<?php

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Model\Data\Helpers;
use Tlapnet\Report\Model\Data\Result;

class JsonRenderer implements Renderer
{

	/**
	 * @param Result $result
	 * @return string
	 */
	public function render(Result $result)
	{
		$data = Helpers::toArray($result);

		return json_encode($data);
	}

}
