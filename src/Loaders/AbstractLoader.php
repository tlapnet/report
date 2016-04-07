<?php

namespace Tlapnet\Report\Loaders;

use Tlapnet\Report\DataSources\DataSource;
use Tlapnet\Report\ReportBox\ParameterList;
use Tlapnet\Report\Renderers\Renderer;

abstract class AbstractLoader implements Loader
{

	protected function createHeapBox($uid, ParameterList $parameters, DataSource $dataSource, Renderer $renderer)
	{
		$stop();
	}

}