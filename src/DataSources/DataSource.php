<?php

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Model\Subreport\ParameterList;
use Tlapnet\Report\Model\Data\Result;

interface DataSource
{

	/**
	 * @param ParameterList $parameters
	 * @return Result
	 */
	public function compile(ParameterList $parameters);

}
