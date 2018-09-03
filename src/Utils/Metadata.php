<?php declare(strict_types = 1);

namespace Tlapnet\Report\Utils;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;

class Metadata
{

	/** @var mixed[] */
	private $data = [];

	/**
	 * @param mixed $value
	 */
	public function set(string $key, $value): void
	{
		$this->data[$key] = $value;
	}

	/**
	 * @param mixed $default
	 * @return mixed
	 */
	public function get(string $key, $default = null)
	{
		if ($this->has($key)) {
			return $this->data[$key];
		}

		if (func_num_args() < 2) {
			$hint = Suggestions::getSuggestion(array_keys($this->data), $key);
			throw new InvalidArgumentException(Suggestions::format(sprintf('Unknown key "%s"', $key), $hint));
		}

		return $default;
	}

	public function has(string $key): bool
	{
		return isset($this->data[$key]);
	}

}
