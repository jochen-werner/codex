<?php
namespace Codex\Codex\Menus;

use Codex\Codex\Contracts\Factory;
use Codex\Codex\Contracts\Menus\MenuFactory as MenuFactoryContract;
use Codex\Codex\Traits\Hookable;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Symfony\Component\Yaml\Yaml;

/**
 * Menu class.
 *
 * @package   Codex\Codex
 * @author    Codex Project Dev Team
 * @copyright Copyright (c) 2015, Codex Project
 * @license   https://tldrlegal.com/license/mit-license MIT License
 */
class Menu
{
    use Hookable;

    /**
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $items;

    /**
     * @var \Codex\Codex\Factory
     */
    protected $factory;

    /**
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     */
    protected $url;

    /**
     * @var string
     */
    protected $view;

    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $viewFactory;

    /**
     * @var \Codex\Codex\Contracts\Menus\MenuFactory
     */
    protected $menus;

    /**
     * @param \Codex\Codex\Contracts\Menus\MenuFactory            $menus
     * @param \Codex\Codex\Contracts\Factory|\Codex\Codex\Factory $factory
     * @param \Illuminate\Contracts\Filesystem\Filesystem         $files
     * @param \Illuminate\Contracts\Cache\Repository              $cache
     * @param \Illuminate\Routing\Router                          $router
     * @param \Illuminate\Contracts\Routing\UrlGenerator          $url
     * @param \Illuminate\Contracts\View\Factory                  $viewFactory
     */
    public function __construct(MenuFactoryContract $menus, Filesystem $files, Cache $cache, Router $router, UrlGenerator $url, ViewFactory $viewFactory)
    {
        $this->menus       = $menus;
        $this->cache       = $cache;
        $this->router      = $router;
        $this->url         = $url;
        $this->files       = $files;
        $this->viewFactory = $viewFactory;
        $this->view        = 'codex::partials/menu';
        $this->items       = new Collection();

        $this->runHook('menu:ready', [ $this ]);

        $this->items->put('root', new Node('root', $this, 'root'));

        $this->runHook('menu:done', [ $this ]);
    }

    /**
     * render
     *
     * @return string
     */
    public function render()
    {
        return $this->viewFactory->make($this->view, [
            'menu' => $this,
            'items' => $this->get('root')->getChildren()
        ])->render();
    }

    /**
     * add
     *
     * @param        $id
     * @param        $value
     * @param string $parent
     * @param array  $meta
     * @param array  $attributes
     * @return \Codex\Codex\Menus\Node
     */
    public function add($id, $value, $parent = 'root', array $meta = [ ], array $attributes = [ ])
    {
        $node = new Node($id, $this, $value);
        $node
            ->setMeta($meta)
            ->setAttribute($attributes)
            ->setParent($this->get($parent));
        $this->items->put($id, $node);

        return $node;
    }

    /**
     * has
     *
     * @param $id
     * @return bool
     */
    public function has($id)
    {
        return $this->items->has($id);
    }

    /**
     * get
     *
     * @param      $id
     * @param null $default
     * @return \Codex\Codex\Menus\Node
     */
    public function get($id, $default = null)
    {
        return $this->items->get($id, $default);
    }

    /**
     * get view value
     *
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Set the view value
     *
     * @param mixed $view
     * @return Menu
     */
    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }


}
