<?php

namespace Tlapnet\Report\Export;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Utils\Suggestions;

abstract class AbstractExporter implements Exporter
{

	/** @var array */
	protected $options = [];

	/**
	 * OPTIONS *****************************************************************
	 */

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public function setOption($name, $value)
	{
		$this->options[$name] = $value;
	}

	/**
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function getOption($name, $default = NULL)
	{
		if (isset($this->options[$name])) {
			return $this->options[$name];
		}

		if (func_num_args() < 2) {
			$hint = Suggestions::getSuggestion(array_keys($this->options), $name);
			throw new InvalidArgumentException(Suggestions::format(sprintf('Unknown option "%s"', $name), $hint));
		}

		return $default;
	}

	/**
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}

}
