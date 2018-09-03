<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Chart\Renderers\Donut;

use Tlapnet\Chart\AbstractChart;
use Tlapnet\Chart\DonutChart;
use Tlapnet\Chart\Segment\DonutSegment;
use Tlapnet\Report\Bridges\Chart\Renderers\AbstractChartRenderer;
use Tlapnet\Report\Result\Result;

class DonutChartRenderer extends AbstractChartRenderer
{

	/** @var string */
	protected $title;

	/** @var bool */
	protected $enableRatioLabel = false;

	/** @var bool */
	protected $enableValueInTitle = false;

	public function setTitle(string $title): void
	{
		$this->title = $title;
	}

	public function setEnableRatioLabel(bool $enable = true): void
	{
		$this->enableRatioLabel = $enable;
	}

	public function setEnableValueInTitle(bool $enable = true): void
	{
		$this->enableValueInTitle = $enable;
	}

	/**
	 * @return DonutChart
	 */
	public function render(Result $result): AbstractChart
	{
		/** @var DonutChart $chart */
		$chart = $this->createChart(new DonutChart());

		if ($this->title !== null) {
			$chart->setTitle($this->title);
		}

		if ($this->enableRatioLabel === true) {
			$chart->enableRatioLabel();
		}

		$titleKey = $this->getSegment('title');
		$valueKey = $this->getSegment('value');

		foreach ($result->getData() as $item) {
			$chart->addSegment(new DonutSegment($this->getSegmentTitle($item[$titleKey], $item[$valueKey]), $item[$valueKey]));
		}

		return $chart;
	}

	private function getSegmentTitle(string $title, float $value): string
	{
		return $this->enableValueInTitle ? $title . ' (' . $value . ')' : $title;
	}

}
