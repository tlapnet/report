<?php declare(strict_types = 1);

namespace Tlapnet\Report\Utils;

final class Suggestions
{

	/**
	 * Finds the best suggestion (for 8-bit encoding).
	 *
	 * @param mixed[] $items
	 * @param mixed $value
	 */
	public static function getSuggestion(array $items, $value): ?string
	{
		$norm = preg_replace($re = '#^(get|set|has|is|add)(?=[A-Z])#', '', $value);
		$best = null;
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

	public static function format(string $text, ?string $hint = null): string
	{
		return $text . ($hint !== null ? ', did you mean "' . $hint . '"?' : '.');
	}

}
