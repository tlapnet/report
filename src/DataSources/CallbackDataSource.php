<?php declare(strict_types = 1);

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Result\Resultable;

final class CallbackDataSource implements DataSource
{

	/** @var callable */
	private $callback;

	/**
	 * @param callable(Parameters $parameters): Resultable $callback
	 */
	public function __construct(callable $callback)
	{
		$this->callback = $callback;
	}

	public function compile(Parameters $parameters): Resultable
	{
		return call_user_func($this->callback, $parameters);
	}

}
