<?php

namespace Tlapnet\Report\Model\Data\Fetcher;

final class FetcherCreator implements FetcherFactoryMethod
{

	/** @var FetcherFactory */
	private $factory;

	/**
	 * @param FetcherFactory $factory
	 */
	public function __construct(FetcherFactory $factory)
	{
		$this->factory = $factory;
	}

	/**
	 * @param string $sql
	 * @return Fetcher
	 */
	public function create($sql)
	{
		return new $this->factory->create($sql);
	}

}
