<?php

namespace Tlapnet\Report\Tests\Model\Result;

use Tlapnet\Report\Model\Result\Helpers;
use Tlapnet\Report\Model\Result\Result;
use Tlapnet\Report\Tests\BaseTestCase;

final class HelpersTest extends BaseTestCase
{

	public function testToArray1()
	{
		$data = [1, 2, 3, 4];
		$r = new Result($data);
		$array = Helpers::toArray($r);

		$this->assertEquals($data, $array);
	}

	public function testToArray2()
	{
		$data = [['foo'], ['bar']];
		$r = new Result($data);
		$array = Helpers::toArray($r);

		$this->assertEquals($data, $array);
	}
}
