<?php

namespace Tlapnet\Report\Model\Parameters\Parameter;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Utils\Suggestions;

abstract class Parameter
{

	// Types
	const TYPE_TEXT = 'text';
	const TYPE_SELECT = 'select';

	/** @var string */
	protected $type;

	/** @var string */
	protected $title;

	/** @var mixed */
	protected $value;

	/** @var mixed */
	protected $rawValue;

	/** @var mixed */
	protected $defaultValue;

	/** @var array */
	protected $options = [];

	/** @var string */
	private $name;

	/**
	 * @param string $name
	 * @param string $type
	 */
	public function __construct($name, $type)
	{
		$this->name = $name;
		$this->type = $type;
	}

	/**
	 * GETTERS / SETTERS *******************************************************
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
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title)
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
	 * @return void
	 */
	public function setValue($value)
	{
		// Store original value
		$this->rawValue = $value;

		// Remove spaces
		$value = trim($value);

		// Escape possible invalid data
		$value = htmlspecialchars($value);

		// Set only non-null and non-empty values
		if (strlen($value) > 0 && $value !== NULL) {
			$this->value = $value;
		}
	}

	/**
	 * @return bool
	 */
	public function hasValue()
	{
		return !empty($this->value) && $this->value !== NULL;
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
	 * @return void
	 */
	public function setDefaultValue($value)
	{
		$this->defaultValue = $value;
	}

	/**
	 * @param string $key
	 * @return mixed
	 */
	public function getOption($key)
	{
		if (isset($this->options[$key])) {
			return $this->options[$key];
		}

		$hint = Suggestions::getSuggestion(array_keys($this->options), $key);
		throw new InvalidArgumentException(Suggestions::format(sprintf('Unknown option "%s"', $key), $hint));
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasOption($key)
	{
		return isset($this->options[$key]);
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function setOption($key, $value)
	{
		$this->options[$key] = $value;
	}

	/**
	 * @param array $options
	 * @return void
	 */
	public function setOptions(array $options)
	{
		$this->options = $options;
	}

}
