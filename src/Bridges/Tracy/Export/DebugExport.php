<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Tracy\Export;

use Nette\Application\UI\Presenter;
use Tlapnet\Report\Bridges\Nette\UI\Response\DebugResponse;
use Tlapnet\Report\Export\Exportable;
use Tlapnet\Report\Result\Resultable;

class DebugExport implements Exportable
{

	/** @var Resultable */
	private $resultable;

	public function __construct(Resultable $resultable)
	{
		$this->resultable = $resultable;
	}

	public function send(Presenter $presenter): void
	{
		$presenter->sendResponse(new DebugResponse($this->resultable));
		$presenter->terminate();
	}

}
