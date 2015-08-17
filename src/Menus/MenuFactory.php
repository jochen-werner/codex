<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Codex\Codex\Menus;

use Codex\Codex\Contracts\Menus\MenuFactory as MenuFactoryContract;
use Codex\Codex\Traits\Hookable;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\View\Factory as View;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;

/**
 * This is the MenuFactory.
 *
 * @package        Codex\Codex
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class MenuFactory implements MenuFactoryContract
{
    use Hookable;

    /**
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     */
    protected $url;

    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $menus;

    /**
     * @param \Illuminate\Contracts\Container\Container   $container
     * @param \Illuminate\Contracts\Filesystem\Filesystem $files
     * @param \Illuminate\Contracts\Cache\Repository      $cache
     * @param \Illuminate\Routing\Router                  $router
     * @param \Illuminate\Contracts\Routing\UrlGenerator  $url
     * @param \Illuminate\Contracts\View\Factory          $view
     */
    public function __construct(Container $container, Filesystem $files, Cache $cache, Router $router, UrlGenerator $url, View $view)
    {
        $this->container = $container;
        $this->files     = $files;
        $this->cache     = $cache;
        $this->router    = $router;
        $this->url       = $url;
        $this->view      = $view;
        $this->menus     = new Collection();

        $this->runHook('menu-factory:ready', [$this]);
    }


    /**
     * Creates a new menu or returns an existing
     *
     * @param string $id
     * @return \Codex\Codex\Menus\Menu
     */
    public function add($id)
    {
        if ($this->has($id)) {
            return $this->get($id);
        }

        $menu = $this->container->make(Menu::class, [
            'menuFactory' => $this
        ]);
        $this->runHook('menu-factory:add', [$this, $menu]);
        $this->menus->put($id, $menu);
        return $menu;
    }


    /**
     * Returns a menu
     *
     * @param string $id
     * @param null $default
     * @return \Codex\Codex\Menus\Menu
     */
    public function get($id, $default = null)
    {
        return $this->menus->get($id, $default);
    }

    /**
     * has
     *
     * @param $id
     * @return bool
     */
    public function has($id)
    {
        return $this->menus->has($id);
    }

    /**
     * Removes a menu
     *
     * @param $id
     * @return MenuFactory
     */
    public function forget($id)
    {
        $this->runHook('menu-factory:add', [$this, $id]);
        $this->menus->forget($id);
        return $this;
    }
}
