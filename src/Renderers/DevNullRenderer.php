<?php declare(strict_types = 1);

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Result\Result;

final class DevNullRenderer implements Renderer
{

	/**
	 * @return null
	 */
	public function render(Result $result)
	{
		return null;
	}

}
