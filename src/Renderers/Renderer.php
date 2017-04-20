<?php

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Result\Result;

interface Renderer
{

	/**
	 * @param Result $result
	 * @return mixed
	 */
	public function render(Result $result);

}
