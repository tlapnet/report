<?php declare(strict_types = 1);

namespace Tlapnet\Report\Export\Impl;

use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI\Presenter;
use Tlapnet\Report\Export\AbstractExport;

class JsonExport extends AbstractExport
{

	public function send(Presenter $presenter): void
	{
		$presenter->sendResponse(new JsonResponse($this->data));
		$presenter->terminate();
	}

}
