<?php

namespace Tlapnet\Report\Bridges\Chart\Renderers\Chart;

use Tlapnet\Chart\DateChart;
use Tlapnet\Chart\Segment\PieSegment;
use Tlapnet\Report\Bridges\Chart\Renderers\AbstractChartRenderer;
use Tlapnet\Report\Heap\Heap;

class CategoryChartRenderer extends AbstractChartRenderer
{

	/**
	 * @param Heap $heap
	 * @return mixed
	 */
	public function render(Heap $heap)
	{
		$chart = new DateChart();

		if ($this->valueSuffix) {
			$chart->setValueSuffix($this->valueSuffix);
		}

		foreach ($heap->getData() as $item) {
			$titleKey = $this->getMapping('title');
			$valueKey = $this->getMapping('value');
			$chart->addSegment(new PieSegment($item[$titleKey, $item->$valueKey));
		}

		return $chart;
	}

}
