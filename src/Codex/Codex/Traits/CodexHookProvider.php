<?php
namespace Codex\Codex\Traits;

use Codex\Codex\Document;
use Codex\Codex\Factory;

/**
 * Codex hook provider trait
 *
 * @package   Codex\Codex
 * @author    Codex Project Dev Team
 * @copyright Copyright (c) 2015, Codex Project
 * @license   https://tldrlegal.com/license/mit-license MIT License
 */
trait CodexHookProvider
{
    /**
     * Add a new factory hook.
     *
     * @param  string                                 $hookPoint
     * @param  \Closure|\Codex\Codex\Constracts\Hook  $handler
     * @return void
     */
    protected function addCodexHook($hookPoint, $handler)
    {
        Factory::hook($hookPoint, $handler);
    }

    /**
     * Add a new document filter.
     *
     * @param  string                                  $name
     * @param  \Closure|\Codex\Codex\Contracts\Filter  $handler
     * @return void
     */
    protected function addCodexFilter($name, $handler) {
        Document::filter($name, $handler);
    }
}
