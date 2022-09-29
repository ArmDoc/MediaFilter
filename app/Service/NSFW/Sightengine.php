<?php

namespace App\Service\NSFW;

use App\Contracts\INSFWDetect;
use App\Events\FilterMediaEvent;
use App\Facades\NSFW;

/** @package App\Service\NSFW */
class Sightengine implements INSFWDetect
{
	protected $config;
	protected $client;
	protected $model;
	protected $path;

	public function __construct($config = [], $path)
	{
		$this->config = $config;
		$this->path	= $path;
	}

	public function detectPhoto($path)
	{
		$params = $this->config;
		$params['media'] = new \CurlFile($path);

		$output = $this->sendRequest('check.json', $params);

		$array = [
			'nudity'		=> 0.99 - $output['nudity']['none'],
			'weapon'		=> $output['weapon'],
			'alcohol'	=> $output['alcohol'],
			'drugs'		=> $output['drugs'],
			'offensive'	=> $output['offensive']['prob'],
			'scam'		=> $output['scam']['prob'],
		];

		arsort($array, SORT_NUMERIC);

		if (current($array) > 0.5) {
			event(new FilterMediaEvent($path, NSFW::TYPE_PHOTO, false, key($array)));
		} else {
			event(new FilterMediaEvent($path, NSFW::TYPE_PHOTO, true));
		}
	}

	public function detectVideo($path)
	{
		$params = $this->config;
		$params['media'] = new \CurlFile($path);

		$output = $this->sendRequest('video/check-sync.json', $params);

		$array = [
			'nudity' => 0,
			'weapon' => 0,
			'alcohol' => 0,
			'drugs' => 0,
			'offensive' => 0,
			'scam' => 0,
		];

		if (is_array($output['data']['frames'])) {
			foreach ($output['data']['frames'] as $item) {
				if ($item['nudity']['none'] > $array['nudity']) {
					$array['nudity'] = 0.99 - $item['nudity']['none'];
				}

				if ($item['weapon'] > $array['weapon']) {
					$array['weapon'] = $item['weapon'];
				}

				if ($item['alcohol'] > $array['alcohol']) {
					$array['alcohol'] = $item['alcohol'];
				}

				if ($item['drugs'] > $array['drugs']) {
					$array['drugs'] = $item['drugs'];
				}

				if ($item['offensive']['prob'] > $array['offensive']) {
					$array['offensive'] = $item['offensive']['prob'];
				}

				if ($item['scam']['prob'] > $array['scam']) {
					$array['scam'] = $item['scam']['prob'];
				}
			}
		}

		arsort($array, SORT_NUMERIC);

		if (current($array) > 0.5) {
			event(new FilterMediaEvent($path, NSFW::TYPE_VIDEO, false, key($array)));
		} else {
			event(new FilterMediaEvent($path, NSFW::TYPE_VIDEO, true));
		}
	}

	public function detectText($text)
	{
		$params = $this->config;
		$params['text'] = $text;

		$output = $this->sendRequest('text/check.json', $params);

		$array = [
			'profanity' => [],
			'link'		=> [],
		];

		foreach (array_keys($array) as $key) {
			foreach ($output[$key]['matches'] as $matches) {
				switch ($key) {
					case 'profanity':
						if ($matches['intensity'] == 'high') {
							$array[$key][] = $matches['type'];
						}
						break;

					case 'link':
						if ($matches['category'] == 'adult') {
							$array[$key][] = $matches['type'];
						}
						break;
				}
			}
		}

		if (count($array['profanity']) || count($array['link'])) {
			event(new FilterMediaEvent($text, NSFW::TYPE_TEXT, false, json_encode($array)));
		} else {
			event(new FilterMediaEvent($text, NSFW::TYPE_TEXT, true));
		}
	}

	public function sendRequest($uri, $params = [])
	{
		$ch = curl_init('https://api.sightengine.com/1.0/' . $uri);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		$response = json_decode(curl_exec($ch), true);
		curl_close($ch);

		return $response;
	}
}
