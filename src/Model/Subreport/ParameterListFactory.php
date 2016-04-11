<?php

namespace Tlapnet\Report\Model\Subreport;

class ParameterListFactory
{

	/**
	 * @param array $parameters
	 * @return ParameterList
	 */
	public static function create(array $parameters)
	{
		$pl = new ParameterList();

		return $pl;
	}

}
