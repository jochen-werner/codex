<?php
namespace Codex\Codex\Parsers;

use ParsedownExtra as BaseParsedownExtra;

class ParsedownExtra extends BaseParsedownExtra
{
	/**
	 * Parse fenced code blocks.
	 *
	 * @param array $line
	 * @return array
	 */
	protected function blockFencedCode($line)
	{
		$regex = '/^(['.$line['text'][0].']{3,})[ ]*([\w-]+)?[ ]*$/';

		if (preg_match($regex, $line['text'], $matches)) {
			$element = [
				'name' => 'code',
				'text' => '',
			];

			if (isset($matches[2])) {
				$class = 'prettyprint lang-'.$matches[2];

				$element['attributes'] = ['class' => $class];
			}

			$block = [
				'char'    => $line['text'][0],
				'element' => [
					'name'    => 'pre',
					'handler' => 'element',
					'text'    => $element,
				],
			];

			return $block;
		}
	}

	/**
	 * Parse tables.
	 *
	 * @param array $line
	 * @param array $block
	 * @return array
	 */
	protected function blockTable($line, array $block = null)
	{
		if ( ! isset($block) or isset($block['type']) or isset($block['interrupted'])) {
			return;
		}

		if (strpos($block['element']['text'], '|') !== false and chop($line['text'], ' -:|') === '') {
			$alignments = array();

			$divider = $line['text'];
			$divider = trim($divider);
			$divider = trim($divider, '|');

			$dividerCells = explode('|', $divider);

			foreach ($dividerCells as $dividerCell) {
				$dividerCell = trim($dividerCell);

				if ($dividerCell === '') {
					continue;
				}

				$alignment = null;

				if ($dividerCell[0] === ':') {
					$alignment = 'left';
				}

				if (substr($dividerCell, - 1) === ':') {
					$alignment = $alignment === 'left' ? 'center' : 'right';
				}

				$alignments[] = $alignment;
			}

			$headerElements = array();

			$header = $block['element']['text'];

			$header = trim($header);
			$header = trim($header, '|');

			$headerCells = explode('|', $header);

			foreach ($headerCells as $index => $headerCell)
			{
				$headerCell = trim($headerCell);

				$headerElement = [
					'name'    => 'th',
					'text'    => $headerCell,
					'handler' => 'line',
				];

				if (isset($alignments[$index])) {
					$alignment = $alignments[$index];

					$headerElement['attributes'] = [
						'style' => 'text-align: '.$alignment.';',
					];
				}

				$headerElements[] = $headerElement;
			}

			$block = [
				'alignments'     => $alignments,
				'identified'     => true,
				'element'        => [
					'name'       => 'table',
					'handler'    => 'elements',
					'attributes' => [
						'class'  => 'table table-striped table-bordered'
					],
				],
			];

			$block['element']['text'][] = [
				'name'    => 'thead',
				'handler' => 'elements',
			];

			$block['element']['text'][] = [
				'name'    => 'tbody',
				'handler' => 'elements',
				'text'    => array(),
			];

			$block['element']['text'][0]['text'][] = [
				'name'    => 'tr',
				'handler' => 'elements',
				'text'    => $headerElements,
			];

			return $block;
		}
	}
}