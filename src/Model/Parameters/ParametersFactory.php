<?php

namespace Tlapnet\Report\Model\Parameters;

class ParametersFactory
{

	/**
	 * @param array $parameters
	 * @return Parameters
	 */
	public static function create(array $parameters)
	{
		$p = new Parameters();

		return $p;
	}

}
