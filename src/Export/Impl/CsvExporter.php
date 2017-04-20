<?php

namespace Tlapnet\Report\Export\Impl;

use Tlapnet\Report\Export\AbstractExporter;
use Tlapnet\Report\Result\Resultable;

class CsvExporter extends AbstractExporter
{

	/**
	 * @param Resultable $result
	 * @param array $options
	 * @return CsvExport
	 */
	public function export(Resultable $result, array $options = [])
	{
		$data = $result->getData();

		return new CsvExport($data);
	}

}
