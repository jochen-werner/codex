<?php
namespace Codex\Codex\Filters;

use Codex\Codex\Document;
use Codex\Codex\Contracts\Filter;
use Symfony\Component\Yaml\Yaml;

/**
 * Frontmatter filter
 *
 * @package   Codex\Codex
 * @author    Codex Project Dev Team
 * @copyright Copyright (c) 2015, Codex Project
 * @license   https://tldrlegal.com/license/mit-license MIT License
 */
class FrontMatterFilter implements Filter
{
    /**
     * Handle the filter.
     *
     * @param \Codex\Codex\Document $document
     * @param array $config
     * @return void
     */
    public function handle(Document $document, array $config)
    {
        $content = $document->getContent();

        $pattern = '/<!---([\w\W]*?)-->/';
        if (preg_match($pattern, $content, $matches) === 1) {
        // not really required when using html doc tags. But in case it's frontmatter, it should be removed
            $content    = preg_replace($pattern, '', $content);
            $attributes = array_merge_recursive($document->getAttributes(), Yaml::parse($matches[1]));

            $document->setAttributes($attributes);
        }

        $document->setContent($content);
    }
}
