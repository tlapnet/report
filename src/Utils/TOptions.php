<?php

namespace Tlapnet\Report\Utils;

trait TOptions
{

	/** @var Metadata */
	protected $metadata;

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function setOption($key, $value)
	{
		$this->metadata->set($key, $value);
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getOption($key, $default = NULL)
	{
		if (func_num_args() < 2) {
			return $this->metadata->get($key);
		} else {
			return $this->metadata->get($key, $default);
		}
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasOption($key)
	{
		return $this->metadata->has($key);
	}

}
