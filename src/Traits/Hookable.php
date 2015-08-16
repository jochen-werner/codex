<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Codex\Codex\Traits;

use Codex\Codex\Extensions;
use Illuminate\Support\Traits\Macroable;

/**
 * This is the Hookable.
 *
 * @package        Codex\Codex
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
trait Hookable
{
    use Macroable;

    protected function runHook($name, array $params = [ ])
    {
        Extensions::runHook($name, $params);
    }
}
