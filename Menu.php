<?php
namespace Codex\Codex;

use Caffeinated\Beverage\Arr;
use Caffeinated\Beverage\Str;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Traits\Macroable;
use Symfony\Component\Yaml\Yaml;

/**
 * Menu class.
 *
 * @package   Codex\Codex
 * @author    Codex Project Dev Team
 * @copyright Copyright (c) 2015, Codex Project
 * @license   https://tldrlegal.com/license/mit-license MIT License
 */
class Menu3 implements Jsonable, Arrayable
{
    use Macroable;

    /**
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var array
     */
    protected $menu;

    /**
     * @var array
     */
    protected $flatMenu = [ ];

    /**
     * @var string
     */
    protected $path;

    /**
     * @var \Codex\Codex\Project
     */
    protected $project;

    /**
     * @var string
     */
    protected $raw;

    /**
     * @param  \Codex\Codex\Project                   $project
     * @param  Filesystem                             $files
     * @param  \Illuminate\Contracts\Cache\Repository $cache
     * @param  string                                 $path
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct(Project $project, Filesystem $files, Cache $cache, $path)
    {
        $this->cache   = $cache;
        $this->files   = $files;
        $this->path    = $path;
        $this->project = $project;
        Factory::run('menu:ready', [ $this ]);

        $this->raw  = $files->get($path);
        $this->menu = $this->parse($this->raw);

        Factory::run('menu:done', [ $this ]);
    }

    /**
     * getDocumentBreadcrumb
     *
     * @param \Codex\Codex\Document $document
     */
    public function getDocumentBreadcrumb(Document $document)
    {
        $url  = $document->url();
        $item = $this->findItemBy('href', $url);
        $parents = [];
        if ( isset($item) )
        {
            $parents = $this->getItemParents($item);
            $item['last'] = true;
            $parents[] = $item;
        }

        return $parents;
    }

    protected function findItemBy($key, $value, array $menuItems = null)
    {
        if ( is_null($menuItems) )
        {
            $menuItems = $this->flatMenu;
        }

        foreach ( $menuItems as $i => $item )
        {
            if ( isset($item[ $key ]) && $item[ $key ] === $value )
            {
                return $item;
            }
        }
    }

    protected function getItemParents($item, array &$parents = [ ])
    {
        if ( isset($item[ 'parent_id' ]) )
        {
            $parent = $this->findItemBy('id', $item[ 'parent_id' ]);
            $parents[] = $parent;
            $this->getItemParents($parent, $parents);
        }
        return $parents;
    }

    /**
     * Parse the menu config.
     *
     * @param  string $string
     * @return string
     */
    protected function parseConfig($string)
    {
        foreach ( array_dot($this->project->getConfig()) as $key => $value )
        {
            $string = str_replace('${project.' . $key . '}', $value, $string);
        }

        return $string;
    }

    /**
     * Parse the YAML markup.
     *
     * @param  string $yaml
     * @return array
     */
    protected function parse($yaml)
    {
        $array = Yaml::parse($yaml);

        return $this->resolveMenu($array[ 'menu' ]);
    }

    /**
     * Resolve the menu and return the items.
     *
     * @param  array $items
     * @return array
     */
    protected function resolveMenu($items, $parentId = null)
    {
        $menu = [ ];

        foreach ( $items as $key => $value )
        {
            $key   = $this->parseConfig($key);
            $value = $this->parseConfig($value);

            // key   = title
            // value = relative page path
            if ( is_string($key) and is_string($value) )
            {
                $item = [
                    'name' => $key,
                    'href' => $this->resolveLink($value)
                ];
            }
            elseif ( is_string($key) and $key === 'children' and is_array($value) )
            {
                $item = $this->resolveMenu($value);
            }
            elseif ( isset($value[ 'name' ]) )
            {
                $item = [
                    'name' => $value[ 'name' ]
                ];

                // resolve href
                if ( isset($value[ 'href' ]) )
                {
                    $item[ 'href' ] = $this->resolveLink($value[ 'href' ]);
                }
                elseif ( isset($value[ 'page' ]) )
                {
                    $item[ 'href' ] = $this->resolveLink($value[ 'page' ]);
                }
            }
            if ( isset($item) )
            {
                // generate id
                $id           = md5($item[ 'name' ] . (isset($item[ 'href' ]) ? $item[ 'href' ] : ''));
                $item[ 'id' ] = $id;

                // parent id
                if ( ! is_null($parentId) )
                {
                    $item[ 'parent_id' ] = $parentId;
                }

                // icon
                if ( isset($value[ 'icon' ]) )
                {
                    $item[ 'icon' ] = $value[ 'icon' ];
                }

                // children
                if ( isset($value[ 'children' ]) and is_array($value[ 'children' ]) )
                {
                    $item[ 'children' ] = $this->resolveMenu($value[ 'children' ], $id);
                }



                $this->flatMenu[] = Arr::except($item, ['children']);
                $menu[]           = $item;
            }
        }

        return $menu;
    }

    /**
     * Resolve the given link.
     *
     * @param  string $link
     * @return string
     */
    protected function resolveLink($link)
    {
        if ( Str::startsWith('http', $link, false) )
        {
            return $link;
        }
        else
        {
            $path = Str::endsWith($link, '.md', false) ? Str::remove($link, '.md') : $link;

            return $this->project->getFactory()->url($this->project, $this->project->getRef(), $path);
        }
    }

    /**
     * Return the menu instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->menu;
    }

    /**
     * Return the menu instance as JSON.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->menu, $options);
    }


    /*
     |---------------------------------------------------------------------
     | Getters / Setters
     |---------------------------------------------------------------------
     |
     */

    /**
     * get menu value
     *
     * @return array
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set the menu value
     *
     * @param array $menu
     * @return Menu
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * get cache value
     *
     * @return Cache
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * Set the cache value
     *
     * @param Cache $cache
     * @return Menu
     */
    public function setCache($cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * get files value
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set the files value
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @return Menu
     */
    public function setFiles($files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * get flatMenu value
     *
     * @return array
     */
    public function getFlatMenu()
    {
        return $this->flatMenu;
    }

    /**
     * Set the flatMenu value
     *
     * @param array $flatMenu
     * @return Menu
     */
    public function setFlatMenu($flatMenu)
    {
        $this->flatMenu = $flatMenu;

        return $this;
    }

    /**
     * get path value
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the path value
     *
     * @param string $path
     * @return Menu
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * get project value
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set the project value
     *
     * @param Project $project
     * @return Menu
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * get raw value
     *
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * Set the raw value
     *
     * @param string $raw
     * @return Menu
     */
    public function setRaw($raw)
    {
        $this->raw = $raw;

        return $this;
    }


}
