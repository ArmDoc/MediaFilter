<?php

namespace App\Contracts;

interface INSFWDetect
{
	public function detectPhoto($path);
	public function detectVideo($path);
	public function detectText($path);
}
