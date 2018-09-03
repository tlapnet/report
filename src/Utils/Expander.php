<?php declare(strict_types = 1);

namespace Tlapnet\Report\Utils;

use Tlapnet\Report\Exceptions\Logic\InvalidStateException;

final class Expander
{

	/** @var string */
	private $pattern = '#\{([\w\d\_\-]+)\}#';

	/** @var mixed[] */
	private $parameters;

	/**
	 * @param mixed[] $parameters
	 */
	public function __construct(array $parameters)
	{
		$this->parameters = $parameters;
	}

	public function setPattern(string $pattern): void
	{
		$this->pattern = $pattern;
	}

	/**
	 * @param mixed $input
	 * @return mixed
	 */
	public function execute($input)
	{
		if (is_array($input)) {
			return $this->doArray($input);
		}

		if (is_string($input)) {
			return $this->doSingle($input);
		}

		return $input;
	}

	/**
	 * @param string[] $array
	 * @return string[]
	 */
	public function doArray(array $array): array
	{
		$output = [];
		foreach ($array as $k => $v) {
			$key = $this->doSingle($k);
			$value = $this->doSingle($v);

			$output[$key] = $value;
		}

		return $output;
	}

	public function doSingle(string $str): string
	{
		return preg_replace_callback($this->pattern, function ($matches) {
			if (count($matches) > 2) {
				throw new InvalidStateException('Invalid pattern, only one group is allowed');
			}

			 [$whole, $param] = $matches;

			// Try to replace part with a parameter
			if (isset($this->parameters[$param])) {
				return $this->parameters[$param];
			}

			// Return original part
			return $whole;
		}, $str);
	}

}
