<?php

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Exceptions\Logic\InvalidStateException;

abstract class AbstractDatabaseDataSource implements DataSource
{

	/** @var array */
	protected $config = [];

	/** @var mixed */
	protected $sql;

	/**
	 * @param array $config
	 */
	public function __construct(array $config)
	{
		$this->config = $config;
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	protected function getConfig($key, $default = NULL)
	{
		if (isset($this->config[$key])) {
			return $this->config[$key];
		}

		if (func_num_args() < 2) {
			throw new InvalidStateException("Key '$key' not found in [" . implode(',', array_keys($this->config)) . "]");
		}

		return $default;
	}

	/**
	 * @return mixed
	 */
	public function getSql()
	{
		return $this->sql;
	}

	/**
	 * @param mixed $sql
	 */
	public function setSql($sql)
	{
		$this->sql = $sql;
	}

}
