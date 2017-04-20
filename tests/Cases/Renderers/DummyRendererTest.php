<?php

namespace Tests\Cases\Renderers;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Renderers\DummyRenderer;
use Tlapnet\Report\Result\Result;

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
