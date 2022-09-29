<?php

namespace App\Jobs;

use App\Facades\NSFW;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMediaFilter implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	private $path;
	private $type;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($path, $type)
	{
		$this->path = $path;
		$this->type = $type;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		switch ($this->type) {
			case NSFW::TYPE_PHOTO:
				NSFW::detectPhoto($this->path);
				break;

			case NSFW::TYPE_VIDEO:
				NSFW::detectVideo($this->path);
				break;

			case NSFW::TYPE_TEXT:
				NSFW::detectText($this->path);
				break;
		}
	}
}
