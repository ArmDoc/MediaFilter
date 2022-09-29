<?php

return [
	'SightengineDetect'	=> [
		'models'				=> 'nudity-2.0,wad,offensive,scam,gore',
		'lang'				=> 'en',
		'opt_countries'	=> 'us,gb,fr',
		'mode'				=> 'standard',
		'api_user'			=> env('SIGHTENGINE_USER'),
		'api_secret'		=> env('SIGHTENGINE_KEY'),
	]
];
