<?php
namespace Codex\Codex\Repositories\Flat;

use Codex\Codex\Repositories\AbstractCodexRepository;

class CodexRepository extends AbstractCodexRepository
{
	/**
	 * Get projects table of contents file, if it exists.
	 *
	 * @param  string  $project
	 * @param  string  $version
	 * @return string|null
	 */
	public function getToc($project, $version)
	{
		$tocFile = $this->storagePath."/{$project}/{$version}/toc.md";

		if ($this->files->exists($tocFile)) {
			return $this->parseMarkdown($this->files->get($tocFile));
		}

		return null;
	}

	/**
	 * Get the given documentation page.
	 *
	 * @param  string  $project
	 * @param  string  $version
	 * @param  string  $page
	 * @return string|Exception
	 */
	public function get($project, $version, $page)
	{
		$pageFile = $this->storagePath."/{$project}/{$version}/{$page}.md";

		if ($this->files->exists($pageFile)) {
			return $this->parseMarkdown($this->files->get($pageFile));
		}

		abort(404);
	}
}
