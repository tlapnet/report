<?php

namespace Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\Model;

class Column extends Component
{

	/** @var string */
	private $title;

	/** @var object */
	private $link;

	/** @var object */
	private $url;

	/** @var object */
	private $label;

	/** @var callable */
	private $callback;

	/** @var array */
	private $options = [
		'align' => NULL,
		'type' => NULL,
	];

	/**
	 * GETTERS *****************************************************************
	 */

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
		return (object) $this->options;
	}

	/**
	 * @return object
	 */
	public function getLink()
	{
		return $this->link;
	}

	/**
	 * @return object
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @return object
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @return callable
	 */
	public function getCallback()
	{
		return $this->callback;
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

	/**
	 * @param string $destination
	 * @param array $args
	 * @return void
	 */
	public function link($destination, array $args = [])
	{
		$this->link = (object) [
			'destination' => $destination,
			'args' => $args,
		];
	}

	/**
	 * @param string $url
	 * @return void
	 */
	public function url($url)
	{
		$this->url = (object) [
			'url' => $url,
		];
	}

	/**
	 * @param string $label
	 * @return void
	 */
	public function label($label)
	{
		$this->label = (object) [
			'name' => $label,
		];
	}

	/**
	 * @param callable $callback
	 * @return void
	 */
	public function callback(callable $callback)
	{
		$this->callback = $callback;
	}

}
