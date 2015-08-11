<?php
namespace Codex\Codex\Http\Controllers;

use App\Http\Controllers\Controller;
use Codex\Codex\Factory;

/**
 * This is the CodexController.
 *
 * @package        Codex\Codex
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class CodexController extends Controller
{
    protected $factory;

    /**
     * Create a new CodexContrller instance.
     *
     * @param \Codex\Codex\Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Redirect to the default project and version.
     *
     * @return Redirect
     */
    public function index()
    {
        return redirect(route('codex.document', [
            'projectSlug' => config('codex.default_project')
        ]));
    }

    /**
     * Render the documentation page for the given project and version.
     *
     * @param string   $projectSlug
     * @param string|null   $ref
     * @param string $path
     * @return $this
     */
    public function document($projectSlug, $ref = null, $path = '')
    {
        $project = $this->factory->make($projectSlug);

        if (is_null($ref)) {
            $ref = $project->getDefaultRef();
        }

        $project->setRef($ref);
        $document = $project->getDocument($path);
        $content = $document->render();
        $breadcrumb = $document->getBreadcrumb();
        $menu = $project->getMenu();

        return view($document->attr('view'), compact('project', 'document', 'content', 'menu', 'breadcrumb'))->with([
            'projectName' => $project->getName(),
            'projectRef' => $ref
        ]);

    }
}
