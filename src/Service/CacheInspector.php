<?php

namespace Tlapnet\Report\Service;

use Nette\DI\Container;
use Tlapnet\Report\DataSources\CachedDataSource;
use Tlapnet\Report\Subreport\Subreport;

class CacheInspector
{

	/** @var Container */
	protected $container;

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * @return Subreport[]
	 */
	public function findSubreports()
	{
		$services = [];
		$names = $this->container->findByType(CachedDataSource::class);
		foreach ($names as $name) {
			$services[] = $this->container->getService($name);
		}

		return $services;
	}

}
