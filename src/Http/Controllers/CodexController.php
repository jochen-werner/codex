<?php
namespace Codex\Codex\Http\Controllers;

use App\Http\Controllers\Controller;
use Codex\Codex\Contracts\Factory;
use Codex\Codex\Contracts\Menus\MenuFactory;

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
    /**
     * @var \Codex\Codex\Contracts\Factory|\Codex\Codex\Factory
     */
    protected $factory;

    /**
     * @var \Codex\Codex\Contracts\Menus\MenuFactory|\Codex\Codex\Menus\MenuFactory
     */
    protected $menus;

    /**
     * @param \Codex\Codex\Factory           $factory
     * @param \Codex\Codex\Menus\MenuFactory $menus
     */
    public function __construct(Factory $factory, MenuFactory $menus)
    {
        $this->factory = $factory;
        $this->menus = $menus;
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
        $project = $this->factory->getProject($projectSlug);

        if (is_null($ref)) {
            $ref = $project->getDefaultRef();
        }

        $project->setRef($ref);

        $document = $project->getDocument($path);
        $content = '';
        $breadcrumb = [];
        /*
        $project = $this->factory->make($projectSlug);

        if (is_null($ref)) {
            $ref = $project->getDefaultRef();
        }

        $project->setRef($ref);
        $document = $project->getDocument($path);
        $content = $document->render();
        $breadcrumb = $document->getBreadcrumb();
        $menu = $project->getMenu();

        */
        return view($document->attr('view'), compact('project', 'document', 'content', 'breadcrumb'))->with([
            'projectName' => $project->getName(),
            'projectRef' => $ref
        ]);

    }
}
