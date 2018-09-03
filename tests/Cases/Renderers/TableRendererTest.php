<?php declare(strict_types = 1);

namespace Tests\Cases\Renderers;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Renderers\TableRenderer;
use Tlapnet\Report\Result\Result;

final class TableRendererTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$r = new TableRenderer();
		$this->assertEquals('<table class="table"><tr><th>foo</th></tr><tr><td>bar</td></tr></table>', (string) $r->render(new Result([['foo' => 'bar']])));
		$this->assertEquals('<table class="table"><tr><th>foo</th><th>foobar</th></tr><tr><td>bar</td><td>1</td></tr></table>', (string) $r->render(new Result([['foo' => 'bar', 'foobar' => 1]])));
	}

}
