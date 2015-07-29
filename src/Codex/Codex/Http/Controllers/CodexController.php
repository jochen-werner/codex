<?php
namespace Codex\Codex\Http\Controllers;

use Config;
use Redirect;
use Request;
use App\Http\Controllers\Controller;
use Codex\Codex\Codex;

class CodexController extends Controller
{
	/**
	 * @var Codex\Codex\Codex
	 */
	protected $codex;

	/**
	 * @var string
	 */
	protected $defaultProject;

	/**
	 * @var string
	 */
	protected $defaultVersion;

	/**
	 * @var string
	 */
	protected $rootUrl;

	/**
	 * Create a new controller instance.
	 *
	 * @param  Codex\Codex\Codex  $codex
	 */
	public function __construct(Codex $codex)
	{
		$this->codex          = $codex;
		$this->defaultProject = $this->codex->getDefaultProject();
		$this->defaultVersion = $this->codex->getDefaultVersion($this->defaultProject);
		$this->rootUrl        = url(Config::get('codex.route_base')."/{$this->defaultProject}/{$this->defaultVersion}");
	}

	public function index()
	{
		return Redirect::to(url($this->rootUrl));
	}

	/**
	 * Render the documentation page.
	 *
	 * @param  string  $project
	 * @param  string  $version
	 * @param  string  $page
	 */
	public function show($project, $version = null, $page = null)
	{
		if (is_null($version)) {
			return Redirect::to(url("{$project}/".$this->codex->getDefaultVersion($project)));
		}

		$content = $this->codex->get($project, $version, $page ?: 'introduction');
		$toc     = $this->codex->getToc($project, $version);

		return view('codex::show', compact('content', 'toc'));
	}

	/**
	 * Search through documentation.
	 *
	 * @param  string  $project
	 * @param  string  $version
	 */
	public function search($project = null, $version = null)
	{
		if (is_null($project)) {
			$project = $this->defaultProject;
		}

		if (is_null($version)) {
			$version = $this->defaultVersion;
		}

		$toc     = $this->codex->getToc($project, $version);
		$search  = Request::get('q');
		$results = $this->codex->search($project, $version, $search);

		return view('codex::search', compact('toc', 'search', 'results'));
	}
}
