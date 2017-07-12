<?php

namespace Tlapnet\Report\Fetcher;

class FetcherCreator implements FetcherFactoryMethod
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
		return $this->factory->create($sql);
	}

}
