<?php
namespace Codex\Codex\Http\Controllers;

use Config;
use Redirect;
use App\Http\Controllers\Controller;
use Codex\Codex\Repositories\Interfaces\CodexRepositoryInterface;

class CodexController extends Controller
{
	/**
	 * @var Codex\Codex\Repositories\Interfaces\CodexRepositoryInterface
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
	 * @param  Codex\Codex\Repositories\Interfaces\CodexRepositoryInterface  $codex
	 */
	public function __construct(CodexRepositoryInterface $codex)
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

		$toc     = $this->codex->getToc($project, $version);
		$content = $this->codex->get($project, $version, $page ?: 'introduction');

		return view('codex::show', compact('content'));
	}
}
