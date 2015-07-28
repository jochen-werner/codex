<?php
namespace Codex\Codex\Filters;

use Symfony\Component\Yaml\Yaml;

class FrontMatterFilter extends Filter
{
	/**
	 * Handle the filter.
	 *
	 * @return array
	 */
	public function handle()
	{
		$regex = '~^('.implode('|', array_map('preg_quote', ['---']))
			."){1}[\r\n|\n]*(.*?)[\r\n|\n]+("
			.implode('|', array_map('preg_quote', ['---']))
			."){1}[\r\n|\n]*(.*)$~s";

		if (preg_match($regex, $this->content['body'], $matches) === 1) {
			$this->content['frontmatter'] = Yaml::parse($matches[2]);
			$this->content['body']        = $matches[4];
		}

		return $this->content;
	}
}