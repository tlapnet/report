<?php

namespace Tlapnet\Report\Bridges\Chart\Renderers\Donut;

use Tlapnet\Chart\DonutChart;
use Tlapnet\Chart\Segment\DonutSegment;
use Tlapnet\Report\Bridges\Chart\Renderers\AbstractChartRenderer;
use Tlapnet\Report\Model\Data\Report;

class DonutChartRenderer extends AbstractChartRenderer
{

	/** @var string */
	protected $title;

	/** @var bool */
	protected $enableRatioLabel;

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @param boolean $enable
	 */
	public function setEnableRatioLabel($enable = TRUE)
	{
		$this->enableRatioLabel = $enable;
	}

	/**
	 * API *********************************************************************
	 */

	/**
	 * @param Report $report
	 * @return mixed
	 */
	public function render(Report $report)
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

		foreach ($report->getData() as $item) {
			$chart->addSegment(new DonutSegment($item[$titleKey], $item[$valueKey]));
		}

		return $chart;
	}

}
