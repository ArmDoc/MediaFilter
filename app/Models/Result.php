<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** @package App\Models */
class Result extends Model
{
	use HasFactory;

	protected $fillable = [
		'type',
		'status',
		'reason',
	];
}
