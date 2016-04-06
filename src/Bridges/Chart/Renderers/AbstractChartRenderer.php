<?php

namespace Tlapnet\Report\Bridges\Chart\Renderers;

use Tlapnet\Chart\AbstractChart;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Renderers\Renderer;
use Tlapnet\Report\Utils\Suggestions;

abstract class AbstractChartRenderer implements Renderer
{

	/** @var array */
	protected $mapping = [
		'segments' => [],
		'series' => [],
		'seriesBy' => [],
	];

	/** @var string */
	protected $valueSuffix;

	/**
	 * @param string $from
	 * @param string $to
	 */
	public function addSegment($from, $to)
	{
		$this->mapping['segments'][$from] = $to;
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	protected function getSegment($key, $default = NULL)
	{
		if (isset($this->mapping['segments'][$key])) {
			return $this->mapping['segments'][$key];
		}

		if (func_num_args() < 2) {
			$hint = Suggestions::getSuggestion($this->mapping['segments'], $key);
			throw new InvalidArgumentException("Unknown segment mapping key '$key'" . ($hint ? ", did you mean '$hint'?" : '.'));
		}

		return $default;
	}

	/**
	 * @return array
	 */
	protected function getSegments()
	{
		return $this->mapping['segments'];
	}

	/**
	 * @param string $id
	 * @param string $column
	 * @param mixed $value
	 */
	public function addSerieBy($id, $column, $value)
	{
		if (isset($this->mapping['seriesBy'][$id])) {
			throw new InvalidStateException("Serie by '$id' already exists.");
		}

		$this->mapping['seriesBy'][$id] = (object)[
			'id' => $id,
			'column' => $column,
			'value' => $value,
		];
	}

	/**
	 * @param string $id
	 */
	public function addSerieByAll($id)
	{
		$this->addSerieBy($id, NULL, NULL);
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	protected function getSerieBy($key)
	{
		if (isset($this->mapping['seriesBy'][$key])) {
			return $this->mapping['seriesBy'][$key];
		}

		$hint = Suggestions::getSuggestion($this->mapping['seriesBy'], $key);
		throw new InvalidArgumentException("Unknown by mapping key '$key'" . ($hint ? ", did you mean '$hint'?" : '.'));
	}

	/**
	 * @return array
	 */
	protected function getSeriesBys()
	{
		return $this->mapping['seriesBy'];
	}

	/**
	 * @param string $id
	 * @param string $type
	 * @param string $title
	 * @param string $color
	 */
	public function addSerie($id, $type, $title, $color = NULL)
	{
		if (!isset($this->mapping['seriesBy'][$id])) {
			throw new InvalidStateException("Serie by '$id' does not exist. Please add it first.");
		}

		$this->mapping['series'][$id] = (object)[
			'id' => $id,
			'type' => $type,
			'title' => $title,
			'color' => $color,
		];
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	protected function getSerie($key)
	{
		if (isset($this->mapping['series'][$key])) {
			return $this->mapping['series'][$key];
		}

		$hint = Suggestions::getSuggestion($this->mapping['series'], $key);
		throw new InvalidArgumentException("Unknown serie mapping key '$key'" . ($hint ? ", did you mean '$hint'?" : '.'));
	}

	/**
	 * @return array
	 */
	protected function getSeries()
	{
		return $this->mapping['series'];
	}

	/**
	 * @param string $suffix
	 */
	public function setValueSuffix($suffix)
	{
		$this->valueSuffix = $suffix;
	}

	/**
	 * HELPERS *****************************************************************
	 */

	/**
	 * @param AbstractChart $chart
	 * @return AbstractChart
	 */
	protected function createChart(AbstractChart $chart)
	{
		if ($this->valueSuffix) {
			$chart->setValueSuffix($this->valueSuffix);
		}

		return $chart;
	}

}
