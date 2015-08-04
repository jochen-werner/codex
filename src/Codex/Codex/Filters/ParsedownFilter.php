<?php
namespace Codex\Codex\Filters;

use Codex\Codex\Document;
use Codex\Codex\Contracts\Filter;
use Codex\Parsers\ParsedownExtra;

class ParsedownFilter implements Filter
{
    protected $parsedown;

    /**
     * Create a new ParsedownFilter instance.
     *
     * @param \Codex\Parsers\ParsedownExtra $parsedown
     * @return void
     */
    public function __construct(ParsedownExtra $parsedown)
    {
        $this->parsedown = $parsedown;
    }

    /**
     * Handle the filter.
     *
     * @param \Codex\Codex\Document $document
     * @param array $config
     * @return void
     */
    public function handle(Document $document, array $config)
    {
        $document->setContent($this->parsedown->text($document->getContent()));
    }
}
