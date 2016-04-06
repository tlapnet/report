<?php

namespace Tlapnet\Report\Bridges\Chart\Renderers\Pie;

use Tlapnet\Chart\PieChart;
use Tlapnet\Chart\Segment\PieSegment;
use Tlapnet\Report\Bridges\Chart\Renderers\AbstractChartRenderer;
use Tlapnet\Report\Heap\Heap;

class PieChartRenderer extends AbstractChartRenderer
{

	/**
	 * @param Heap $heap
	 * @return mixed
	 */
	public function render(Heap $heap)
	{
		$chart = new PieChart();

		if ($this->valueSuffix) {
			$chart->setValueSuffix($this->valueSuffix);
		}

		$titleKey = $this->getSegment('title');
		$valueKey = $this->getSegment('value');
		
		foreach ($heap->getData() as $item) {
			$chart->addSegment(new PieSegment($item[$titleKey], $item[$valueKey]));
		}

		return $chart;
	}

}
