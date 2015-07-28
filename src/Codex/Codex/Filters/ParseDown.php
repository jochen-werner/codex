<?php
namespace Codex\Codex\Filters;

use Symfony\Component\Yaml\Yaml;

class ParseDown implements FilterInterface
{
	/**
	 * @var \Symfony\Component\Yaml\Yaml
	 */
	protected $yaml;

	/**
	 * Create a new instance of FrontMatter.
	 *
	 * @param  Yaml  $yaml
	 * @return void
	 */
	public function __construct(Yaml $yaml)
	{
		$this->yaml = $yaml;
	}

	/**
	 * Handle the filter.
	 *
	 * @param  array  $content
	 * @return array
	 */
	public function handle($content)
	{
		$parts = preg_split($this->regex, $content['body'], 3);

		if (count($parts) === 3) {
			$content['frontmatter'] = $this->yaml->load($parts[2]);
			$content['body']        = trim($parts[3]);
		}

		return $content;
	}
}