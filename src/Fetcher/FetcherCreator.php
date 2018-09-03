<?php declare(strict_types = 1);

namespace Tlapnet\Report\Fetcher;

class FetcherCreator implements FetcherFactoryMethod
{

	/** @var FetcherFactory */
	private $factory;

	public function __construct(FetcherFactory $factory)
	{
		$this->factory = $factory;
	}

	public function create(string $sql): Fetcher
	{
		return $this->factory->create($sql);
	}

}
