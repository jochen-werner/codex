<?php
namespace Codex\Codex\Contracts;

/**
 * Filter contract
 *
 * @package   Codex\Codex
 * @author    Codex Project Dev Team
 * @copyright Copyright (c) 2015, Codex Project
 * @license   https://tldrlegal.com/license/mit-license MIT License
 */
interface Filter
{
    public function handle(Document $document, array $config);
}
