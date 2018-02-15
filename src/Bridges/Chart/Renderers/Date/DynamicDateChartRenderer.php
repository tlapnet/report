<?php

namespace Tlapnet\Report\Bridges\Chart\Renderers\Date;

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

	/**
	 * @param string $filterColumn
	 * @param string $titleColumn
	 * @param string $line
	 * @return void
	 */
	public function addDynamicSeries($filterColumn, $titleColumn, $line)
	{
		$this->dynamicSeriesFilter = $filterColumn;
		$this->dynamicSeriesTitle = $titleColumn;
		$this->dynamicSeriesLine = $line;
	}

	/**
	 * RENDERING ***************************************************************
	 */

	/**
	 * @param Result $result
	 * @return mixed
	 */
	public function render(Result $result)
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
