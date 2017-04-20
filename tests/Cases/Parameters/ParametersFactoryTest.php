<?php

namespace Tests\Cases\Parameters;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Parameters\ParametersFactory;

final class ParametersFactoryTest extends BaseTestCase
{

	/**
	 * @covers ParametersFactory::create
	 * @covers ParametersFactory::getAll
	 * @return void
	 */
	public function testDefault()
	{
		$p = ParametersFactory::create([]);

		$this->assertEmpty($p->getAll());
	}

}
