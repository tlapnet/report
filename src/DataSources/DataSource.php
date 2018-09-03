<?php declare(strict_types = 1);

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Result\Resultable;

interface DataSource
{

	public function compile(Parameters $parameters): Resultable;

}
