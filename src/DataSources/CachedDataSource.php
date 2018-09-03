<?php declare(strict_types = 1);

namespace Tlapnet\Report\DataSources;

use DateTimeInterface;
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
	public const CACHE_KEY = 'key';
	public const CACHE_EXPIRATION = Cache::EXPIRATION;
	public const CACHE_SLIDING = Cache::SLIDING;

	/** @var Cache */
	private $cache;

	/** @var DataSource */
	private $inner;

	/** @var mixed[] */
	private $configuration = [];

	public function __construct(IStorage $storage, DataSource $inner)
	{
		$this->cache = new Cache($storage, CacheKeys::CACHE_DATASOURCES);
		$this->inner = $inner;
	}

	public function setKey(string $key): void
	{
		$this->configuration[self::CACHE_KEY] = $key;
	}

	/**
	 * @param string|int|DateTimeInterface $expiration
	 */
	public function setExpiration($expiration): void
	{
		$this->configuration[self::CACHE_EXPIRATION] = $expiration;
	}

	public function setSliding(bool $sliding = true): void
	{
		$this->configuration[self::CACHE_SLIDING] = $sliding;
	}

	public function compile(Parameters $parameters): Resultable
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
				$dependencies[Cache::SLIDING] = true;
			}

			$innerResult = $this->inner->compile($parameters);

			return new Result($innerResult->getData());
		});

		return $cached;
	}

}
