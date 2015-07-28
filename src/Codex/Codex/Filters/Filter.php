<?php
namespace Codex\Codex\Filters;

use Illuminate\Contracts\Bus\SelfHandling;

abstract class Filter implements SelfHandling
{
	/**
	 * @var array
	 */
	public $content;

	/**
	 * Create a new instance of Filter.
	 *
	 * @param  array  $content
	 * @return void
	 */
	public function __construct($content)
	{
		$this->content = $content;
	}
}