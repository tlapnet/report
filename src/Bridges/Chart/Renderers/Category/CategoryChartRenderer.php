<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Chart\Renderers\Category;

use Tlapnet\Chart\AbstractChart;
use Tlapnet\Chart\Category;
use Tlapnet\Chart\CategoryChart;
use Tlapnet\Chart\Segment\CategorySegment;
use Tlapnet\Chart\Serie\AbstractSerie;
use Tlapnet\Chart\Serie\CategorySerie;
use Tlapnet\Report\Bridges\Chart\Renderers\SeriesChartRenderer;
use Tlapnet\Report\Result\Result;

class CategoryChartRenderer extends SeriesChartRenderer
{

	/** @var Category[] */
	private $categories;

	/**
	 * @param Category[] $categories
	 */
	public function __construct(array $categories = [])
	{
		$this->categories = $categories;
	}

	public function addCategory(string $key, string $title): void
	{
		$this->categories[] = new Category($key, $title);
	}

	/**
	 * @return CategorySerie
	 */
	protected function createSerie(object $serie): AbstractSerie
	{
		return new CategorySerie($serie->type, $serie->title, $serie->color);
	}

	/**
	 * @return CategoryChart
	 */
	public function render(Result $result): AbstractChart
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
