<?php
namespace Codex\Codex\Contracts;

/**
 * This is the Filter.
 *
 * @package        Codex\Codex
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
interface Filter
{
    public function handle(Document $document, array $config);
}
