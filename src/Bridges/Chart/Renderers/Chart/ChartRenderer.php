<?php

namespace Tlapnet\Report\Bridges\Chart\Renderers\Chart;

use Tlapnet\Chart\Chart;
use Tlapnet\Chart\Segment\Segment;
use Tlapnet\Chart\Serie\Serie;
use Tlapnet\Report\Bridges\Chart\Renderers\AbstractChartRenderer;
use Tlapnet\Report\Heap\Heap;

class ChartRenderer extends AbstractChartRenderer
{

	/**
	 * @param Heap $heap
	 * @return mixed
	 */
	public function render(Heap $heap)
	{
		$chart = new Chart();

		if ($this->valueSuffix) {
			$chart->setValueSuffix($this->valueSuffix);
		}

		// Create series
		/** @var Serie[] $series */
		$series = [];
		foreach ($this->getSeries() as $serie) {
			$series[$serie->id] = new Serie($serie->type, $serie->title, $serie->color);
			$chart->addSerie($series[$serie->id]);
		}

		// Filter data
		$filtered = $this->doFilterData($this->getSeriesGroups(), $heap->getData());

		$xKey = $this->getSegment('x');
		$yKey = $this->getSegment('y');

		foreach ($filtered as $serieName => $items) {
			$serie = $series[$serieName];
			foreach ($items as $item) {
				$serie->addSegment(new Segment($item[$xKey], $item[$yKey]));
			}
		}

		return $chart;
	}

}
