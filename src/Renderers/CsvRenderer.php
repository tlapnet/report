<?php

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Exceptions\Logic\NotImplementedException;
use Tlapnet\Report\Model\Data\Result;

class CsvRenderer implements Renderer
{

	/**
	 * @param Result $report
	 * @return string
	 */
	public function render(Result $report)
	{
		throw new NotImplementedException();
	}

}
