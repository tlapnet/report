<?php

namespace Tlapnet\Report\Tests\Renderers;

use Tlapnet\Report\Model\Data\Result;
use Tlapnet\Report\Renderers\DummyRenderer;
use Tlapnet\Report\Tests\BaseTestCase;

final class DummyRendererTest extends BaseTestCase
{

	public function testDefault()
	{
		$result = new Result([]);
		$r = new DummyRenderer();
		$this->assertSame($result, $r->render($result));
	}

}
