<?php

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Model\Data\Result;

final class DummyRenderer implements Renderer
{

	/**
	 * @param Result $result
	 * @return Result
	 */
	public function render(Result $result)
	{
		return $result;
	}

}
