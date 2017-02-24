<?php

namespace Tlapnet\Report\Utils;

/**
 * @copyright David Grudl
 */
final class Suggestions
{

	/**
	 * Finds the best suggestion (for 8-bit encoding).
	 *
	 * @param array $items
	 * @param mixed $value
	 * @return NULL|string
	 */
	public static function getSuggestion(array $items, $value)
	{
		$norm = preg_replace($re = '#^(get|set|has|is|add)(?=[A-Z])#', '', $value);
		$best = NULL;
		$min = (strlen($value) / 4 + 1) * 10 + .1;
		foreach (array_unique($items) as $item) {
			if ($item !== $value) {
				$len = levenshtein($item, $value, 10, 11, 10);
				if ($len < $min) {
					$best = $item;
					$min = $len;
					continue;
				}

				$len = levenshtein(preg_replace($re, '', $item), $norm, 10, 11, 10) + 20;
				if ($len < $min) {
					$min = $len;
					$best = $item;
					continue;
				}
			}
		}

		return $best;
	}

	/**
	 * @param string $text
	 * @param mixed $hint
	 * @return string
	 */
	public static function format($text, $hint)
	{
		return $text . ($hint ? ', did you mean "' . $hint . '"?' : '.');
	}

}
