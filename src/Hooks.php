<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Codex\Codex;

use Codex\Codex\Contracts\Hook;

/**
 * This is the Hooks.
 *
 * @package        Codex\Codex
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class Hooks
{

    /**
     * @var array
     */
    protected static $hooks = [ ];

    # Hooks

    /**
     * Ensure point.
     *
     * @param  string $name
     * @return void
     */
    protected static function ensurePoint($name)
    {
        if ( ! isset(static::$hooks[ $name ]) )
        {
            static::$hooks[ $name ] = [ ];
        }
    }

    /**
     * Register a hook instance.
     *
     * @param  string          $point
     * @param  string|\Closure $handler
     * @return void
     */
    public static function hook($point, $handler)
    {
        if ( ! $handler instanceof \Closure and ! in_array(Hook::class, class_implements($handler), false) )
        {
            throw new \InvalidArgumentException("Failed adding hook. Provided handler for [{$point}] is not valid. Either provider a \\Closure or classpath that impelments \\Codex\\Codex\\Contracts\\Hook");
        }

        static::ensurePoint($point);
        static::$hooks[ $point ][] = $handler;
    }

    /**
     * Run the given hook.
     *
     * @param  string $name
     * @param  array  $params
     * @return void
     */
    public static function run($name, array $params = [ ])
    {
        static::ensurePoint($name);

        foreach ( static::$hooks[ $name ] as $handler )
        {
            if ( $handler instanceof \Closure )
            {
                call_user_func_array($handler, $params);
            }
            elseif ( class_exists($handler) )
            {
                $instance = app()->make($handler);

                call_user_func_array([ $instance, 'handle' ], $params);
            }
        }
    }
}
