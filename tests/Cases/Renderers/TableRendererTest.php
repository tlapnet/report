<?php

namespace Tests\Cases\Renderers;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Model\Result\Result;
use Tlapnet\Report\Renderers\TableRenderer;

final class TableRendererTest extends BaseTestCase
{

	/**
	 * @covers TableRenderer::render
	 * @return void
	 */
	public function testDefault()
	{
		$r = new TableRenderer();
		$this->assertEquals('<table class="table"><tr><th>foo</th></tr><tr><td>bar</td></tr></table>', (string) $r->render(new Result([['foo' => 'bar']])));
		$this->assertEquals('<table class="table"><tr><th>foo</th><th>foobar</th></tr><tr><td>bar</td><td>1</td></tr></table>', (string) $r->render(new Result([['foo' => 'bar', 'foobar' => 1]])));
	}

}
