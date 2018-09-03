<?php declare(strict_types = 1);

namespace Tlapnet\Report\Utils;

final class Arrays
{

	/**
	 * @param mixed[] $array
	 * @return mixed
	 */
	public static function pop(array $array)
	{
		return array_pop($array);
	}

	/**
	 * @param mixed[] $array
	 * @return mixed
	 */
	public static function shift(array $array)
	{
		return array_shift($array);
	}

}
