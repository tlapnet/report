<?php

namespace Tlapnet\Report\Bridges\Nette\DataSources;

use Nette\Database\Connection;
use Nette\Database\Helpers;
use Tlapnet\Report\DataSources\AbstractDatabaseDataSource;
use Tlapnet\Report\Heap\Heap;
use Tlapnet\Report\HeapBox\ParameterList;
use Tracy\Debugger;

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
			sprintf(
				'%s:host=%s;dbname=%s',
				$this->getConfig('driver'),
				$this->getConfig('host'),
				$this->getConfig('database')
			),
			$this->getConfig('user'),
			$this->getConfig('password'),
			$this->getConfig('options', ['lazy' => TRUE])
		);
	}

	/**
	 * Register this datasource to Tracy panel
	 */
	public function registerTracyPanel()
	{
		if (class_exists(Debugger::class)) {
			Helpers::createDebugPanel($this->connection);
		}
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
