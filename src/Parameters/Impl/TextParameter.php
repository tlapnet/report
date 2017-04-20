<?php

namespace Tlapnet\Report\Parameters\Impl;

use Tlapnet\Report\Exceptions\Logic\InvalidStateException;

final class TextParameter extends Parameter
{

	/**
	 * @param string $name
	 */
	public function __construct($name)
	{
		parent::__construct($name, Parameter::TYPE_TEXT);
	}

	/**
	 * ABSTRACT ****************************************************************
	 */

	/**
	 * @return bool
	 */
	public function canProvide()
	{
		return $this->hasValue() || $this->hasDefaultValue();
	}

	/**
	 * @return mixed
	 */
	public function getProvidedValue()
	{
		if ($this->value) return $this->value;
		if ($this->defaultValue) return $this->defaultValue;

		throw new InvalidStateException('Cannot provide value');
	}

}
