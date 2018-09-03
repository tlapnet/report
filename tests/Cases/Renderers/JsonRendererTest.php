<?php declare(strict_types = 1);

namespace Tests\Cases\Renderers;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Renderers\JsonRenderer;
use Tlapnet\Report\Result\Result;

final class JsonRendererTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$r = new JsonRenderer();
		$this->assertEquals('[{"foo":"bar"}]', $r->render(new Result([['foo' => 'bar']])));
		$this->assertEquals('[{"foo":"bar","foobar":"1"}]', $r->render(new Result([['foo' => 'bar', 'foobar' => 1]])));
	}

}
