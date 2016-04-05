<?php

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Heap\Heap;

interface Renderer
{

	/**
	 * @param Heap $heap
	 * @return mixed
	 */
	public function render(Heap $heap);

}
