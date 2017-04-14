<?php

namespace Tlapnet\Report\Bridges\Tracy\Export;

use Nette\Application\UI\Presenter;
use Tlapnet\Report\Bridges\Nette\UI\Response\DebugResponse;
use Tlapnet\Report\Model\Export\Exportable;
use Tlapnet\Report\Model\Result\Resultable;

class DebugExport implements Exportable
{

	/** @var Resultable */
	private $resultable;

	/**
	 * @param Resultable $resultable
	 */
	public function __construct(Resultable $resultable)
	{
		$this->resultable = $resultable;
	}

	/**
	 * @param Presenter $presenter
	 * @return void
	 */
	public function send(Presenter $presenter)
	{
		$presenter->sendResponse(new DebugResponse($this->resultable));
		$presenter->terminate();
	}

}
