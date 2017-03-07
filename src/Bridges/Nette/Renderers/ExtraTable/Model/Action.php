<?php

namespace Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\Model;

class Action extends Component
{

	/** @var object */
	private $link;

	/** @var object */
	private $label;

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
	 * @param string $label
	 * @return void
	 */
	public function label($label)
	{
		$this->label = (object) [
			'name' => $label,
		];
	}

}
