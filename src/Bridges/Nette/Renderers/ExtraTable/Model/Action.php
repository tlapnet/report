<?php

namespace Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\Model;

class Action extends Component
{

	/** @var object */
	private $link;

	/** @var object */
	private $label;

	/** @var string */
	private $title;

	/**
	 * GETTERS *****************************************************************
	 */

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
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
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

}
