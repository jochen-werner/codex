<?php
namespace Codex\Codex\Repositories\Flat;

use Codex\Codex\Repositories\AbstractCodexRepository;

class CodexRepository extends AbstractCodexRepository
{
	/**
	 * Get manuals table of contents file, if it exists.
	 *
	 * @param  string  $manual
	 * @param  string  $version
	 * @return string|null
	 */
	public function getToc($manual, $version)
	{
		$tocFile = $this->storagePath."/{$manual}/{$version}/toc.md";

		if ($this->files->exists($tocFile)) {
			return $this->parseMarkdown($this->files->get($tocFile));
		}

		return null;
	}

	/**
	 * Get the given documentation page.
	 *
	 * @param  string  $manual
	 * @param  string  $version
	 * @param  string  $page
	 * @return string|Exception
	 */
	public function get($manual, $version, $page)
	{
		$pageFile = $this->storagePath."/{$manual}/{$version}/{$page}.md";

		if ($this->files->exists($pageFile)) {
			return $this->parseMarkdown($this->files->get($pageFile));
		}

		abort(404);
	}
}
