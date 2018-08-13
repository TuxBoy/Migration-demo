<?php
namespace App\Entity;

use SDAM\Traits\HasTimestamp;

class Tag
{
	use HasTimestamp;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $slug;

}