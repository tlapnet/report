<?php

namespace Tests\Cases\Renderers;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Model\Result\Result;
use Tlapnet\Report\Renderers\DummyRenderer;

final class DummyRendererTest extends BaseTestCase
{

	/**
	 * @covers DummyRenderer::render
	 * @return void
	 */
	public function testDefault()
	{
		$result = new Result([]);
		$r = new DummyRenderer();
		$this->assertSame($result, $r->render($result));
	}

}
