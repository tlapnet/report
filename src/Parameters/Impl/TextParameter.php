<?php declare(strict_types = 1);

namespace Tlapnet\Report\Parameters\Impl;

use Tlapnet\Report\Exceptions\Logic\InvalidStateException;

final class TextParameter extends Parameter
{

	public function __construct(string $name)
	{
		parent::__construct($name, Parameter::TYPE_TEXT);
	}

	public function canProvide(): bool
	{
		return $this->hasValue() || $this->hasDefaultValue();
	}

	/**
	 * @return mixed
	 */
	public function getProvidedValue()
	{
		if ($this->value !== null) return $this->value;
		if ($this->defaultValue !== null) return $this->defaultValue;

		throw new InvalidStateException('Cannot provide value');
	}

}
