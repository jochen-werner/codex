<?php
namespace Codex\Codex;

use Caffeinated\Beverage\Path;
use Caffeinated\Beverage\Str;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Traits\Macroable;
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
    use Macroable;

    const SHOW_MASTER_BRANCH                        = 0;
    const SHOW_LAST_VERSION                         = 1;
    const SHOW_LAST_VERSION_OTHERWISE_MASTER_BRANCH = 2;
    const SHOW_CUSTOM                               = 3;

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
     * Create a new Project instance.
     *
     * @param  \Codex\Codex\Factory  $factory
     * @param  Filesystem            $files
     * @param  string                $name
     * @param  Project               $config
     * @return void
     */
    public function __construct(Factory $factory, Filesystem $files, $name, $config)
    {
        $this->factory = $factory;
        $this->files   = $files;
        $this->name    = $name;
        $this->config  = $config;
        $this->path    = $path = Path::join($factory->getRootDir(), $name);

        Factory::run('project:ready', [$this]);

        $directories = $this->files->directories($this->path);
        $branches    = [];
        $this->refs  = [];

        $this->versions = array_filter(array_map(function ($dirPath) use ($path, $name, &$branches) {
            $version      = Str::create(Str::ensureLeft($dirPath, '/'))->removeLeft($path)->removeLeft(DIRECTORY_SEPARATOR);
            $version      = (string)$version->removeLeft($name.'/');
            $this->refs[] = $version;

            try {
                return new version($version);
            } catch (\RuntimeException $e) {
                $branches[] = $version;
            }
        }, $directories), 'is_object');

        $this->branches = $branches;

        // check which version/branch to show by default
        $defaultRef = count($this->versions) > 0 ? head($this->versions) : head($branches);

        switch ($this->config['default']) {
            case Project::SHOW_LAST_VERSION:
                usort($this->versions, function (version $v1, version $v2) {
                    return version::gt($v1, $v2) ? -1 : 1;
                });

                $defaultRef = head($this->versions);
                break;
            case Project::SHOW_LAST_VERSION_OTHERWISE_MASTER_BRANCH:
                if (count($this->versions) > 0) {
                    usort($this->versions, function (version $v1, version $v2) {
                        return version::gt($v1, $v2) ? -1 : 1;
                    });
                }

                $defaultRef = count($this->versions) > 0 ? head($this->versions) : head($branches);
                break;
            case Project::SHOW_MASTER_BRANCH:
                $defaultRef = 'master';
                break;
            case Project::SHOW_CUSTOM:
                $defaultRef = $this->config['custom'];
                break;
        }

        $this->ref = $this->defaultRef = (string)$defaultRef;
    }

    public function url($doc = 'index', $ref = null)
    {
        return $this->factory->url($this, $ref, $doc);
    }

    /**
     * Retreive this projects config using a dot notated key
     *
     * @param  null|string  $key
     * @param  null|mixed   $default
     * @return callable
     */
    public function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->config;
        }

        return array_get($this->config, $key, $default);
    }

    /**
     * Set config.
     *
     * @param  array  $config
     * @return void
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * Set the ref (version/branch) you want to use. getDocument will be getting stuff using the ref
     *
     * @param  string  $name
     * @return \Codex\Codex\Project
     */
    public function setRef($name)
    {
        $this->ref = $name;

        return $this;
    }

    /**
     * Get a document using the provided path.
     *
     * It will retreive it from the current $ref or otherwise the $defaultRef folder
     *
     * @param  string  $path
     * @return \Codex\Codex\Document
     */
    public function getDocument($path = '')
    {
        if (strlen($path) === 0) {
            $path = 'index';
        }

        $path = Path::join($this->path, $this->ref, $path.'.md');

        $document = new Document($this->factory, $this, $this->files, $path);

        Factory::run('project:document', [$document]);

        return $document;
    }

    /**
     * Get the projects menu file.
     *
     * @return \Codex\Codex\Menu
     */
    public function getMenu()
    {
        $path = Path::join($this->getPath(), $this->ref, 'menu.yml');

        return new Menu($this, $this->files, $this->factory->getCache(), $path);
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

        usort($versions, function (version $v1, version $v2) {
            return version::gt($v1, $v2) ? -1 : 1;
        });

        $versions = array_map(function (version $v) {
            return $v->getVersion();
        }, $versions);

        return array_merge($this->branches, $versions);
    }

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
     * @param  \Illuminate\Contracts\Filesystem\Filesystem  $files
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
     * @param  string  $path
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
     * @param  \Codex\Codex\Factory  $factory
     * @return \Codex\Codex\Project
     */
    public function setFactory($factory)
    {
        $this->factory = $factory;

        return $this;
    }
}
