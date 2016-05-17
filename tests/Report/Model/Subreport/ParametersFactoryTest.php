<?php

namespace Tlapnet\Report\Tests\Model\Subreport;

use Tlapnet\Report\Model\Subreport\ParametersFactory;
use Tlapnet\Report\Tests\BaseTestCase;

final class ParametersFactoryTest extends BaseTestCase
{

	public function testDefault()
	{
		$p = ParametersFactory::create([]);

		$this->assertEmpty($p->getAll());
	}

}
