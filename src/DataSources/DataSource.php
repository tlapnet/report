<?php

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Model\Subreport\Parameters;
use Tlapnet\Report\Model\Data\Result;

interface DataSource
{

	/**
	 * @param Parameters $parameters
	 * @return Result
	 */
	public function compile(Parameters $parameters);

}
