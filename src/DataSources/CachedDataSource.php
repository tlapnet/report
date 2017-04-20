<?php

namespace Tlapnet\Report\DataSources;

use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Tlapnet\Report\Cache\CacheKeys;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Result\Result;
use Tlapnet\Report\Result\Resultable;

class CachedDataSource implements DataSource
{

	/** @var Cache */
	private $cache;

	/** @var DataSource */
	private $inner;

	/** @var array */
	private $configuration = [];

	/**
	 * @param IStorage $storage
	 * @param DataSource $inner
	 */
	public function __construct(IStorage $storage, DataSource $inner)
	{
		$this->cache = new Cache($storage, CacheKeys::CACHE_DATASOURCES);
		$this->inner = $inner;
	}

	/**
	 * CONFIGURATION ***********************************************************
	 */

	/**
	 * @param string $key
	 * @return void
	 */
	public function setKey($key)
	{
		$this->configuration['key'] = $key;
	}

	/**
	 * @param string $expiration
	 * @return void
	 */
	public function setExpiration($expiration)
	{
		$this->configuration['expiration'] = $expiration;
	}

	/**
	 * COMPILING ***************************************************************
	 */

	/**
	 * @param Parameters $parameters
	 * @return Resultable
	 */
	public function compile(Parameters $parameters)
	{
		if (!isset($this->configuration['key'])) {
			throw new InvalidStateException('Cache "key" is required');
		}

		if (!isset($this->configuration['expiration'])) {
			throw new InvalidStateException('Cache "expiration" is required');
		}

		$key = md5(serialize([
			$this->configuration['key'],
			$parameters->toArray(),
		]));

		$cached = $this->cache->load($key, function (&$dependencies) use ($parameters) {
			$dependencies[Cache::EXPIRE] = $this->configuration['expiration'];

			$innerResult = $this->inner->compile($parameters);

			return new Result($innerResult->getData());
		});

		return $cached;
	}

}
