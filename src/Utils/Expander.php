<?php

namespace Tlapnet\Report\Utils;

final class Expander
{

	// Delimiter
	const DELIMITER = '%';

	/** @var array */
	private $parameters;

	/**
	 * @param array $parameters
	 */
	public function __construct(array $parameters)
	{
		$this->parameters = $parameters;
	}

	/**
	 * @param mixed $input
	 * @return mixed
	 */
	public function expand($input)
	{
		if (is_array($input)) {
			return $this->arr($input);
		} else if (is_string($input)) {
			return $this->str($input);
		} else {
			return $input;
		}
	}

	/**
	 * @param array $array
	 * @return array
	 */
	public function arr(array $array)
	{
		$output = [];
		foreach ($array as $k => $v) {
			$key = str_replace(
				$this->getPlaceholders(),
				$this->getReplacements(),
				$k
			);

			$value = str_replace(
				$this->getPlaceholders(),
				$this->getReplacements(),
				$v
			);

			$output[$key] = $value;
		}

		return $output;
	}

	/**
	 * @param string $str
	 * @return string
	 */
	public function str($str)
	{
		return str_replace(
			$this->getPlaceholders(),
			$this->getReplacements(),
			$str
		);
	}

	/**
	 * @return array
	 */
	protected function getPlaceholders()
	{
		$placeholders = [];
		foreach ($this->parameters as $name => $value) {
			$placeholders[] = self::DELIMITER . $name . self::DELIMITER;
		}

		return $placeholders;
	}

	/**
	 * @return array
	 */
	protected function getReplacements()
	{
		$replacements = [];
		foreach ($this->parameters as $name => $value) {
			$replacements[] = $value;
		}

		return $replacements;
	}

}