<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Codex\Codex\Console;

/**
 * This is the CodexMakeCommand.
 *
 * @package        Codex\Codex
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class CodexListCommand extends BaseCommand
{
    protected $signature = 'codex:list';

    protected $description = 'List all codex projects';

    public function handle()
    {
        $headers = ['Name', 'Ref'];
        $rows = [];
        foreach ($this->factory->all() as $name => $config) {
            $rows[] = [$name, $this->factory->make($name)->getRef()];
        }
        $this->table($headers, $rows);
    }
}
