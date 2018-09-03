<?php declare(strict_types = 1);

namespace Tlapnet\Report\Export;

use Tlapnet\Report\Result\Resultable;

interface Exporter
{

	/**
	 * @param mixed[] $options
	 */
	public function export(Resultable $result, array $options = []): Exportable;

}
