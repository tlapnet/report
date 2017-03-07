<?php

namespace Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\Model;

abstract class Component
{

	/** @var string */
	protected $name;

	/**
	 * @param string $name
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}

	/**
	 * GETTERS *****************************************************************
	 */

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

}
