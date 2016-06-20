<?php

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Model\Data\Result;
use Tlapnet\Report\Model\Parameters\Parameters;

final class DevNullDataSource implements DataSource
{

	/**
	 * @param Parameters $parameters
	 * @return Result
	 */
	public function compile(Parameters $parameters)
	{
		return new Result(NULL);
	}

}
