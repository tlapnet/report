<?php

namespace Tlapnet\Report\Utils;

use Tlapnet\Report\Exceptions\Logic\InvalidStateException;

final class Expander
{

	/** @var string */
	private $pattern = '#\{([\w\d\_\-]+)\}#';

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
	 * @param string $pattern
	 * @return void
	 */
	public function setPattern($pattern)
	{
		$this->pattern = $pattern;
	}

	/**
	 * API *********************************************************************
	 */

	/**
	 * @param array|string $input
	 * @return mixed
	 */
	public function execute($input)
	{
		if (is_array($input)) {
			return $this->doArray($input);
		} else if (is_string($input)) {
			return $this->doSingle($input);
		} else {
			return $input;
		}
	}

	/**
	 * @param array $array
	 * @return array
	 */
	public function doArray(array $array)
	{
		$output = [];
		foreach ($array as $k => $v) {
			$key = $this->doSingle($k);
			$value = $this->doSingle($v);

			$output[$key] = $value;
		}

		return $output;
	}

	/**
	 * @param string $str
	 * @return string
	 */
	public function doSingle($str)
	{
		return preg_replace_callback($this->pattern, function ($matches) {
			if (count($matches) > 2) {
				throw new InvalidStateException('Invalid pattern, only one group is allowed');
			}

			list ($whole, $param) = $matches;

			// Try to replace part with a parameter
			if (isset($this->parameters[$param])) {
				return $this->parameters[$param];
			}

			// Return original part
			return $whole;
		}, $str);
	}

}
