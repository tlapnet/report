<?php

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Model\Result\Result;

final class CallbackRenderer implements Renderer
{

	/** @var callable */
	private $callback;

	/**
	 * @param callable $callback
	 */
	public function __construct($callback)
	{
		$this->callback = $callback;
	}

	/**
	 * RENDERING ***************************************************************
	 */

	/**
	 * @param Result $result
	 * @return mixed
	 */
	public function render(Result $result)
	{
		return call_user_func($this->callback, $result);
	}

}
