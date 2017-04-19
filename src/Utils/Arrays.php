<?php

namespace Tlapnet\Report\Utils;

final class Arrays
{

	/**
	 * @param array $array
	 * @return mixed
	 */
	public static function pop($array)
	{
		return array_pop($array);
	}

	/**
	 * @param array $array
	 * @return mixed
	 */
	public static function shift($array)
	{
		return array_shift($array);
	}

}
