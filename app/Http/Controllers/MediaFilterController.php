<?php

namespace App\Http\Controllers;

use App\Facades\NSFW;
use App\Jobs\ProcessMediaFilter;
use App\Service\NSFW\Sightengine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/** @package App\Http\Controllers */
class MediaFilterController extends Controller
{

	public function detectPhoto(Request $request)
	{
		if ($request->hasFile('photo')) {
			if ($request->file('photo')->isValid()) {
				$path = $request->file("photo")->store('public/UploadPhotos');
				dispatch(new ProcessMediaFilter(Storage::path($path), NSFW::TYPE_PHOTO));
				return ['status' => true];
			}
		}
		return ['status' => false];
	}

	public function detectVideo(Request $request)
	{
		if ($request->hasFile('video')) {
			if ($request->file('video')->isValid()) {
				$path = $request->file("video")->store('public/UploadVideo');
				dispatch(new ProcessMediaFilter(Storage::path($path), NSFW::TYPE_VIDEO));
				return ['status' => true];
			}
		}
		return ['status' => false];
	}

	public function detectText(Request $request)
	{
		$text = $request->text;
		if ($text) {
			dispatch(new ProcessMediaFilter($text, NSFW::TYPE_TEXT));
			return ['status' => true];
		}
		return ['status' => false];
	}
}
