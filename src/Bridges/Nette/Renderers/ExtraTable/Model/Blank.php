<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\Model;

class Blank extends Component
{

	/** @var callable|null */
	private $callback;

	public function getCallback(): ?callable
	{
		return $this->callback;
	}

	public function callback(callable $callback): void
	{
		$this->callback = $callback;
	}

}
