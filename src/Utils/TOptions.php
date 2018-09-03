<?php declare(strict_types = 1);

namespace Tlapnet\Report\Utils;

trait TOptions
{

	/** @var Metadata */
	protected $metadata;

	/**
	 * @param mixed $value
	 */
	public function setOption(string $key, $value): void
	{
		$this->metadata->set($key, $value);
	}

	/**
	 * @param mixed $default
	 * @return mixed
	 */
	public function getOption(string $key, $default = null)
	{
		if (func_num_args() < 2) {
			return $this->metadata->get($key);
		} else {
			return $this->metadata->get($key, $default);
		}
	}

	public function hasOption(string $key): bool
	{
		return $this->metadata->has($key);
	}

}
