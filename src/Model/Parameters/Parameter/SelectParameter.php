<?php

namespace Tlapnet\Report\Model\Parameters\Parameter;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Utils\Arrays;

final class SelectParameter extends Parameter
{

	/** @var array */
	private $items = [];

	/** @var bool */
	private $useKeys = TRUE;

	/** @var bool|null */
	private $autoPick;

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
	 * ABSTRACT ****************************************************************
	 */

	/**
	 * @return bool
	 */
	public function canProvide()
	{
		return $this->hasDefaultValue() || $this->getAutoPick() === TRUE;
	}

	/**
	 * @return mixed
	 */
	public function getProvidedValue()
	{
		if ($this->value) return $this->value;
		if ($this->defaultValue) return $this->defaultValue;
		if ($this->autoPick) return Arrays::shift($this->items);

		throw new InvalidStateException('Cannot provide value');
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
			} else {
				throw new InvalidArgumentException(sprintf('Key "%s" not found in array [%s] (useKeys:on)', $value, implode('|', $this->items)));
			}
		} else {
			// Set value representing by his key
			if (in_array($value, $this->items)) {
				$this->value = $value;
			} else {
				throw new InvalidArgumentException(sprintf('Key "%s" not found in array [%s] (useKeys:off)', $value, implode('|', $this->items)));
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

	/**
	 * @return bool
	 */
	public function hasPrompt()
	{
		return $this->prompt !== NULL;
	}

	/**
	 * @return bool|null
	 */
	public function getAutoPick()
	{
		return $this->autoPick;
	}

	/**
	 * @param bool|null $auto
	 * @return void
	 */
	public function setAutoPick($auto)
	{
		$this->autoPick = $auto === NULL ? $auto : boolval($auto);
	}

}
