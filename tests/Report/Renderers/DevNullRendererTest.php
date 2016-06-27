<?php

namespace Tlapnet\Report\Tests\Renderers;

use Tlapnet\Report\Model\Result\Result;
use Tlapnet\Report\Renderers\DevNullRenderer;
use Tlapnet\Report\Tests\BaseTestCase;

final class DevNullRendererTest extends BaseTestCase
{

	public function testDefault()
	{
		$r = new DevNullRenderer();
		$this->assertNull($r->render(new Result([])));
	}

}
