<?php
namespace Codex\Codex\Repositories;

use Parsedown;
use Codex\Codex\Repositories\Interfaces\CodexRepositoryInterface;
use Illuminate\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem as Files;

abstract class AbstractCodexRepository implements CodexRepositoryInterface
{
	/**
	 * @var Illuminate\Config\Repository
	 */
	protected $config;

	/**
	 * @var Illuminate\Filesystem\Filesystem
	 */
	protected $files;

	/**
	 * @var Parsedown
	 */
	protected $parsedown;

	/**
	 * @var string
	 */
	protected $storagePath;

	/**
	 * Create a new AbstractCodexRepository instance.
	 *
	 * @param  Illuminate\Config\Repository           $config
	 * @param  Illuminate\Filesystem\Filesystem       $files
	 * @param  League\CommonMark\CommonMarkConverter  $commonmark
	 */
	public function __construct(Config $config, Files $files, Parsedown $parsedown)
	{
		$this->config      = $config;
		$this->files       = $files;
		$this->parsedown   = $parsedown;
		$this->storagePath = $this->config->get('codex.storage_path');
	}

	/**
	 * Get the default manual.
	 *
	 * @return mixed
	 */
	public function getDefaultManual()
	{
		$manuals = $this->getManuals();

		if (! empty($this->config->get('codex.default_manual'))) {
			return $this->config->get('codex.default_manual');
		} elseif (count($manuals) > 0) {
			return strval($manuals[0]);
		}

		return null;
	}

	/**
	 * Get the default version for the given manual.
	 *
	 * @param  string  $manual
	 * @return string
	 */
	public function getDefaultVersion($manual)
	{
		$versions = $this->getVersions($manual);

		return $versions[0];
	}

	/**
	 * Get all manuals from documentation directory.
	 *
	 * @return array
	 */
	public function getManuals()
	{
		$manuals = $this->getDirectories($this->storagePath);

		return $manuals;
	}

	/**
	 * Get all versions fro the given manual.
	 *
	 * @param  string  $manual
	 * @return array
	 */
	public function getVersions($manual)
	{
		$alpha     = array();
		$numeric   = array();
		$manualDir = "{$this->storagePath}/{$manual}";
		$versions  = $this->getDirectories($manualDir);

		foreach ($versions as $version) {
			if (ctype_alpha(substr($version, 0, 2))) {
				$alpha[] = $version;
			} else {
				$numeric[] = $version;
			}
		}

		sort($alpha);
		rsort($numeric);

		if ($this->config->get('codex.version_ordering') == 'numeric-first') {
			return array_merge($numeric, alpha);
		} else {
			return array_merge($alpha, $numeric);
		}
	}

	/**
	 * Return an array of folders within the supplied path.
	 *
	 * @param  string  $path
	 * @return array
	 */
	public function getDirectories($path)
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
