<?php declare(strict_types = 1);

namespace Tlapnet\Report\Export;

use Nette\Application\UI\Presenter;

interface Exportable
{

	public function send(Presenter $presenter): void;

}
