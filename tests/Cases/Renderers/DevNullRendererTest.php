<?php declare(strict_types = 1);

namespace Tests\Cases\Renderers;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Renderers\DevNullRenderer;
use Tlapnet\Report\Result\Result;

final class DevNullRendererTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$r = new DevNullRenderer();
		$this->assertNull($r->render(new Result([])));
	}

}
