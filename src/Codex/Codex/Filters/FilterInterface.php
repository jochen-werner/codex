<?php
namespace Codex\Codex\Filters;

interface FilterInterface
{
	/**
	 * Handle the filter.
	 *
	 * @param  string  $content
	 * @return mixed
	 */
	public function handle($content);
}