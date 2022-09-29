<?php

namespace App\Providers;

use App\Contracts\INSFWDetect;
use Illuminate\Support\ServiceProvider;
use App\Service\NSFW\Sightengine;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(INSFWDetect::class, function () {
			return new Sightengine(config('NSFW.SightengineDetect'));
		});
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}
}
