<?php
namespace Codex\Codex\Http\Controllers;

use Config;
use Redirect;
use Request;
use App\Http\Controllers\Controller;

class CodexController extends Controller
{
    /**
     * Create a new CodexContrller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 
    }

    /**
     * Redirect to the default project and version.
     *
     * @return Redirect
     */
    public function index()
    {
        // 
    }

    /**
     * Render the documentation page for the given project and version.
     *
     * @param  string       $project
     * @param  null|string  $version
     * @param  null|string  $page
     * @return Response
     */
    public function show($project, $version = null, $page = null)
    {
        // 
    }
}
