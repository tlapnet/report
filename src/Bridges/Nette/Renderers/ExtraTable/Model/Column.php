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
		'sortable' => TRUE,
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
	 * @param bool $sortable
	 * @return self
	 */
	public function sortable($sortable)
	{
		$this->options['sortable'] = boolval($sortable);

		return $this;
	}

	/**
	 * @param string $destination
	 * @param array $args
	 * @return self
	 */
	public function link($destination, array $args = [])
	{
		$this->link = (object) [
			'destination' => $destination,
			'args' => $args,
		];

		return $this;
	}

	/**
	 * @param string $url
	 * @return self
	 */
	public function url($url)
	{
		$this->url = (object) [
			'url' => $url,
		];

		return $this;
	}

	/**
	 * @param string $label
	 * @return self
	 */
	public function label($label)
	{
		$this->label = (object) [
			'name' => $label,
		];

		return $this;
	}

	/**
	 * @param callable $callback
	 * @return self
	 */
	public function callback(callable $callback)
	{
		$this->callback = $callback;

		return $this;
	}

}
