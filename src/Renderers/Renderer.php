<?php declare(strict_types = 1);

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Result\Result;

interface Renderer
{

	/**
	 * @return mixed
	 */
	public function render(Result $result);

}
