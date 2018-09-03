<?php declare(strict_types = 1);

namespace Tlapnet\Report\Fetcher;

use DateTimeInterface;
use Nette\Caching\IStorage;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;

class CachedFetcherFactory
{

	/** @var FetcherFactory */
	private $factory;

	/** @var IStorage */
	private $storage;

	/** @var mixed[] */
	private $configuration = [];

	public function __construct(FetcherFactory $factory, IStorage $storage)
	{
		$this->factory = $factory;
		$this->storage = $storage;
	}

	/**
	 * @param string|int|DateTimeInterface $expiration
	 */
	public function set(string $key, $expiration = '+1 h'): self
	{
		$this->configuration['key'] = $key;
		$this->configuration['expiration'] = $expiration;

		return $this;
	}

	public function create(string $sql): CachedFetcher
	{
		if (!isset($this->configuration['key'])) {
			throw new InvalidStateException('Cache "key" is required');
		}

		if (!isset($this->configuration['expiration'])) {
			throw new InvalidStateException('Cache "expiration" is required');
		}

		$inner = $this->factory->create($sql);
		$fetcher = new CachedFetcher($inner, $this->storage);
		$fetcher->setKey($this->configuration['key']);
		$fetcher->setExpiration($this->configuration['expiration']);

		return $fetcher;
	}

}
