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
		'groups' => [],
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
	public function addSerieGroup($id, $column, $value)
	{
		if (isset($this->mapping['groups'][$id])) {
			throw new InvalidStateException("SerieGroup '$id' already exists.");
		}

		$this->mapping['groups'][$id] = (object)[
			'id' => $id,
			'column' => $column,
			'value' => $value,
		];
	}

	/**
	 * @param string $id
	 */
	public function addSerieSimpleGroup($id)
	{
		$this->addSerieGroup($id, NULL, NULL);
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	protected function getSerieGroup($key)
	{
		if (isset($this->mapping['groups'][$key])) {
			return $this->mapping['groups'][$key];
		}

		$hint = Suggestions::getSuggestion($this->mapping['groups'], $key);
		throw new InvalidArgumentException("Unknown group mapping key '$key'" . ($hint ? ", did you mean '$hint'?" : '.'));
	}

	/**
	 * @return array
	 */
	protected function getSeriesGroups()
	{
		return $this->mapping['groups'];
	}

	/**
	 * @param string $id
	 * @param string $type
	 * @param string $title
	 * @param string $color
	 */
	public function addSerie($id, $type, $title, $color = NULL)
	{
		if (!isset($this->mapping['groups'][$id])) {
			throw new InvalidStateException("SerieGroup '$id' does not exist. Please add it first.");
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
