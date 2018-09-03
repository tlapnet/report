<?php declare(strict_types = 1);

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Result\Result;
use Tlapnet\Report\Result\Resultable;

final class DummyDataSource implements DataSource
{

	/** @var mixed[] */
	private $return;

	/**
	 * @param mixed[] $return
	 */
	public function __construct(array $return = [])
	{
		$this->return = $return;
	}

	/**
	 * @return Result
	 */
	public function compile(Parameters $parameters): Resultable
	{
		return new Result($this->return);
	}

}
