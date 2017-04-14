<?php

namespace Tlapnet\Report\Model\Export\Impl;

use Tlapnet\Report\Model\Export\AbstractExporter;
use Tlapnet\Report\Model\Result\Resultable;

class JsonExporter extends AbstractExporter
{

	/**
	 * @param Resultable $result
	 * @param array $options
	 * @return JsonExport
	 */
	public function export(Resultable $result, array $options = [])
	{
		$data = $result->getData();

		return new JsonExport($data);
	}

}
