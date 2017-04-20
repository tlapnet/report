<?php

namespace Tlapnet\Report\Bridges\Chart\Renderers\Donut;

use Tlapnet\Chart\DonutChart;
use Tlapnet\Chart\Segment\DonutSegment;
use Tlapnet\Report\Bridges\Chart\Renderers\AbstractChartRenderer;
use Tlapnet\Report\Result\Result;

class DonutChartRenderer extends AbstractChartRenderer
{

	/** @var string */
	protected $title;

	/** @var bool */
	protected $enableRatioLabel;

	/** @var bool */
	protected $enableValueInTitle;

	/**
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @param boolean $enable
	 * @return void
	 */
	public function setEnableRatioLabel($enable = TRUE)
	{
		$this->enableRatioLabel = $enable;
	}

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
		/** @var DonutChart $chart */
		$chart = $this->createChart(new DonutChart());

		if ($this->title) {
			$chart->setTitle($this->title);
		}

		if ($this->enableRatioLabel === TRUE) {
			$chart->enableRatioLabel();
		}

		$titleKey = $this->getSegment('title');
		$valueKey = $this->getSegment('value');

		foreach ($result->getData() as $item) {
			$chart->addSegment(new DonutSegment($this->getSegmentTitle($item[$titleKey], $item[$valueKey]), $item[$valueKey]));
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
