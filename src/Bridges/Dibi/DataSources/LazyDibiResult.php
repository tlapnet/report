<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Dibi\DataSources;

use Dibi\Result as DibiResult;
use Dibi\ResultIterator as DibiResultIterator;
use Iterator;
use Tlapnet\Report\Result\Result;

class LazyDibiResult extends Result
{

	/** @var DibiResult */
	private $result;

	public function __construct(DibiResult $result)
	{
		parent::__construct();
		$this->result = $result;
	}

	/**
	 * @return mixed[]
	 */
	public function getData(): array
	{
		return $this->result->fetchAll();
	}

	public function count(): int
	{
		return $this->result->count();
	}

	/**
	 * @return DibiResultIterator
	 */
	public function getIterator(): Iterator
	{
		return $this->result->getIterator();
	}

}
