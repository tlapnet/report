<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Chart\Renderers\Date;

use Tlapnet\Chart\AbstractChart;
use Tlapnet\Chart\DateChart;
use Tlapnet\Chart\Segment\DateSegment;
use Tlapnet\Chart\Serie\DateSerie;
use Tlapnet\Report\Result\Result;
use Tlapnet\Report\Utils\DateTime;

class DynamicDateChartRenderer extends DateChartRenderer
{

	/** @var string */
	private $dynamicSeriesLine;

	/** @var string */
	private $dynamicSeriesTitle;

	/** @var string */
	private $dynamicSeriesFilter;

	public function addDynamicSeries(string $filterColumn, string $titleColumn, string $line): void
	{
		$this->dynamicSeriesFilter = $filterColumn;
		$this->dynamicSeriesTitle = $titleColumn;
		$this->dynamicSeriesLine = $line;
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

		// Dynamically create series from data
		$series = [];
		foreach ($result->getData() as $dibiRow) {
			$series[$dibiRow[$this->dynamicSeriesFilter]] = $dibiRow[$this->dynamicSeriesTitle];
		}

		$condition = [];
		foreach ($series as $filterValue => $title) {
			$condition[$this->dynamicSeriesFilter] = $filterValue;
			$this->addSerie($condition, $this->dynamicSeriesLine, $title);
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
