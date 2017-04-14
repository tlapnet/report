<?php

namespace Tlapnet\Report\Model\Export;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Model\Result\Resultable;
use Tlapnet\Report\Utils\Suggestions;

class Exporters
{

	/** @var Exporter[] */
	private $exporters = [];

	/**
	 * @param string $name
	 * @param Exporter $exporter
	 * @return void
	 */
	public function add($name, Exporter $exporter)
	{
		if (isset($this->exporters[$name])) {
			throw new InvalidStateException(sprintf('Exporter "%s" already exists', $name));
		}

		$this->exporters[$name] = $exporter;
	}

	/**
	 * @return bool
	 */
	public function isEmpty()
	{
		return count($this->exporters) == 0;
	}

	/**
	 * @return Exporter[]
	 */
	public function getAll()
	{
		return $this->exporters;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function has($name)
	{
		return isset($this->exporters, $name);
	}

	/**
	 * @param string $name
	 * @return Exporter
	 */
	public function get($name)
	{
		if (!$this->has($name)) {
			$hint = Suggestions::getSuggestion(array_keys($this->exporters), $name);
			throw new InvalidArgumentException(Suggestions::format(sprintf('Unknown exporter "%s"', $name), $hint));
		}

		return $this->exporters[$name];
	}

	/**
	 * EXPORT ******************************************************************
	 */

	/**
	 * @param string $name
	 * @param Resultable $result
	 * @param array $options
	 * @return Exportable
	 */
	public function export($name, Resultable $result, array $options = [])
	{
		$exporter = $this->get($name);
		$exportable = $exporter->export($result, $options);

		return $exportable;
	}

}
