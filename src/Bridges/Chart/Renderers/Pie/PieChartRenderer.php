<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Chart\Renderers\Pie;

use Tlapnet\Chart\PieChart;
use Tlapnet\Chart\Segment\PieSegment;
use Tlapnet\Report\Bridges\Chart\Renderers\AbstractChartRenderer;
use Tlapnet\Report\Result\Result;

class PieChartRenderer extends AbstractChartRenderer
{

	/** @var bool */
	protected $enableValueInTitle = false;

	public function setEnableValueInTitle(bool $enable = true): void
	{
		$this->enableValueInTitle = $enable;
	}

	/**
	 * @return mixed
	 */
	public function render(Result $result)
	{
		/** @var PieChart $chart */
		$chart = $this->createChart(new PieChart());

		$titleKey = $this->getSegment('title');
		$valueKey = $this->getSegment('value');

		foreach ($result->getData() as $item) {
			$chart->addSegment(new PieSegment($this->getSegmentTitle($item[$titleKey], $item[$valueKey]), $item[$valueKey]));
		}

		return $chart;
	}

	private function getSegmentTitle(string $title, float $value): string
	{
		return $this->enableValueInTitle ? $title . ' (' . $value . ')' : $title;
	}

}
