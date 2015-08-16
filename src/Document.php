<?php
namespace Codex\Codex;

use Codex\Codex\Contracts\Factory;
use Codex\Codex\Traits\Hookable;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Filesystem\Filesystem;

/**
 * Document class.
 *
 * @package   Codex\Codex
 * @author    Codex Project Dev Team
 * @copyright Copyright (c) 2015, Codex Project
 * @license   https://tldrlegal.com/license/mit-license MIT License
 */
class Document
{
    use Hookable;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var string
     */
    protected $content;

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
    protected $path;

    /**
     * @var \Codex\Codex\Project
     */
    protected $project;

    /**
     * The pathname is the path given to Project->getDocument. It's a relative path
     *
     * @var string
     */
    protected $pathName;

    /**
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * @param \Codex\Codex\Factory                        $factory
     * @param \Illuminate\Contracts\Filesystem\Filesystem $files
     * @param \Codex\Codex\Project                        $project
     * @param \Illuminate\Contracts\Container\Container   $container
     * @param                                             $path
     * @param                                             $pathName
     */
    public function __construct(Factory $factory, Filesystem $files, Project $project, Container $container, $path, $pathName)
    {
        $this->container = $container;
        $this->factory   = $factory;
        $this->project   = $project;
        $this->files     = $files;
        $this->path      = $path;
        $this->pathName  = $pathName;

        $this->runHook('document:ready', [ $this ]);

        $this->attributes = $factory->config('default_document_attributes');
        $this->content    = $this->files->get($this->path);

        $this->runHook('document:done', [ $this ]);
    }


    /**
     * Render the document.
     *
     * This will run all document:render hooks and then return the
     * output. Should be called within a view.
     *
     * @return string
     */
    public function render()
    {
        $this->runHook('document:render', [ $this ]);

        $fsettings = $this->project->config('filters_settings');
        $filters   = Extensions::getFilters($this->getProject()->config('filters'));

        if (count($filters) > 0) {
            foreach ($filters as $name => $filter) {
                if ($filter instanceof \Closure) {
                    call_user_func_array($filter, [ $this, isset($fsettings[ $name ]) ? $fsettings[ $name ] : [ ] ]);
                    } else {
                    $instance = app()->make($filter);
                    call_user_func_array([ $instance, 'handle' ], [ $this, isset($fsettings[ $name ]) ? $fsettings[ $name ] : [ ] ]);
                    }
            }
        }

        return $this->content;
    }


    /**
     * Get the given attribute.
     *
     * @param  string $key
     * @return array
     */
    public function attr($key = null, $default = null)
    {
        return is_null($key) ? $this->attributes : array_get($this->attributes, $key, $default);
    }

    /**
     * Get the url to this document
     *
     * @return string
     */
    public function url()
    {
        return $this->factory->url($this->project, $this->project->getRef(), $this->pathName);
    }

    /**
     * getBreadcrumb
     */
    public function getBreadcrumb()
    {
        return $this->project->getDocumentsMenu()->getBreadcrumbToHref($this->url());
    }

    /**
     * get the path value.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * get the content of the document.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the content value of the document.
     *
     * @param  string $content
     * @return Document
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get all document attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set the document attributes.
     *
     * @param  array $attributes
     * @return Document
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * mergeAttributes
     *
     * @param array $attributes
     * @return $this
     */
    public function mergeAttributes(array $attributes)
    {
        $this->attributes = array_replace_recursive($this->attributes, $attributes);
        return $this;
    }

    /**
     * Get the document's project.
     *
     * @return \Codex\Codex\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Get all files for the given project.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set the project files value.
     *
     * @param  Filesystem $files
     * @return Document
     */
    public function setFiles($files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * Set the path value.
     *
     * @param  string $path
     * @return Document
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }
}
