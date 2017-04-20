<?php

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Exceptions\Logic\NotImplementedException;
use Tlapnet\Report\Parameters\Parameters;

class RestDataSource implements DataSource
{

	/**
	 * @param Parameters $parameters
	 * @return void
	 */
	public function compile(Parameters $parameters)
	{
		throw new NotImplementedException();
	}

}
