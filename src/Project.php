<?php
namespace Codex\Codex;

use Caffeinated\Beverage\Path;
use Caffeinated\Beverage\Str;
use Codex\Codex\Contracts\Factory;
use Codex\Codex\Traits\Hookable;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use vierbergenlars\SemVer\version;

/**
 * Project class.
 *
 * @package   Codex\Codex
 * @author    Codex Project Dev Team
 * @copyright Copyright (c) 2015, Codex Project
 * @license   https://tldrlegal.com/license/mit-license MIT License
 */
class Project
{
    use Hookable;

    const SHOW_MASTER_BRANCH = 0;
    const SHOW_LAST_VERSION = 1;
    const SHOW_LAST_VERSION_OTHERWISE_MASTER_BRANCH = 2;
    const SHOW_CUSTOM = 3;

    /**
     * @var array
     */
    protected $branches;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $defaultRef;

    /**
     * @var \Codex\Codex\Factory
     */
    protected $factory;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $ref;

    /**
     * @var array
     */
    protected $refs;

    /**
     * @var array
     */
    protected $versions;

    /**
     * A collection of documents that have already been instantiated. getDocument method will first check if the document is here.
     *
     * @var Document[]
     */
    protected $documents = [ ];

    /**
     * The menu instance, getMenu will first check if this property is set and if so returns it.
     * Otherwise it will instanciate a new Menu and set this property
     *
     * @var Menu
     */
    protected $menu;

    /**s
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * @param \Codex\Codex\Factory                        $factory
     * @param \Illuminate\Contracts\Filesystem\Filesystem $files
     * @param \Illuminate\Contracts\Container\Container   $container
     * @param                                             $name
     * @param                                             $config
     */
    public function __construct(Factory $factory, Filesystem $files, Container $container, $name, $config)
    {
        $this->container = $container;
        $this->factory   = $factory;
        $this->files     = $files;
        $this->name      = $name;
        $this->config    = $config;
        $this->path      = $path = Path::join($factory->getRootDir(), $name);


        $this->runHook('project:ready', [ $this ]);

        # Resolve refs

        $directories = $this->files->directories($this->path);
        $branches    = [ ];
        $this->refs  = [ ];

        $this->versions = array_filter(array_map(function ($dirPath) use ($path, $name, &$branches)
        {
            $version      = Str::create(Str::ensureLeft($dirPath, '/'))->removeLeft($path)->removeLeft(DIRECTORY_SEPARATOR);
            $version      = (string)$version->removeLeft($name . '/');
            $this->refs[] = $version;

            try
            {
                return new version($version);
            }
            catch (\RuntimeException $e)
            {
                $branches[] = $version;
            }
        }, $directories), 'is_object');

        $this->branches = $branches;

        // check which version/branch to show by default
        $defaultRef = count($this->versions) > 0 ? head($this->versions) : head($branches);

        switch ( $this->config[ 'default' ] )
        {
            case Project::SHOW_LAST_VERSION:
                usort($this->versions, function (version $v1, version $v2)
                {
                    return version::gt($v1, $v2) ? -1 : 1;
                });

                $defaultRef = head($this->versions);
                break;
            case Project::SHOW_LAST_VERSION_OTHERWISE_MASTER_BRANCH:
                if ( count($this->versions) > 0 )
                {
                    usort($this->versions, function (version $v1, version $v2)
                    {
                        return version::gt($v1, $v2) ? -1 : 1;
                    });
                }

                $defaultRef = count($this->versions) > 0 ? head($this->versions) : head($branches);
                break;
            case Project::SHOW_MASTER_BRANCH:
                $defaultRef = 'master';
                break;
            case Project::SHOW_CUSTOM:
                $defaultRef = $this->config[ 'custom' ];
                break;
        }

        $this->ref = $this->defaultRef = (string)$defaultRef;

        # Resolve menu
        $this->runHook('project:done', [ $this ]);
    }

    /**
     * url
     *
     * @param string $doc
     * @param null   $ref
     * @return string
     */
    public function url($doc = 'index', $ref = null)
    {
        return $this->factory->url($this, $ref, $doc);
    }

    /**
     * getDocument
     *
     * @param string $pathName
     * @return \Codex\Codex\Document
     */
    public function getDocument($pathName = '')
    {
        if ( $pathName === '' )
        {
            $pathName = 'index';
        }

        if ( ! isset($this->documents[ $pathName ]) )
        {
            $path = Path::join($this->path, $this->ref, $pathName . '.md');

            $this->documents[ $pathName ] = $this->container->make(Document::class, [
                'factory'  => $this->factory,
                'project'  => $this,
                'path'     => $path,
                'pathName' => $pathName
            ]);
            //new Document($this->factory, $this, $this->files, $path, $pathName);

            $this->runHook('project:document', [ $this->documents[ $pathName ] ]);
        }


        return $this->documents[ $pathName ];
    }


    # Menu

    /**
     * getDocumentsMenu
     *
     * @return \Codex\Codex\Menus\Menu
     */
    public function getDocumentsMenu()
    {

        $yaml  = $this->files->get(Path::join($this->path, $this->ref, 'menu.yml'));
        $array = Yaml::parse($yaml);
        $this->factory->getMenus()->forget('project_sidebar_menu');

        $menu = $this->resolveDocumentsMenu($array[ 'menu' ]);
        $menu->setView('codex::menus/project-sidebar');
        $this->runHook('project:menu:documents', [$this, $menu]);

        return $menu;
    }

    /**
     * getRefsMenu
     *
     * @return \Codex\Codex\Menus\Menu
     */
    public function getRefsMenu()
    {
        $menus = $this->factory->getMenus();
        $menus->forget('project_versions_menu');
        /**
         * @var Menus\Menu $menu
         */
        $menu = $menus->add('project_versions_menu');
        foreach ( $this->getSortedRefs() as $ref )
        {
            $node = $menu->add($ref, $ref);
            $node->setAttribute('href', $this->factory->url($this, $ref));
        }

        $menu->setView('codex::menus/project-refs');
        $this->runHook('project:menu:refs', [$this, $menu]);
        return $menu;
    }

    /**
     * resolveMenu
     *
     * @param        $items
     * @param string $parentId
     * @return \Codex\Codex\Menus\Menu
     */
    protected function resolveDocumentsMenu($items, $parentId = 'root')
    {
        /**
         * @var Menus\Menu $menu
         */
        $menu = $this->factory->getMenus()->add('project_sidebar_menu');

        foreach ( $items as $item )
        {
            $link = '';
            if ( array_key_exists('document', $item) )
            {
                // remove .md extension if present
                $path = Str::endsWith($item[ 'document' ], '.md', false) ? Str::remove($item[ 'document' ], '.md') : $item[ 'document' ];
                $link = $this->factory->url($this, $this->ref, $path);
            }
            elseif ( array_key_exists('href', $item) )
            {
                $link = $item[ 'href' ];
            }

            $id = md5($item[ 'name' ] . $link);

            $node = $menu->add($id, $item[ 'name' ], $parentId);
            $node->setAttribute('href', $link);
            $node->setAttribute('id', $id);

            if ( isset($item[ 'icon' ]) )
            {
                $node->setMeta('icon', $item[ 'icon' ]);
            }

            if ( isset($item[ 'children' ]) )
            {
                $this->resolveDocumentsMenu($item[ 'children' ], $id);
            }
        }

        return $menu;
    }



    # Config

    /**
     * Retreive this projects config using a dot notated key
     *
     * @param  null|string $key
     * @param  null|mixed  $default
     * @return callable
     */
    public function config($key = null, $default = null)
    {
        if ( is_null($key) )
        {
            return $this->config;
        }

        return array_get($this->config, $key, $default);
    }

    /**
     * Set config.
     *
     * @param  array $config
     * @return void
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }


    # Refs / versions

    /**
     * Set the ref (version/branch) you want to use. getDocument will be getting stuff using the ref
     *
     * @param  string $name
     * @return \Codex\Codex\Project
     */
    public function setRef($name)
    {
        $this->ref = $name;

        return $this;
    }

    /**
     * Get ref.
     *
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Get default ref.
     *
     * @return string
     */
    public function getDefaultRef()
    {
        return $this->defaultRef;
    }

    /**
     * Get refs.
     *
     * @return array
     */
    public function getRefs()
    {
        return $this->refs;
    }

    /**
     * Get refs sorted by the configured order.
     *
     * @return array
     */
    public function getSortedRefs()
    {
        $versions = $this->versions;

        usort($versions, function (version $v1, version $v2)
        {
            return version::gt($v1, $v2) ? -1 : 1;
        });

        $versions = array_map(function (version $v)
        {
            return $v->getVersion();
        }, $versions);

        return array_merge($this->branches, $versions);
    }


    # Getters / setters

    /**
     * Get project files.
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set project files.
     *
     * @param  \Illuminate\Contracts\Filesystem\Filesystem $files
     * @return \Codex\Codex\Project
     */
    public function setFiles($files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get config.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get branches.
     *
     * @return array
     */
    public function getBranches()
    {
        return $this->branches;
    }

    /**
     * Get versions.
     *
     * @return array
     */
    public function getVersions()
    {
        return $this->versions;
    }

    /**
     * Set path.
     *
     * @param  string $path
     * @return \Codex\Codex\Project
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get factory.
     *
     * @return \Codex\Codex\Factory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * Set factory.
     *
     * @param  \Codex\Codex\Factory $factory
     * @return \Codex\Codex\Project
     */
    public function setFactory($factory)
    {
        $this->factory = $factory;

        return $this;
    }
}
