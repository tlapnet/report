<?php

namespace Tlapnet\Report\Utils;

class Suggestions
{


    /**
     * Finds the best suggestion (for 8-bit encoding).
     *
     * @copyright David Grudl
     * @return string|NULL
     */
    public static function getSuggestion(array $items, $value)
    {
        $norm = preg_replace($re = '#^(get|set|has|is|add)(?=[A-Z])#', '', $value);
        $best = NULL;
        $min = (strlen($value) / 4 + 1) * 10 + .1;
        foreach (array_unique($items) as $item) {
            if ($item !== $value && (
                    ($len = levenshtein($item, $value, 10, 11, 10)) < $min
                    || ($len = levenshtein(preg_replace($re, '', $item), $norm, 10, 11, 10) + 20) < $min
                )
            ) {
                $min = $len;
                $best = $item;
            }
        }

        return $best;
    }

}
