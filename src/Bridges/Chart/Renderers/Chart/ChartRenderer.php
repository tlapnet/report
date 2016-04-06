<?php

namespace Tlapnet\Report\Bridges\Chart\Renderers\Chart;

use Tlapnet\Chart\Chart;
use Tlapnet\Chart\Segment\Segment;
use Tlapnet\Chart\Serie\Serie;
use Tlapnet\Report\Bridges\Chart\Renderers\SeriesChartRenderer;
use Tlapnet\Report\Heap\Heap;

class ChartRenderer extends SeriesChartRenderer
{

	/**
	 * @param object $serie
	 * @return Serie
	 */
	protected function createSerie($serie)
	{
		return new Serie($serie->type, $serie->title, $serie->color);
	}

	/**
	 * RENDERERING *************************************************************
	 */

	/**
	 * @param Heap $heap
	 * @return mixed
	 */
	public function render(Heap $heap)
	{
		/** @var Chart $chart */
		$chart = $this->createChart(new Chart());

		// Create series
		$series = $this->doPrepareSeries($this->getSeries());

		// Filter data
		$filtered = $this->doFilterData($this->getSeriesBys(), $heap->getData());

		$xKey = $this->getSegment('x');
		$yKey = $this->getSegment('y');

		foreach ($filtered as $serieName => $items) {
			/** @var Serie $serie */
			$serie = $series[$serieName];
			foreach ($items as $item) {
				$serie->addSegment(new Segment($item[$xKey], $item[$yKey]));
			}
		}

		// Add series to chart
		$this->doAddSeries($chart, $series);

		return $chart;
	}

}
