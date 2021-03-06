<?php
namespace Codex\Codex\Filters;

use Codex\Codex\Contracts\Filter;
use Codex\Codex\Document;
use Codex\Codex\Parsers\ParsedownExtra;

/**
 * Parsedown filter
 *
 * @package   Codex\Codex
 * @author    Codex Project Dev Team
 * @copyright Copyright (c) 2015, Codex Project
 * @license   https://tldrlegal.com/license/mit-license MIT License
 */
class ParsedownFilter implements Filter
{
    protected $parsedown;

    /**
     * Create a new ParsedownFilter instance.
     *
     * @param  ParsedownExtra  $parsedown
     * @return void
     */
    public function __construct(ParsedownExtra $parsedown)
    {
        $this->parsedown = $parsedown;
    }

    /**
     * Handle the filter.
     *
     * @param  \Codex\Codex\Document  $document
     * @param  array                  $config
     * @return void
     */
    public function handle(Document $document, array $config)
    {
        $document->setContent($this->parsedown->text($document->getContent()));
    }
}
