<?php

namespace Tlapnet\Report\Tests\Model\Parameters;

use Tlapnet\Report\Model\Parameters\ParametersFactory;
use Tlapnet\Report\Tests\BaseTestCase;

final class ParametersFactoryTest extends BaseTestCase
{

	public function testDefault()
	{
		$p = ParametersFactory::create([]);

		$this->assertEmpty($p->getAll());
	}

}
