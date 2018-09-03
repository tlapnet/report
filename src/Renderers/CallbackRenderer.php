<?php declare(strict_types = 1);

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Result\Result;

final class CallbackRenderer implements Renderer
{

	/** @var callable */
	private $callback;

	/**
	 * @param callable(Result $result): mixed $callback
	 */
	public function __construct(callable $callback)
	{
		$this->callback = $callback;
	}

	/**
	 * @return mixed
	 */
	public function render(Result $result)
	{
		return call_user_func($this->callback, $result);
	}

}
