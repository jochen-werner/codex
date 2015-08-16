<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Codex\Codex\Menus;

/**
 * This is the Node.
 *
 * @package        Codex\Codex
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class Node extends \Tree\Node\Node
{
    /**
     * @var mixed|null
     */
    protected $id;

    /**
     * @var \Codex\Codex\Menu
     */
    protected $menu;

    /**
     * @var array
     */
    protected $meta;

    protected $attributes;

    /**
     * @param mixed|null        $id
     * @param \Codex\Codex\Menu $menu
     * @param null              $value
     * @param array             $children
     */
    public function __construct($id, Menu $menu, $value = null, array $children = [ ])
    {
        parent::__construct($value, $children);

        $this->id         = $id;
        $this->menu       = $menu;
        $this->meta       = [ ];
        $this->attributes = [ ];
    }

    public function hasChildren()
    {
        return count($this->getChildren()) > 0;
    }


    public function attribute($key, $default = null)
    {
        return array_get($this->attributes, $key, $default);
    }

    public function meta($key, $default = null)
    {
        return array_get($this->attributes, $key, $default);
    }

    public function setAttribute($key, $value = null)
    {
        if ( is_array($key) && is_null($value) )
        {
            $this->attributes = $key;
        }
        else
        {
            array_set($this->attributes, $key, $value);
        }

        return $this;
    }


    public function setMeta($key, $value = null)
    {
        if ( is_array($key) && is_null($value) )
        {
            $this->meta = $key;
        }
        else
        {
            array_set($this->meta, $key, $value);
        }

        return $this;
    }


    /**
     * get meta value
     *
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * get attributes value
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get the value of id
     *
     * @return mixed|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id
     *
     * @param mixed|null $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

}
