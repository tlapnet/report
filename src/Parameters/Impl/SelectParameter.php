<?php declare(strict_types = 1);

namespace Tlapnet\Report\Parameters\Impl;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Utils\Arrays;

final class SelectParameter extends Parameter
{

	/** @var mixed[] */
	private $items = [];

	/** @var bool */
	private $useKeys = true;

	/** @var bool */
	private $autoPick = false;

	/** @var string|null */
	private $prompt;

	public function __construct(string $name)
	{
		parent::__construct($name, Parameter::TYPE_SELECT);
	}

	public function canProvide(): bool
	{
		return $this->hasValue() || $this->hasDefaultValue() || $this->getAutoPick() === true;
	}

	/**
	 * @return mixed
	 */
	public function getProvidedValue()
	{
		if ($this->value !== null) return $this->value;
		if ($this->defaultValue !== null) return $this->defaultValue;
		if ($this->autoPick) return Arrays::shift($this->items);

		throw new InvalidStateException('Cannot provide value');
	}

	/**
	 * @return mixed[]
	 */
	public function getItems(): array
	{
		return $this->items;
	}

	/**
	 * @param mixed[] $items
	 */
	public function setItems(array $items): void
	{
		$this->items = $items;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value): void
	{
		$value = trim((string) $value);

		if ($this->useKeys === true) {
			// Set value representing as key
			if (array_key_exists($value, $this->items)) {
				$this->value = $value;
			} else {
				throw new InvalidArgumentException(sprintf('Key "%s" not found in array [%s] (useKeys:on)', $value, implode('|', array_keys($this->items))));
			}
		} else {
			// Set value representing by his key
			if (in_array($value, $this->items, true)) {
				$this->value = $value;
			} else {
				throw new InvalidArgumentException(sprintf('Value "%s" not found in array [%s] (useKeys:off)', $value, implode('|', array_values($this->items))));
			}
		}
	}

	public function setUseKeys(bool $use): void
	{
		$this->useKeys = $use;
	}

	public function isUseKeys(): bool
	{
		return $this->useKeys;
	}

	public function getPrompt(): ?string
	{
		return $this->prompt;
	}

	public function setPrompt(string $prompt): void
	{
		$this->prompt = $prompt;
	}

	public function hasPrompt(): bool
	{
		return $this->prompt !== null;
	}

	public function getAutoPick(): ?bool
	{
		return $this->autoPick;
	}

	public function setAutoPick(bool $auto): void
	{
		$this->autoPick = $auto;
	}

}
