<?php

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Result\Result;

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
