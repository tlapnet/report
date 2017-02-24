<?php

namespace Tlapnet\Report\Bridges\Chart\Renderers;

use Tlapnet\Chart\CategoryChart;
use Tlapnet\Chart\Chart;
use Tlapnet\Chart\DateChart;
use Tlapnet\Chart\Serie\AbstractSerie;

abstract class SeriesChartRenderer extends AbstractChartRenderer
{

	/**
	 * @param object $serie
	 * @return AbstractSerie
	 */
	abstract protected function createSerie($serie);

	/**
	 * @param array $series
	 * @return AbstractSerie[]
	 */
	protected function doPrepareSeries(array $series)
	{
		$_series = [];
		foreach ($series as $sid => $serie) {
			$_series[$sid] = $this->createSerie($serie);
		}

		return $_series;
	}

	/**
	 * @param CategoryChart|Chart|DateChart $chart
	 * @param AbstractSerie[] $series
	 * @return void
	 */
	protected function doAddSeries($chart, array $series)
	{
		foreach ($series as $sid => $serie) {
			$chart->addSerie($serie, $this->getGroupBySerie($sid));
		}
	}

	/**
	 * @param array $seriesBys
	 * @param array $data
	 * @return array
	 */
	protected function doFilterData($seriesBys, array $data)
	{
		$filtered = [];

		// Prepare array
		foreach ($seriesBys as $key => $serieBy) {
			$filtered[$key] = [];
		}

		// Filter data
		foreach ($data as $item) {
			foreach ($seriesBys as $serieBy) {
				if (empty($serieBy->conditions)) {
					// Common serie
					$filtered[$serieBy->id][] = $item;
				} else {
					// Test if row belongs to serie
					if ($this->isItemMatched($item, $serieBy)) {
						$filtered[$serieBy->id][] = $item;
					}
				}

			}
		}

		return $filtered;
	}

	/**
	 * @param array $item
	 * @param object $serieBy
	 * @return bool
	 */
	protected function isItemMatched($item, $serieBy)
	{
		foreach ($serieBy->conditions as $column => $value) {
			if ($item[$column] == $value) {
				return TRUE;
			}
		}

		return FALSE;
	}

}
