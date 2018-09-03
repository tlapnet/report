<?php declare(strict_types = 1);

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Result\Helpers;
use Tlapnet\Report\Result\Result;

class JsonRenderer implements Renderer
{

	public function render(Result $result): string
	{
		$data = Helpers::toArray($result);

		return (string) json_encode($data);
	}

}
