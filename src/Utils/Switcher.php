<?php

namespace Tlapnet\Report\Utils;

use Tlapnet\Report\Exceptions\Logic\InvalidStateException;

final class Switcher
{

	/** @var string */
	private $pattern = '#\{([\w\d\_\-]+)\}#';

	/** @var string */
	private $placeholder = '?';

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
	 * @param string $placeholder
	 * @return void
	 */
	public function setPlaceholder($placeholder)
	{
		$this->placeholder = $placeholder;
	}

	/**
	 * API *********************************************************************
	 */

	/**
	 * @param string|array $input
	 * @return array [input, args]
	 */
	public function execute($input)
	{
		if (is_string($input)) {
			return $this->doSwitch($input);
		} else {
			return $input;
		}
	}

	/**
	 * @param string $str
	 * @return array [input, args]
	 */
	protected function doSwitch($str)
	{
		$args = [];
		$str = preg_replace_callback($this->pattern, function ($matches) use (&$args) {
			if (count($matches) > 2) {
				throw new InvalidStateException('Invalid pattern, only one group is allowed');
			}

			list ($whole, $param) = $matches;

			// Replace with placeholder, if we have given parameter
			if (isset($this->parameters[$param])) {
				// Append to array
				$args[] = $this->parameters[$param];

				// Return placeholder
				return $this->placeholder;
			}

			// Return original part
			return $whole;
		}, $str);

		return [$str, $args];
	}

}
