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
	protected $defaultManual;

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
		$this->defaultManual  = $this->codex->getDefaultManual();
		$this->defaultVersion = $this->codex->getDefaultVersion($this->defaultManual);
		$this->rootUrl        = url(Config::get('codex.route_base')."/{$this->defaultManual}/{$this->defaultVersion}");
	}

	public function index()
	{
		return Redirect::to(url($this->rootUrl));
	}

	/**
	 * Render the documentation page.
	 *
	 * @param  string  $manual
	 * @param  string  $version
	 * @param  string  $page
	 */
	public function show($manual, $version = null, $page = null)
	{
		if (is_null($version)) {
			return Redirect::to(url("{$manual}/".$this->codex->getDefaultVersion($manual)));
		}

		$toc     = $this->codex->getToc($manual, $version);
		$content = $this->codex->get($manual, $version, $page ?: 'introduction');

		return view('codex::show', compact('content'));
	}
}
