<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Chart\Renderers;

use Tlapnet\Chart\AbstractChart;
use Tlapnet\Chart\CategoryChart;
use Tlapnet\Chart\Chart;
use Tlapnet\Chart\DateChart;
use Tlapnet\Chart\Serie\AbstractSerie;

abstract class SeriesChartRenderer extends AbstractChartRenderer
{

	abstract protected function createSerie(object $serie): AbstractSerie;

	/**
	 * @param object[] $series
	 * @return AbstractSerie[]
	 */
	protected function doPrepareSeries(array $series): array
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
	 */
	protected function doAddSeries(AbstractChart $chart, array $series): void
	{
		foreach ($series as $sid => $serie) {
			$chart->addSerie($serie, $this->getGroupBySerie($sid));
		}
	}

	/**
	 * @param object[] $seriesBys
	 * @param mixed[] $data
	 * @return mixed[]
	 */
	protected function doFilterData(array $seriesBys, array $data): array
	{
		$filtered = [];

		// Prepare array
		foreach ($seriesBys as $key => $serieBy) {
			$filtered[$key] = [];
		}

		// Filter data
		foreach ($data as $item) {
			foreach ($seriesBys as $serieBy) {
				if ($serieBy->conditions !== []) {
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
	 * @param mixed[] $item
	 */
	protected function isItemMatched(array $item, object $serieBy): bool
	{
		foreach ($serieBy->conditions as $column => $value) {
			if ($item[$column] === $value) {
				return true;
			}
		}

		return false;
	}

}
