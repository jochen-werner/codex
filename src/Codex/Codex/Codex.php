<?php
namespace Codex\Codex;

use Codex\Codex\Repositories\RepositoryInterface;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Config\Repository as Config;

class Codex
{
	use DispatchesJobs;

	/**
	 * @var \Illuminate\Config\Repository
	 */
	protected $config;

	/**
	 * @var \Codex\Codex\Repositories\RepositoryInterface
	 */
	protected $repository;

	/**
	 * Create a new codex instance.
	 *
	 * @param  CodexRepositoryInterface  $codex
	 * @return void
	 */
	public function __construct(Config $config, RepositoryInterface $repository)
	{
		$this->config     = $config;
		$this->repository = $repository;
	}

	/**
	 * Get the default project.
	 *
	 * @return mixed
	 */
	public function getDefaultProject()
	{
		$projects = $this->getProjects();

		if (! empty($this->config->get('codex.default_project'))) {
			return $this->config->get('codex.default_project');
		} elseif (count($projects) > 0) {
			return strval($projects[0]);
		}

		return null;
	}

	/**
	 * Get the default version for the given project.
	 *
	 * @param  string  $project
	 * @return string
	 */
	public function getDefaultVersion($project)
	{
		$versions = $this->getVersions($project);

		return $versions[0];
	}

	/**
	 * Get all projects from documentation directory.
	 *
	 * @return array
	 */
	public function getProjects()
	{
		return $this->repository->getProjects();
	}

	/**
	 * Get all versions fro the given project.
	 *
	 * @param  string  $project
	 * @return array
	 */
	public function getVersions($project)
	{
		return $this->repository->getVersions($project);
	}

	/**
	 * Get the given documentation page.
	 *
	 * @param  string  $project
	 * @param  string  $version
	 * @param  string  $page
	 * @return array|Exception
	 */
	public function get($project, $version, $page)
	{
		$rawContent = $this->repository->get($project, $version, $page);

		if (! is_null($rawContent)) {
			return $this->parse($rawContent);
		}

		abort(404);
	}

	/**
	 * Parse the supplied raw content and return it.
	 *
	 * @param  string  $rawContent
	 * @return array
	 */
	public function parse($rawContent)
	{
		$filters = $this->config->get('codex.filters');

		$content['body'] = $rawContent;

		foreach ($filters as $filter) {
			$content = $this->dispatch(new $filter($content));
		}

		return $content;
	}
}