<?php
namespace Codex\Codex\Filters;

use ParsedownExtra;

class ParsedownFilter extends Filter
{
	/**
	 * Handle the filter.
	 *
	 * @param  \ParsedownExtra  $parsedown
	 * @return array
	 */
	public function handle(ParsedownExtra $parsedown)
	{
		$this->content['body'] = $parsedown->text($this->content['body']);

		return $this->content;
	}
}