<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Codex\Codex\Console;

use Caffeinated\Beverage\Command;
use Codex\Codex\Contracts\Factory;

/**
 * This is the CodexMakeCommand.
 *
 * @package        Codex\Codex
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
abstract class BaseCommand extends Command
{
    /**
     * @var \Codex\Codex\Factory
     */
    protected $factory;

    public function __construct(Factory $factory)
    {
        parent::__construct();
        $this->factory = $factory;
    }

}
