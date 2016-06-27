<?php

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Model\Result\Result;

interface Renderer
{

	/**
	 * @param Result $result
	 * @return mixed
	 */
	public function render(Result $result);

}
