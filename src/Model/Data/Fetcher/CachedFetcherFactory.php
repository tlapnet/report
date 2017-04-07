<?php

namespace Tlapnet\Report\Model\Data\Fetcher;

use Nette\Caching\IStorage;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;

class CachedFetcherFactory
{

	/** @var FetcherFactory */
	private $factory;

	/** @var IStorage */
	private $storage;

	/** @var array */
	private $configuration = [];

	/**
	 * @param FetcherFactory $factory
	 * @param IStorage $storage
	 */
	public function __construct(FetcherFactory $factory, IStorage $storage)
	{
		$this->factory = $factory;
		$this->storage = $storage;
	}

	/**
	 * @param string $key
	 * @param string $expiration
	 * @return static
	 */
	public function set($key, $expiration = '+1 h')
	{
		$this->configuration['key'] = $key;
		$this->configuration['expiration'] = $expiration;

		return $this;
	}

	/**
	 * @param string $sql
	 * @return CachedFetcher
	 */
	public function create($sql)
	{
		if (!isset($this->configuration['key'])) {
			throw new InvalidStateException('Cache "key" is required');
		}

		if (!isset($this->configuration['expiration'])) {
			throw new InvalidStateException('Cache "expiration" is required');
		}

		$inner = $this->factory->create($sql);
		$fetcher = new CachedFetcher($this->storage, $inner);
		$fetcher->setKey($this->configuration['key']);
		$fetcher->setExpiration($this->configuration['expiration']);

		return $fetcher;
	}

}
