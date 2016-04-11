<?php

namespace Tlapnet\Report\Bridges\Chart\Renderers\Pie;

use Tlapnet\Chart\PieChart;
use Tlapnet\Chart\Segment\PieSegment;
use Tlapnet\Report\Bridges\Chart\Renderers\AbstractChartRenderer;
use Tlapnet\Report\Model\Data\Result;

class PieChartRenderer extends AbstractChartRenderer
{

	/**
	 * @param Result $report
	 * @return mixed
	 */
	public function render(Result $report)
	{
		/** @var PieChart $chart */
		$chart = $this->createChart(new PieChart());

		$titleKey = $this->getSegment('title');
		$valueKey = $this->getSegment('value');

		foreach ($report->getData() as $item) {
			$chart->addSegment(new PieSegment($item[$titleKey], $item[$valueKey]));
		}

		return $chart;
	}

}
