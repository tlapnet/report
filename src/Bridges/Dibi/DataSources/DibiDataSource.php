<?php

namespace Tlapnet\Report\Bridges\Dibi\DataSources;

use Tlapnet\Report\DataSources\AbstractDatabaseDataSource;
use Tlapnet\Report\Report\Report;
use Tlapnet\Report\ReportBox\ParameterList;

class DibiDataSource extends AbstractDatabaseDataSource
{

	/**
	 * @param ParameterList $parameters
	 * @return Report
	 */
	public function compile(ParameterList $parameters)
	{
		$stop();
	}

}
