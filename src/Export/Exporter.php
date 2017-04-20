<?php

namespace Tlapnet\Report\Export;

use Tlapnet\Report\Result\Resultable;

interface Exporter
{

	/**
	 * @param Resultable $result
	 * @param array $options
	 * @return Exportable
	 */
	public function export(Resultable $result, array $options = []);

}
