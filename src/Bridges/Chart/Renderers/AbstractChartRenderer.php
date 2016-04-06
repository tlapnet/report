<?php

namespace Tlapnet\Report\Bridges\Chart\Renderers;

use Tlapnet\Chart\AbstractChart;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Renderers\Renderer;
use Tlapnet\Report\Utils\Suggestions;

abstract class AbstractChartRenderer implements Renderer
{

	/** @var array */
	protected $mapping = [
		'segments' => [],
		'series' => [],
		'seriesBy' => [],
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
	 * @return array
	 */
	protected function getSeriesBys()
	{
		return $this->mapping['seriesBy'];
	}

	/**
	 * @param array $conditions
	 * @param string $type
	 * @param string $title
	 * @param string $color
	 * @param string $group
	 */
	public function addSerie(array $conditions = [], $type, $title, $color = NULL, $group = NULL)
	{
		// Generate unique serie ID
		$sid = md5(serialize([$conditions, $type, $title, $color]));

		$this->mapping['series'][$sid] = (object)[
			'id' => $sid,
			'type' => $type,
			'title' => $title,
			'color' => $color,
		];

		$this->mapping['seriesBy'][$sid] = (object)[
			'id' => $sid,
			'conditions' => $conditions,
		];

		$this->mapping['groups'][$sid] = $group;
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
	 * @return array
	 */
	protected function getGroups()
	{
		return $this->mapping['groups'];
	}

	/**
	 * @param string $sid
	 * @return string|NULL
	 */
	protected function getGroupBySerie($sid)
	{
		foreach ($this->mapping['groups'] as $_sid => $group) {
			if ($_sid == $sid) {
				return $group;
			}
		}

		return NULL;
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
