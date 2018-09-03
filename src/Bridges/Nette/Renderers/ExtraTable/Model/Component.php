<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\Model;

abstract class Component
{

	/** @var string */
	protected $name;

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	public function getName(): string
	{
		return $this->name;
	}

}
