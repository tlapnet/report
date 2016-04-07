<?php

namespace Tlapnet\Report\Bridges\Nette\Components\Render;

use Nette\Application\UI\Control;
use Nette\Application\UI\Multiplier;
use Tlapnet\Report\Model\Group\Group;

class GroupRenderControl extends Control
{

	/** @var Group */
	private $group;

	/** @var array */
	private $boxes = [];

	/**
	 * @param Group $group
	 */
	public function __construct(Group $group)
	{
		parent::__construct();
		$this->group = $group;
	}

	/**
	 * @return Multiplier|BoxRenderControl[]
	 */
	protected function createComponentBox()
	{
		return new Multiplier(function ($bid) {
			$box = $this->group->getBox($bid);

			return new BoxRenderControl($box);
		});
	}

	/**
	 * Render group
	 */
	public function render()
	{
		// Set template
		$this->template->setFile(__DIR__ . '/templates/group.latte');

		// Render
		$this->template->group = $this->group;
		$this->template->render();
	}

}
