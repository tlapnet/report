<?php

namespace Tlapnet\Report\Export\Impl;

use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI\Presenter;
use Tlapnet\Report\Export\AbstractExport;

class JsonExport extends AbstractExport
{

	/**
	 * @param Presenter $presenter
	 * @return void
	 */
	public function send(Presenter $presenter)
	{
		$presenter->sendResponse(new JsonResponse($this->data));
		$presenter->terminate();
	}

}
