<?php

namespace Tlapnet\Report\Bridges\Chart\Renderers\Category;

use Tlapnet\Chart\Category;
use Tlapnet\Chart\CategoryChart;
use Tlapnet\Chart\Segment\CategorySegment;
use Tlapnet\Chart\Serie\CategorySerie;
use Tlapnet\Chart\Serie\Serie;
use Tlapnet\Report\Bridges\Chart\Renderers\SeriesChartRenderer;
use Tlapnet\Report\Model\Data\Report;

class CategoryChartRenderer extends SeriesChartRenderer
{

	/** @var Category[] */
	private $categories = [];

	/**
	 * @param Category[] $categories
	 */
	public function __construct(array $categories = [])
	{
		$this->categories = $categories;
	}

	/**
	 * @param mixed $key
	 * @param string $title
	 */
	public function addCategory($key, $title)
	{
		$this->categories[] = new Category($key, $title);
	}

	/**
	 * @param object $serie
	 * @return Serie
	 */
	protected function createSerie($serie)
	{
		return new CategorySerie($serie->type, $serie->title, $serie->color);
	}

	/**
	 * RENDERERING *************************************************************
	 */

	/**
	 * @param Report $heap
	 * @return mixed
	 */
	public function render(Report $heap)
	{
		/** @var CategoryChart $chart */
		$chart = $this->createChart(new CategoryChart($this->categories));

		// Create series
		$series = $this->doPrepareSeries($this->getSeries());

		// Filter data
		$filtered = $this->doFilterData($this->getSeriesBys(), $heap->getData());

		$xKey = $this->getSegment('x');
		$yKey = $this->getSegment('y');

		foreach ($filtered as $serieName => $items) {
			/** @var CategorySerie $serie */
			$serie = $series[$serieName];
			foreach ($items as $item) {
				$serie->addSegment(new CategorySegment($item[$xKey], $item[$yKey]));
			}
		}

		// Add series to chart
		$this->doAddSeries($chart, $series);

		return $chart;
	}

}
