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
	private $name;

	/** @var string */
	private $type;

	/** @var string */
	private $title;

	/** @var mixed */
	private $value;

	/** @var array */
	private $options = [];

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
	 */
	public function setValue($value)
	{
		$this->value = $value;
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
		throw new InvalidArgumentException("Unknown option '$key'" . ($hint ? ", did you mean '$hint'?" : '.'));
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
	 */
	public function setOption($key, $value)
	{
		$this->options[$key] = $value;
	}

}
