<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Codex\Codex\Providers;

use Caffeinated\Beverage\ConsoleServiceProvider as BaseConsoleServiceProvider;

/**
 * This is the ConsoleServiceProvider.
 *
 * @package        Codex\Codex
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class ConsoleServiceProvider extends BaseConsoleServiceProvider
{
    protected $namespace = 'Codex\\Codex\\Console';

    protected $prefix = 'codex.commands.';

    protected $commands = [
        'make' => 'CodexMake',
        'list' => 'CodexList'
    ];
}
