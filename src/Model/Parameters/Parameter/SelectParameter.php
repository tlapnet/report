<?php

namespace Tlapnet\Report\Model\Parameters\Parameter;

final class SelectParameter extends Parameter
{

	/** @var array */
	private $items = [];

	/**
	 * @param string $name
	 */
	public function __construct($name)
	{
		parent::__construct($name, Parameter::TYPE_SELECT);
	}

	/**
	 * GETTERS / SETTERS *******************************************************
	 */

	/**
	 * @return array
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * @param array $items
	 */
	public function setItems(array $items)
	{
		$this->items = $items;
	}

	/**
	 * @param string $value
	 */
	public function setValue($value)
	{
		$value = trim($value);

		// Set value only be his key
		if (isset($this->items[$value])) {
			$this->value = $this->items[$value];
		}
	}

}
