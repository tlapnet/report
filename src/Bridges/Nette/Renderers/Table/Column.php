<?php

namespace Tlapnet\Report\Bridges\Nette\Renderers\Table;

class Column
{

	/** @var string */
	private $name;

	/** @var string */
	private $title;

	/** @var array */
	private $options = [
		'align' => NULL,
		'type' => NULL,
	];

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

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @return object
	 */
	public function getOptions()
	{
		return (object)$this->options;
	}

	/**
	 * BUILDER *****************************************************************
	 */

	/**
	 * @param string $title
	 * @return self
	 */
	public function title($title)
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * @param string $type
	 * @return self
	 */
	public function type($type)
	{
		$this->options['type'] = $type;

		return $this;
	}

	/**
	 * @param string $align
	 * @return self
	 */
	public function align($align)
	{
		$this->options['align'] = $align;

		return $this;
	}

}