<?php declare(strict_types = 1);

namespace Tests\Cases\Parameters;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Parameters\ParametersFactory;

final class ParametersFactoryTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$p = ParametersFactory::create([]);

		$this->assertEmpty($p->getAll());
	}

}
