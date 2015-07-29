<?php
namespace Codex\Codex\Repositories;

use Illuminate\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;

class FlatRepository implements RepositoryInterface
{
	/**
	 * @var Illuminate\Config\Repository
	 */
	protected $config;

	/**
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $files;

	/**
	 * @var string
	 */
	protected $storagePath;

	/**
	 * Create a new instance of FlatRepository.
	 *
	 * @param  Filesystem  $files
	 * @return void
	 */
	public function __construct(Config $config, Filesystem $files)
	{
		$this->config      = $config;
		$this->files       = $files;
		$this->storagePath = $this->config->get('codex.storage_path');
	}

	/**
	 * Get the given documentation page.
	 *
	 * @param  string  $project
	 * @param  string  $version
	 * @param  string  $page
	 * @return string|null
	 */
	public function get($project, $version, $page)
	{
		$file = $this->storagePath."/{$project}/{$version}/{$page}.md";

		if ($this->files->exists($file)) {
			return $this->files->get($file);
		}

		return null;
	}

	/**
	 * Get all projects.
	 *
	 * @return array
	 */
	public function getProjects()
	{
		return $this->getDirectories($this->storagePath);
	}

	/**
	 * Get all versions for the given project.
	 *
	 * @param  string  $project
	 * @return array
	 */
	public function getVersions($project)
	{
		$projectDir = "{$this->storagePath}/{$project}";
		
		return $this->getDirectories($projectDir);		
	}

	/**
	 * Search project for given string.
	 *
	 * @param  string  $project
	 * @param  string  $version
	 * @param  string  $needle
	 * @return array
	 */
	public function search($project, $version, $needle = '')
	{
		$results   = [];
		$directory = $this->storagePath.'/'.$project.'/'.$version;
		$files     = preg_grep('/toc\.md$/', $this->files->allFiles($directory),
		 	PREG_GREP_INVERT);
		
		if ( ! empty($needle)) {
			foreach ($files as $file) {
				$haystack = file_get_contents($file);
		
				if ($this->config->get('codex.route_base') !== '') {
					$fileUrl  = '/'.$this->config->get('codex.route_base');
					$fileUrl .= str_replace([$this->storagePath, '.md'], '', (string)$file);
				} else {
					$fileUrl = str_replace([$this->storagePath, '.md'], '', (string)$file);
				}
		
				if (strpos(strtolower($haystack), strtolower($needle)) !== false) {
					$results[] = [
						'title' => $file,
						'url'   => $fileUrl,
					];
				}
			}
		}
		
		return $results;
	}

	/**
	 * Return an array of folders within the supplied path.
	 *
	 * @param  string  $path
	 * @return array
	 */
	protected function getDirectories($path)
	{
		if ($this->files->exists($path) === false) {
			abort(404);
		}

		$directories = $this->files->directories($path);
		$folders     = array();

		if (count($directories) > 0) {
			foreach ($directories as $dir) {
				$dir       = str_replace('\\', '/', $dir);
				$folder    = explode('/', $dir);
				$folders[] = end($folder);
			}
		}

		return $folders;
	}
}