<?php declare(strict_types = 1);

namespace Tlapnet\Report\Fetcher;

use DateTimeInterface;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Tlapnet\Report\Cache\CacheKeys;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;

class CachedFetcher implements Fetcher
{

	/** @var Fetcher */
	protected $inner;

	/** @var Cache */
	private $cache;

	/** @var mixed[] */
	private $configuration = [];

	public function __construct(Fetcher $inner, IStorage $storage)
	{
		$this->inner = $inner;
		$this->cache = new Cache($storage, CacheKeys::CACHE_FETCHER);
	}

	public function setKey(string $key): void
	{
		$this->configuration['key'] = $key;
	}

	/**
	 * @param string|int|DateTimeInterface $expiration
	 */
	public function setExpiration($expiration): void
	{
		$this->configuration['expiration'] = $expiration;
	}

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
	 * @param string|int|null $column
	 * @return mixed
	 */
	public function fetchSingle($column = null)
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
	 * @return mixed
	 */
	public function fetchAll(?int $offset = null, ?int $limit = null)
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
	 * @return mixed
	 */
	public function fetchPairs(?string $key = null, ?string $value = null)
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
