<?php

namespace Tlapnet\Report\Bridges\Tracy\Export;

use Tlapnet\Report\Model\Export\Exporter;
use Tlapnet\Report\Model\Result\Resultable;
use Tracy\Debugger;

class DebugExporter implements Exporter
{

	/**
	 * @param Resultable $result
	 * @param array $options
	 * @return string
	 */
	public function export(Resultable $result, array $options = [])
	{
		return Debugger::dump($result, TRUE);
	}

}
