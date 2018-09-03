<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Tracy\Export;

use Tlapnet\Report\Export\AbstractExporter;
use Tlapnet\Report\Export\Exportable;
use Tlapnet\Report\Result\Resultable;

class DebugExporter extends AbstractExporter
{

	/**
	 * @param mixed[] $options
	 * @return DebugExport
	 */
	public function export(Resultable $result, array $options = []): Exportable
	{
		return new DebugExport($result);
	}

}
