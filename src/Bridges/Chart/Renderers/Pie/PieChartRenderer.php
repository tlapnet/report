<?php

namespace Tlapnet\Report\Bridges\Chart\Renderers\Pie;

use Tlapnet\Chart\PieChart;
use Tlapnet\Chart\Segment\PieSegment;
use Tlapnet\Report\Bridges\Chart\Renderers\AbstractChartRenderer;
use Tlapnet\Report\Result\Result;

class PieChartRenderer extends AbstractChartRenderer
{

	/** @var bool */
	protected $enableValueInTitle;

	/**
	 * @param bool $enable
	 * @return void
	 */
	public function setEnableValueInTitle($enable = TRUE)
	{
		$this->enableValueInTitle = $enable;
	}

	/**
	 * API *********************************************************************
	 */

	/**
	 * @param Result $result
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

	/**
	 * @param string $title
	 * @param int | float $value
	 * @return string
	 */
	private function getSegmentTitle($title, $value)
	{
		return $this->enableValueInTitle ? $title . ' (' . $value . ')' : $title;
	}

}
