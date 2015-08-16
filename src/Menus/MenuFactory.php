<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Codex\Codex\Menus;

use Codex\Codex\Contracts\Menus\MenuFactory as MenuFactoryContract;
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
    protected $container;

    protected $files;

    protected $cache;

    protected $router;

    protected $url;

    protected $view;

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
    }


    /**
     * createMenu
     *
     * @param $id
     * @return \Codex\Codex\Menus\Menu
     */
    public function add($id)
    {
        if ( $this->has($id) )
        {
            return $this->get($id);
        }

        $menu = $this->container->make(Menu::class, [
            'menuFactory' => $this
        ]);
        $this->menus->put($id, $menu);

        return $menu;
    }


    /**
     * get
     *
     * @param      $id
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
     * forget
     *
     * @param $id
     * @return MenuFactory
     */
    public function forget($id)
    {
        $this->menus->forget($id);
        return $this;
    }

}
