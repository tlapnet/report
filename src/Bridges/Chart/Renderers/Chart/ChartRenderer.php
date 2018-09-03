<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Chart\Renderers\Chart;

use Tlapnet\Chart\AbstractChart;
use Tlapnet\Chart\Chart;
use Tlapnet\Chart\Segment\Segment;
use Tlapnet\Chart\Serie\AbstractSerie;
use Tlapnet\Chart\Serie\Serie;
use Tlapnet\Report\Bridges\Chart\Renderers\SeriesChartRenderer;
use Tlapnet\Report\Result\Result;

class ChartRenderer extends SeriesChartRenderer
{

	/**
	 * @return Serie
	 */
	protected function createSerie(object $serie): AbstractSerie
	{
		return new Serie($serie->type, $serie->title, $serie->color);
	}

	/**
	 * @return Chart
	 */
	public function render(Result $result): AbstractChart
	{
		/** @var Chart $chart */
		$chart = $this->createChart(new Chart());

		// Create series
		$series = $this->doPrepareSeries($this->getSeries());

		// Filter data
		$filtered = $this->doFilterData($this->getSeriesBys(), $result->getData());

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
