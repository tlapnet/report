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
	 * @param CategoryChart|Chart|DateChart $chart
	 * @param array $series
	 * @return AbstractSerie[]
	 */
	protected function doPrepareSeries($chart, array $series)
	{
		$_series = [];
		foreach ($series as $serie) {
			$_series[$serie->id] = $this->createSerie($serie);
		}

		return $_series;
	}

	/**
	 * @param CategoryChart|Chart|DateChart $chart
	 * @param AbstractSerie[] $series
	 */
	protected function doAddSeries($chart, array $series)
	{
		foreach ($series as $serie) {
			$chart->addSerie($serie);
		}
	}

	/**
	 * @param $groups
	 * @param array $data
	 * @return array
	 */
	protected function doFilterData($groups, array $data)
	{
		$filtered = [];

		// Prepare array
		foreach ($groups as $key => $group) {
			$filtered[$key] = [];
		}

		// Filter data
		foreach ($data as $item) {
			foreach ($groups as $group) {
				if ($group->column === NULL && $group->value === NULL) {
					// Common group
					$filtered[$group->id][] = $item;
				} else {
					// Test if row belongs to group
					if ($item[$group->column] == $group->value) {
						$filtered[$group->id][] = $item;
					}
				}

			}
		}

		return $filtered;
	}

}
