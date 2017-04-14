<?php

namespace Tlapnet\Report\Model\Export;

use Nette\Application\UI\Presenter;

interface Exportable
{

	/**
	 * @param Presenter $presenter
	 * @return void
	 */
	public function send(Presenter $presenter);

}
