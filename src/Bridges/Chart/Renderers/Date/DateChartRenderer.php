<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Chart\Renderers\Date;

use Tlapnet\Chart\AbstractChart;
use Tlapnet\Chart\DateChart;
use Tlapnet\Chart\Segment\DateSegment;
use Tlapnet\Chart\Serie\AbstractSerie;
use Tlapnet\Chart\Serie\DateSerie;
use Tlapnet\Report\Bridges\Chart\Renderers\SeriesChartRenderer;
use Tlapnet\Report\Result\Result;
use Tlapnet\Report\Utils\DateTime;

class DateChartRenderer extends SeriesChartRenderer
{

	/** @var bool */
	protected $useTimePrecision = false;

	public function setUseTimePrecision(bool $use = true): void
	{
		$this->useTimePrecision = $use;
	}

	/**
	 * @return DateSerie
	 */
	protected function createSerie(object $serie): AbstractSerie
	{
		return new DateSerie($serie->type, $serie->title, $serie->color);
	}

	/**
	 * @return DateChart
	 */
	public function render(Result $result): AbstractChart
	{
		/** @var DateChart $chart */
		$chart = $this->createChart(new DateChart());

		if ($this->useTimePrecision) {
			$this->setUseTimePrecision($this->useTimePrecision);
		}

		// Create series
		$series = $this->doPrepareSeries($this->getSeries());

		// Filter data
		$filtered = $this->doFilterData($this->getSeriesBys(), $result->getData());

		$xKey = $this->getSegment('x');
		$yKey = $this->getSegment('y');

		foreach ($filtered as $serieName => $items) {
			/** @var DateSerie $serie */
			$serie = $series[$serieName];
			foreach ($items as $item) {
				$serie->addSegment(new DateSegment(new DateTime($item[$xKey]), $item[$yKey]));
			}
		}

		// Add series to chart
		$this->doAddSeries($chart, $series);

		return $chart;
	}

}
