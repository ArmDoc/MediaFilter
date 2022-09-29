<?php

namespace App\Facades;

use App\Contracts\INSFWDetect;
use Illuminate\Support\Facades\Facade;

class NSFW extends Facade
{
	const TYPE_PHOTO	= 1;
	const TYPE_VIDEO	= 2;
	const TYPE_TEXT	= 3;

	protected static function getFacadeAccessor()
	{
		return INSFWDetect::class;
	}
}
