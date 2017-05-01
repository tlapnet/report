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

	// Cache keys
	const CACHE_KEY = 'key';
	const CACHE_EXPIRATION = Cache::EXPIRATION;
	const CACHE_SLIDING = Cache::SLIDING;

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
		$this->configuration[self::CACHE_KEY] = $key;
	}

	/**
	 * @param string $expiration
	 * @return void
	 */
	public function setExpiration($expiration)
	{
		$this->configuration[self::CACHE_EXPIRATION] = $expiration;
	}

	/**
	 * @param string $sliding
	 * @return void
	 */
	public function setSliding($sliding = TRUE)
	{
		$this->configuration[self::CACHE_SLIDING] = $sliding;
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
		if (!isset($this->configuration[self::CACHE_KEY])) {
			throw new InvalidStateException(sprintf('Cache "%s" is required', self::CACHE_KEY));
		}

		if (!isset($this->configuration[self::CACHE_EXPIRATION])) {
			throw new InvalidStateException(sprintf('Cache "%s" is required', self::CACHE_EXPIRATION));
		}

		$key = md5(serialize([
			$this->configuration[self::CACHE_KEY],
			$parameters->toArray(),
		]));

		$cached = $this->cache->load($key, function (&$dependencies) use ($parameters) {
			$dependencies[Cache::EXPIRE] = $this->configuration[self::CACHE_EXPIRATION];
			$dependencies[Cache::TAGS] = [
				'reports',
				sprintf('reports/%s', $this->configuration[self::CACHE_KEY]),
				sprintf('reports/%s/datasource', $this->configuration[self::CACHE_KEY]),
			];

			if (isset($this->configuration[self::CACHE_SLIDING])) {
				$dependencies[Cache::SLIDING] = TRUE;
			}

			$innerResult = $this->inner->compile($parameters);

			return new Result($innerResult->getData());
		});

		return $cached;
	}

}
