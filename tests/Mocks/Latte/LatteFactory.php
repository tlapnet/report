<?php

namespace Tests\Mocks\Latte;

use Latte\Engine;
use Nette\Bridges\ApplicationLatte\ILatteFactory;

class LatteFactory implements ILatteFactory
{

	/** @var array */
	protected $onCreate = [];

	/**
	 * @return Engine
	 */
	public function create()
	{
		$latte = new Engine();
		foreach ($this->onCreate as $callback) {
			$callback($latte);
		}

		return $latte;
	}

	/**
	 * @param callable $callback
	 * @return void
	 */
	public function onCreate(callable $callback)
	{
		$this->onCreate[] = $callback;
	}

}
