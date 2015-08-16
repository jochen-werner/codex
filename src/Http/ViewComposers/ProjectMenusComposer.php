<?php
namespace Codex\Codex\Http\ViewComposers;

use Codex\Codex\Factory;
use Codex\Codex\Project;
use Illuminate\Contracts\View\View;

/**
 * This is the ProjectsMenus.
 *
 * @package        Codex\Codex
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class ProjectMenusComposer
{
    protected $factory;

    /** Instantiates the class
     *
     * @param \Codex\Codex\Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * compose
     *
     * @param \Illuminate\Contracts\View\View|\Illuminate\View\View $view
     */
    public function compose(View $view)
    {
        $data = $view->getData();
        $view->with([
            'projectRefList'     => $this->getRefList($data[ 'project' ]),
            'projectsList' => $this->getProjectList()
        ]);
    }

    protected function getRefList(Project $project)
    {
        $list = [ ];
        foreach ($project->getSortedRefs() as $ref) {
            $list[$ref] = $project->url(null, $ref);
        }

        return $list;
    }

    protected function getProjectList()
    {
        $list = [ ];
        foreach ($this->factory->getMenu('projects_menu') as $name => $project) {
            $list[(string)$project['display_name']] = $this->factory->url($name);
            ;
        }

        return $list;
    }
}
