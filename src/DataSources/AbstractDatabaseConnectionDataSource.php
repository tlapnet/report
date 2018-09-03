<?php declare(strict_types = 1);

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Exceptions\Logic\InvalidStateException;

abstract class AbstractDatabaseConnectionDataSource extends AbstractDatabaseDataSource
{

	/** @var mixed[] */
	protected $config = [];

	/**
	 * @param mixed[] $config
	 */
	public function __construct(array $config)
	{
		$this->config = $config;
	}

	/**
	 * @param mixed $default
	 * @return mixed
	 */
	protected function getConfig(string $key, $default = null)
	{
		if (isset($this->config[$key])) {
			return $this->config[$key];
		}

		if (func_num_args() < 2) {
			throw new InvalidStateException('Key "' . $key . '" not found in [' . implode(',', array_keys($this->config)) . ']');
		}

		return $default;
	}

	abstract protected function connect(): void;

}
