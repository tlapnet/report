<?php declare(strict_types = 1);

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Result\Result;

final class DummyRenderer implements Renderer
{

	public function render(Result $result): Result
	{
		return $result;
	}

}
