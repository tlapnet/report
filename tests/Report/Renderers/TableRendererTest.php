<?php

namespace Tlapnet\Report\Tests\Renderers;

use Tlapnet\Report\Model\Result\Result;
use Tlapnet\Report\Renderers\TableRenderer;
use Tlapnet\Report\Tests\BaseTestCase;

final class TableRendererTest extends BaseTestCase
{

	public function testDefault()
	{
		$r = new TableRenderer();
		$this->assertEquals('<table class="table"><tr><th>foo</th></tr><tr><td>bar</td></tr></table>', (string) $r->render(new Result([['foo' => 'bar']])));
		$this->assertEquals('<table class="table"><tr><th>foo</th><th>foobar</th></tr><tr><td>bar</td><td>1</td></tr></table>', (string) $r->render(new Result([['foo' => 'bar', 'foobar' => 1]])));
	}

}
