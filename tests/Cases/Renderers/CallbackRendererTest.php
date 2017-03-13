<?php

namespace Tests\Cases\Renderers;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Model\Result\Result;
use Tlapnet\Report\Renderers\CallbackRenderer;

final class CallbackRendererTest extends BaseTestCase
{

	/**
	 * @covers CallbackRenderer::render
	 * @return void
	 */
	public function testDefault()
	{
		$result = new Result([]);
		$r = new CallbackRenderer(function (Result $inner) use ($result) {
			$this->assertSame($result, $inner);

			return 1;
		});

		$this->assertEquals(1, $r->render($result));
	}

}