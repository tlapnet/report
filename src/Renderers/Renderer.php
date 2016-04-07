<?php

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Model\Data\Report;

interface Renderer
{

	/**
	 * @param Report $report
	 * @return mixed
	 */
	public function render(Report $report);

}
