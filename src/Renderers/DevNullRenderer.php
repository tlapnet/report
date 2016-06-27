<?php

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Model\Result\Result;

final class DevNullRenderer implements Renderer
{

	/**
	 * @param Result $result
	 * @return NULL
	 */
	public function render(Result $result)
	{
		return NULL;
	}

}
