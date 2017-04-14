<?php

namespace Tlapnet\Report\Model\Export;

use Tlapnet\Report\Model\Result\Resultable;

interface Exporter
{

	/**
	 * @param Resultable $result
	 * @param array $options
	 * @return Exportable
	 */
	public function export(Resultable $result, array $options = []);

}
