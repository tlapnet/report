<?php

namespace Tlapnet\Report\Model\Parameters\Parameter;

final class SelectParameter extends Parameter
{

	/** @var array */
	private $items = [];

	/** @var bool */
	private $useKeys = TRUE;

	/** @var string */
	private $prompt;

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
	 * @return void
	 */
	public function setItems(array $items)
	{
		$this->items = $items;
	}

	/**
	 * @param string $value
	 * @return void
	 */
	public function setValue($value)
	{
		$value = trim($value);

		if ($this->useKeys === TRUE) {
			// Set value representing as key
			if (array_key_exists($value, $this->items)) {
				$this->value = $value;
			}
		} else {
			// Set value representing by his key
			if (isset($this->items[$value])) {
				$this->value = $this->items[$value];
			}
		}
	}

	/**
	 * @param bool $use
	 * @return void
	 */
	public function setUseKeys($use)
	{
		$this->useKeys = boolval($use);
	}

	/**
	 * @return boolean
	 */
	public function isUseKeys()
	{
		return $this->useKeys;
	}

	/**
	 * @return string
	 */
	public function getPrompt()
	{
		return $this->prompt;
	}

	/**
	 * @param string $prompt
	 * @return void
	 */
	public function setPrompt($prompt)
	{
		$this->prompt = $prompt;
	}

}
