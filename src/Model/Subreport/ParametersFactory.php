<?php

namespace Tlapnet\Report\Model\Subreport;

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
