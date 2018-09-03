<?php declare(strict_types = 1);

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Result\Result;
use Tlapnet\Report\Result\Resultable;

class ArrayDataSource implements DataSource
{

	/** @var mixed[] */
	private $data = [];

	/**
	 * @param mixed[] $data
	 */
	public function __construct(array $data)
	{
		$this->data = $data;
	}

	/**
	 * @return Result
	 */
	public function compile(Parameters $parameters): Resultable
	{
		return new Result($this->data);
	}

}
