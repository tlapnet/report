<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\Database\DataSources;

use Iterator;
use Nette\Database\ResultSet;
use Tlapnet\Report\Result\Result;

class LazyResultSet extends Result
{

	/** @var ResultSet */
	private $resultSet;

	public function __construct(ResultSet $resultset)
	{
		parent::__construct();
		$this->resultSet = $resultset;
	}

	/**
	 * @return mixed[]
	 */
	public function getData(): array
	{
		return $this->resultSet->fetchAll();
	}

	public function count(): int
	{
		return count($this->resultSet->fetchAll());
	}

	public function getIterator(): Iterator
	{
		return $this->resultSet;
	}

}
