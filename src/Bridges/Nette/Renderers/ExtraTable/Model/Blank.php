<?php

namespace Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\Model;

class Blank extends Component
{

	/** @var callable */
	private $callback;

	/**
	 * GETTERS *****************************************************************
	 */

	/**
	 * @return callable
	 */
	public function getCallback()
	{
		return $this->callback;
	}

	/**
	 * BUILDER *****************************************************************
	 */

	/**
	 * @param callable $callback
	 * @return void
	 */
	public function callback(callable $callback)
	{
		$this->callback = $callback;
	}

}
