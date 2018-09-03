<?php declare(strict_types = 1);

namespace Tlapnet\Report\Export\Impl;

use Nette\Application\UI\Presenter;
use Tlapnet\Report\Bridges\Nette\UI\Response\CsvResponse;
use Tlapnet\Report\Export\AbstractExport;

class CsvExport extends AbstractExport
{

	public function send(Presenter $presenter): void
	{
		$presenter->sendResponse(new CsvResponse($this->data));
		$presenter->terminate();
	}

}
