<?php

namespace Tlapnet\Report\Bridges\Chart\Renderers\Category;

use Tlapnet\Chart\Category;
use Tlapnet\Chart\CategoryChart;
use Tlapnet\Chart\Segment\CategorySegment;
use Tlapnet\Chart\Serie\CategorySerie;
use Tlapnet\Report\Bridges\Chart\Renderers\SeriesChartRenderer;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Model\Result\Result;

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
	 * @return void
	 */
	public function addCategory($key, $title)
	{
		$this->categories[] = new Category($key, $title);
	}

	/**
	 * @param object $serie
	 * @return CategorySerie
	 */
	protected function createSerie($serie)
	{
		if (!is_object($serie)) {
			throw new InvalidArgumentException('Argument $serie must be object, "%s" given', gettype($serie));
		}

		return new CategorySerie($serie->type, $serie->title, $serie->color);
	}

	/**
	 * RENDERERING *************************************************************
	 */

	/**
	 * @param Result $result
	 * @return mixed
	 */
	public function render(Result $result)
	{
		/** @var CategoryChart $chart */
		$chart = $this->createChart(new CategoryChart($this->categories));

		// Create series
		$series = $this->doPrepareSeries($this->getSeries());

		// Filter data
		$filtered = $this->doFilterData($this->getSeriesBys(), $result->getData());

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
