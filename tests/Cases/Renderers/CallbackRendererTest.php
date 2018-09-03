<?php declare(strict_types = 1);

namespace Tests\Cases\Renderers;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Renderers\CallbackRenderer;
use Tlapnet\Report\Result\Result;

final class CallbackRendererTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$result = new Result([]);
		$r = new CallbackRenderer(function (Result $inner) use ($result) {
			$this->assertSame($result, $inner);

			return 1;
		});

		$this->assertEquals(1, $r->render($result));
	}

}
