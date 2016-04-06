<?php

namespace Tlapnet\Report\Bridges\Nette\DataSources;

use Nette\Database\Connection;
use Tlapnet\Report\DataSources\AbstractDatabaseDataSource;
use Tlapnet\Report\Heap\Heap;
use Tlapnet\Report\HeapBox\ParameterList;

class NetteDatabaseDataSource extends AbstractDatabaseDataSource
{

	/** @var Connection */
	protected $connection;

	/**
	 * @param array $config
	 */
	public function __construct(array $config)
	{
		parent::__construct($config);

		$this->connection = new Connection(
			$this->getConfig('dns'),
			$this->getConfig('user'),
			$this->getConfig('password'),
			$this->getConfig('options', ['lazy' => TRUE])
		);
	}

	/**
	 * @param ParameterList $parameters
	 * @return Heap
	 */
	public function compile(ParameterList $parameters)
	{
		$expander = $parameters->createExpander();
		$sql = $this->getSql();

		// Replace placeholders
		$query = $expander->expand($sql);

		// Execute query
		$resulset = $this->connection->query($query);

		$heap = new Heap($resulset->fetchAll());

		return $heap;
	}

}
