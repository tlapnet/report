<?php

namespace Tests\Cases\Renderers;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Model\Result\Result;
use Tlapnet\Report\Renderers\JsonRenderer;

final class JsonRendererTest extends BaseTestCase
{

	/**
	 * @covers JsonRenderer::render
	 * @return void
	 */
	public function testDefault()
	{
		$r = new JsonRenderer();
		$this->assertEquals('[{"foo":"bar"}]', $r->render(new Result([['foo' => 'bar']])));
		$this->assertEquals('[{"foo":"bar","foobar":"1"}]', $r->render(new Result([['foo' => 'bar', 'foobar' => 1]])));
	}

}
