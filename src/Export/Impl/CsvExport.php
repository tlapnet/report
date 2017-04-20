<?php

namespace Tlapnet\Report\Export\Impl;

use Nette\Application\UI\Presenter;
use Tlapnet\Report\Bridges\Nette\UI\Response\CsvResponse;
use Tlapnet\Report\Export\AbstractExport;

class CsvExport extends AbstractExport
{

	/**
	 * @param Presenter $presenter
	 * @return void
	 */
	public function send(Presenter $presenter)
	{
		$presenter->sendResponse(new CsvResponse($this->data));
		$presenter->terminate();
	}

}
