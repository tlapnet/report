<?php

namespace Tlapnet\Report\Model\Export\Impl;

use Tlapnet\Report\Model\Export\AbstractExporter;
use Tlapnet\Report\Model\Result\Resultable;

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
