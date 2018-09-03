<?php declare(strict_types = 1);

namespace Tlapnet\Report\Utils;

use Tlapnet\Report\Exceptions\Logic\InvalidStateException;

final class Switcher
{

	/** @var string */
	private $pattern = '#\{([\w\d\_\-]+)\}#';

	/** @var string */
	private $placeholder = '?';

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

	public function setPlaceholder(string $placeholder): void
	{
		$this->placeholder = $placeholder;
	}

	/**
	 * @param mixed $input
	 * @return mixed
	 */
	public function execute($input)
	{
		if (is_string($input)) {
			return $this->doSwitch($input);
		}

		return $input;
	}

	/**
	 * @return mixed[]
	 */
	protected function doSwitch(string $str): array
	{
		$args = [];
		$str = preg_replace_callback($this->pattern, function ($matches) use (&$args) {
			if (count($matches) > 2) {
				throw new InvalidStateException('Invalid pattern, only one group is allowed');
			}

			 [$whole, $param] = $matches;

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
