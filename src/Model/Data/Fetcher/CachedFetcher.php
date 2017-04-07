<?php

namespace Tlapnet\Report\Model\Data\Fetcher;

use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Model\Cache\CacheKeys;

class CachedFetcher implements Fetcher
{

	/** @var Fetcher */
	protected $inner;

	/** @var Cache */
	private $cache;

	/** @var array */
	private $configuration = [];

	/**
	 * @param IStorage $storage
	 * @param Fetcher $inner
	 */
	public function __construct(IStorage $storage, Fetcher $inner)
	{
		$this->cache = new Cache($storage, CacheKeys::CACHE_FETCHER);
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
	 * API *********************************************************************
	 */

	/**
	 * @return mixed
	 */
	public function fetch()
	{
		if (!isset($this->configuration['key'])) {
			throw new InvalidStateException('Cache "key" is required');
		}

		if (!isset($this->configuration['expiration'])) {
			throw new InvalidStateException('Cache "expiration" is required');
		}

		$cacheKey = md5(serialize([
			'fetch',
			$this->configuration['key'],
		]));

		return $this->cache->load($cacheKey, function (&$dependencies) {
			$dependencies[Cache::EXPIRATION] = $this->configuration['expiration'];

			return $this->inner->fetch();
		});
	}

	/**
	 * @param string $column
	 * @return mixed
	 */
	public function fetchSingle($column = NULL)
	{
		if (!isset($this->configuration['key'])) {
			throw new InvalidStateException('Cache "key" is required');
		}

		if (!isset($this->configuration['expiration'])) {
			throw new InvalidStateException('Cache "expiration" is required');
		}

		$cacheKey = md5(serialize([
			'fetchSingle',
			$this->configuration['key'],
			$column,
		]));

		return $this->cache->load($cacheKey, function (&$dependencies) use ($column) {
			$dependencies[Cache::EXPIRATION] = $this->configuration['expiration'];

			return $this->inner->fetchSingle($column);
		});
	}

	/**
	 * @param int $offset
	 * @param int $limit
	 * @return mixed
	 */
	public function fetchAll($offset = NULL, $limit = NULL)
	{
		if (!isset($this->configuration['key'])) {
			throw new InvalidStateException('Cache "key" is required');
		}

		if (!isset($this->configuration['expiration'])) {
			throw new InvalidStateException('Cache "expiration" is required');
		}

		$cacheKey = md5(serialize([
			'fetchAll',
			$this->configuration['key'],
			$offset,
			$limit,
		]));

		return $this->cache->load($cacheKey, function (&$dependencies) use ($offset, $limit) {
			$dependencies[Cache::EXPIRATION] = $this->configuration['expiration'];

			return $this->inner->fetchAll($offset, $limit);
		});
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @return mixed
	 */
	public function fetchPairs($key = NULL, $value = NULL)
	{
		if (!isset($this->configuration['key'])) {
			throw new InvalidStateException('Cache "key" is required');
		}

		if (!isset($this->configuration['expiration'])) {
			throw new InvalidStateException('Cache "expiration" is required');
		}

		$cacheKey = md5(serialize([
			'fetchPairs',
			$this->configuration['key'],
			$key,
			$value,
		]));

		return $this->cache->load($cacheKey, function (&$dependencies) use ($key, $value) {
			$dependencies[Cache::EXPIRATION] = $this->configuration['expiration'];

			return $this->inner->fetchPairs($key, $value);
		});
	}

}
