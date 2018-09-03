<?php declare(strict_types = 1);

namespace Tlapnet\Report\Export\Impl;

use Tlapnet\Report\Export\AbstractExporter;
use Tlapnet\Report\Export\Exportable;
use Tlapnet\Report\Result\Resultable;

class CsvExporter extends AbstractExporter
{

	/**
	 * @param mixed[] $options
	 * @return CsvExport
	 */
	public function export(Resultable $result, array $options = []): Exportable
	{
		$data = $result->getData();

		return new CsvExport($data);
	}

}
