<?php

namespace Tests\Mocks\Latte;

use Latte\Engine;
use Nette\Bridges\ApplicationLatte\ILatteFactory;

final class LatteFactory implements ILatteFactory
{

	/**
	 * @return Engine
	 */
	public function create()
	{
		$latte = new Engine();

		return $latte;
	}

}
