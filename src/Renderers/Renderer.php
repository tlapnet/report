<?php

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Model\Data\Result;

interface Renderer
{

	/**
	 * @param Result $report
	 * @return mixed
	 */
	public function render(Result $report);

}
