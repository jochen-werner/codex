<?php
namespace Codex\Codex;

use Codex\Codex\Contracts\Hook;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Traits\Macroable;
use Symfony\Component\Finder\Finder;

/**
 * Factory class.
 *
 * @package   Codex\Codex
 * @author    Codex Project Dev Team
 * @copyright Copyright (c) 2015, Codex Project
 * @license   https://tldrlegal.com/license/mit-license MIT License
 */
class Factory
{
    use Macroable;

    /**
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $files;

    /**
     * @var array
     */
    protected static $hooks = [];

    /**
     * @var Project[]
     */
    protected $projects;

    /**
     * Path to the directory containing all docs
     *
     * @var string
     */
    protected $rootDir;
    
    /**
     * @param  \Illuminate\Contracts\Filesystem\Filesystem  $files
     * @param  \Illuminate\Contracts\Config\Repository      $config
     * @param  \Illuminate\Contracts\Cache\Repository       $cache
     * @return void
     */
    public function __construct(Filesystem $files, Repository $config, Cache $cache)
    {
        $this->cache   = $cache;
        $this->config  = $config->get('codex');
        $this->files   = $files;
        $this->rootDir = config('codex.root_dir');

        // 'factory:ready' is called after parameters have been set as class properties.
        static::run('factory:ready', [$this]);

        if (!isset($this->projects)) {
            $this->findAll();
        }

        // 'factory:done' called after all factory operations have completed.
        static::run('factory:done', [$this]);
    }

    /**
     * Find and return all projects.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function findAll()
    {
        $finder         = new Finder();
        $projects       = $finder->in($this->rootDir)->files()->name('config.php')->depth('<= 1')->followLinks();
        $this->projects = [];
        
        foreach ($projects as $project) {
            $name                  = last(explode(DIRECTORY_SEPARATOR, $project->getPath()));
            $config                = with(new \Illuminate\Filesystem\Filesystem)->getRequire($project->getRealPath());
            $this->projects[$name] = array_replace_recursive($this->config('default_project_config'), $config);
        }
    }

    /**
     * Make a new project object, will represent a project based on directory name.
     *
     * @param  string  $name
     * @return \Codex\Codex\Project
     */
    public function make($name)
    {
        if (!$this->has($name)) {
            throw new \InvalidArgumentException("Project [$name] could not be found in [{$this->rootDir}]");
        }

        $project = new Project($this, $this->files, $name, $this->projects[$name]);

        static::run('project:make', [$this, $project]);

        return $project;
    }

    /**
     * Check if the given project exists.
     *
     * @param  string  $name
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->projects);
    }

    /**
     * Generate a URL to a project's default page and version.
     *
     * @param  Project      $project
     * @param  null|string  $ref
     * @param  null|string  $doc
     * @return string
     */
    public function url($project = null, $ref = null, $doc = null)
    {
        $uri = $this->config('base_route');

        if (!is_null($project)) {
            if ($project instanceof Project) {
                $uri .= '/'.$project->getName();
            } else {
                $uri .= '/'.$project;
            }

            if (!is_null($ref)) {
                $uri .= '/'.$ref;
                
                if (!is_null($doc)) {
                    $uri .= '/'.$doc;
                }
            }
        }

        return url($uri);
    }

    /**
     * Return all found projects.
     *
     * @return Project[]
     */
    public function all()
    {
        return $this->projects;
    }

    /**
     * Get root directory.
     *
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * Retreive codex config using a dot notated key.
     *
     * @param  null|string  $key
     * @param  null|string  $default
     * @return array|mixed
     */
    public function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->config;
        }

        return array_get($this->config, $key, $default);
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
     * Set the config.
     *
     * @param  array  $config
     * @return void
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get files.
     *
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set files.
     *
     * @param  mixed  $files
     * @return Factory
     */
    public function setFiles($files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * Get cache.
     *
     * @return Cache
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * Set cache.
     *
     * @param  \Illuminate\Cache\CacheManager  $cache
     * @return Factory
     */
    public function setCache($cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * Ensure point.
     *
     * @param  string  $name
     * @return void
     */
    protected static function ensurePoint($name)
    {
        if (!isset(static::$hooks[$name])) {
            static::$hooks[$name] = [];
        }
    }

    /**
     * Register a hook instance.
     *
     * @param  string           $point
     * @param  string|\Closure  $handler
     * @return void
     */
    public static function hook($point, $handler)
    {
        if (!$handler instanceof \Closure and !in_array(Hook::class, class_implements($handler), false)) {
            throw new \InvalidArgumentException("Failed adding hook. Provided handler for [{$point}] is not valid. Either provider a \\Closure or classpath that impelments \\Codex\\Codex\\Contracts\\Hook");
        }

        static::ensurePoint($point);
        static::$hooks[$point][] = $handler;
    }

    /**
     * Run the given hook.
     *
     * @param  string  $name
     * @param  array   $params
     * @return void
     */
    public static function run($name, array $params = [])
    {
        static::ensurePoint($name);

        foreach (static::$hooks[$name] as $handler) {
            if ($handler instanceof \Closure) {
                call_user_func_array($handler, $params);
            } elseif (class_exists($handler)) {
                $instance = app()->make($handler);

                call_user_func_array([$instance, 'handle'], $params);
            }
        }
    }
}
