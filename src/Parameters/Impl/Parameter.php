<?php declare(strict_types = 1);

namespace Tlapnet\Report\Parameters\Impl;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Utils\Suggestions;

abstract class Parameter
{

	// Types
	public const TYPE_TEXT = 'text';
	public const TYPE_SELECT = 'select';

	/** @var string */
	protected $type;

	/** @var string|null */
	protected $title;

	/** @var mixed */
	protected $value;

	/** @var mixed */
	protected $rawValue;

	/** @var mixed */
	protected $defaultValue;

	/** @var mixed[] */
	protected $options = [];

	/** @var string */
	private $name;

	public function __construct(string $name, string $type)
	{
		$this->name = $name;
		$this->type = $type;
	}

	abstract public function canProvide(): bool;

	/**
	 * @return mixed
	 */
	abstract public function getProvidedValue();

	public function getName(): string
	{
		return $this->name;
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function setTitle(string $title): void
	{
		$this->title = $title;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value): void
	{
		// Store original value
		$this->rawValue = $value;

		if (is_string($value)) {
			// Remove spaces
			$value = trim($value);

			// Escape possible invalid data
			$value = htmlspecialchars($value);
		}

		// Set only non-empty values
		if (!in_array($value, ['', []], true)) {
			$this->value = $value;
		}
	}

	public function hasValue(): bool
	{
		return $this->value !== null;
	}

	/**
	 * @return mixed
	 */
	public function getRawValue()
	{
		return $this->rawValue;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultValue()
	{
		return $this->defaultValue;
	}

	/**
	 * @param mixed $value
	 */
	public function setDefaultValue($value): void
	{
		$this->defaultValue = $value;
	}

	public function hasDefaultValue(): bool
	{
		return $this->defaultValue !== null;
	}

	/**
	 * @return mixed
	 */
	public function getOption(string $key)
	{
		if (isset($this->options[$key])) {
			return $this->options[$key];
		}

		$hint = Suggestions::getSuggestion(array_keys($this->options), $key);
		throw new InvalidArgumentException(Suggestions::format(sprintf('Unknown option "%s"', $key), $hint));
	}

	public function hasOption(string $key): bool
	{
		return isset($this->options[$key]);
	}

	/**
	 * @param mixed $value
	 */
	public function setOption(string $key, $value): void
	{
		$this->options[$key] = $value;
	}

	/**
	 * @param mixed[] $options
	 */
	public function setOptions(array $options): void
	{
		$this->options = $options;
	}

}
