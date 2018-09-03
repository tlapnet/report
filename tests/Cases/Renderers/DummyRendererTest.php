<?php declare(strict_types = 1);

namespace Tests\Cases\Renderers;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Renderers\DummyRenderer;
use Tlapnet\Report\Result\Result;

final class DummyRendererTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$result = new Result([]);
		$r = new DummyRenderer();
		$this->assertSame($result, $r->render($result));
	}

}
