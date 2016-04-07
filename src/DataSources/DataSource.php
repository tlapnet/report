<?php

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Model\Box\ParameterList;
use Tlapnet\Report\Model\Data\Report;

interface DataSource
{

	/**
	 * @param ParameterList $parameters
	 * @return Report
	 */
	public function compile(ParameterList $parameters);

}
