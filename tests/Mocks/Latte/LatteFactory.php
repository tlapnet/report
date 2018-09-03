<?php declare(strict_types = 1);

namespace Tests\Mocks\Latte;

use Latte\Engine;
use Nette\Bridges\ApplicationLatte\ILatteFactory;

class LatteFactory implements ILatteFactory
{

	/** @var callable[] function(Engine $engine): void */
	protected $onCreate = [];

	public function create(): Engine
	{
		$latte = new Engine();
		foreach ($this->onCreate as $callback) {
			$callback($latte);
		}

		return $latte;
	}

	public function onCreate(callable $callback): void
	{
		$this->onCreate[] = $callback;
	}

}
