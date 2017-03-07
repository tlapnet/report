<?php

namespace Tests\Cases\Renderers;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Model\Result\Result;
use Tlapnet\Report\Renderers\DevNullRenderer;

final class DevNullRendererTest extends BaseTestCase
{

	/**
	 * @covers DevNullRenderer::render
	 * @return void
	 */
	public function testDefault()
	{
		$r = new DevNullRenderer();
		$this->assertNull($r->render(new Result([])));
	}

}
