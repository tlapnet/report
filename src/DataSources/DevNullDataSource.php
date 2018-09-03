<?php declare(strict_types = 1);

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Result\Result;
use Tlapnet\Report\Result\Resultable;

final class DevNullDataSource implements DataSource
{

	/**
	 * @return Result
	 */
	public function compile(Parameters $parameters): Resultable
	{
		return new Result([]);
	}

}
