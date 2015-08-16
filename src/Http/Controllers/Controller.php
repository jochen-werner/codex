<?php
namespace Codex\Codex\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Codex\Codex\Contracts\Factory;
use Codex\Codex\Contracts\Menus\MenuFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;

/**
 * This is the CodexController.
 *
 * @package        Codex\Codex
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class Controller extends BaseController
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
     * @var \Illuminate\View\Factory
     */
    protected $view;

    /**
     * @param \Codex\Codex\Contracts\Factory           $factory
     * @param \Codex\Codex\Contracts\Menus\MenuFactory $menus
     * @param \Illuminate\Contracts\View\Factory       $view
     */
    public function __construct(Factory $factory, MenuFactory $menus, ViewFactory $view)
    {
        $this->factory = $factory;
        $this->menus = $menus;
        $this->view = $view;
    }

}
