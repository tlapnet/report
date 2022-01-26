<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Chart\Renderers;

use Tlapnet\Chart\AbstractChart;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Renderers\Renderer;
use Tlapnet\Report\Utils\Suggestions;

abstract class AbstractChartRenderer implements Renderer
{

	/** @var mixed[] */
	protected $mapping = [
		'segments' => [],
		'series' => [],
		'seriesBy' => [],
		'groups' => [],
	];

	/** @var string|null */
	protected $valueSuffix;

	public function addSegment(string $from, string $to): void
	{
		$this->mapping['segments'][$from] = $to;
	}

	/**
	 * @param mixed $default
	 * @return mixed
	 */
	protected function getSegment(string $key, $default = null)
	{
		if (isset($this->mapping['segments'][$key])) {
			return $this->mapping['segments'][$key];
		}

		if (func_num_args() < 2) {
			$hint = Suggestions::getSuggestion($this->mapping['segments'], $key);
			throw new InvalidArgumentException(Suggestions::format(sprintf('Unknown segment mapping key "%s"', $key), $hint));
		}

		return $default;
	}

	/**
	 * @return mixed[]
	 */
	protected function getSegments(): array
	{
		return $this->mapping['segments'];
	}

	/**
	 * @return mixed[]
	 */
	protected function getSeriesBys(): array
	{
		return $this->mapping['seriesBy'];
	}

	/**
	 * @param mixed[] $conditions
	 */
	public function addSerie(array $conditions = [], string $type = '', string $title = '', ?string $color = null, ?string $group = null): void
	{
		// Generate unique serie ID
		$sid = md5(serialize([$conditions, $type, $title, $color]));

		$this->mapping['series'][$sid] = (object) [
			'id' => $sid,
			'type' => $type,
			'title' => $title,
			'color' => $color,
		];

		$this->mapping['seriesBy'][$sid] = (object) [
			'id' => $sid,
			'conditions' => $conditions,
		];

		$this->mapping['groups'][$sid] = $group;
	}

	/**
	 * @return mixed
	 */
	protected function getSerie(string $key)
	{
		if (isset($this->mapping['series'][$key])) {
			return $this->mapping['series'][$key];
		}

		$hint = Suggestions::getSuggestion($this->mapping['series'], $key);
		throw new InvalidArgumentException(Suggestions::format(sprintf('Unknown serie mapping key "%s"', $key), $hint));
	}

	/**
	 * @return mixed[]
	 */
	protected function getSeries(): array
	{
		return $this->mapping['series'];
	}

	/**
	 * @return mixed[]
	 */
	protected function getGroups(): array
	{
		return $this->mapping['groups'];
	}

	protected function getGroupBySerie(string $sid): ?string
	{
		foreach ($this->mapping['groups'] as $_sid => $group) {
			if ($_sid === $sid) {
				return $group;
			}
		}

		return null;
	}

	public function setValueSuffix(string $suffix): void
	{
		$this->valueSuffix = $suffix;
	}

	protected function createChart(AbstractChart $chart): AbstractChart
	{
		if ($this->valueSuffix !== null) {
			$chart->setValueSuffix($this->valueSuffix);
		}

		return $chart;
	}

}
