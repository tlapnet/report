<?php declare(strict_types = 1);

namespace Tests\Cases\Result;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Result\Helpers;
use Tlapnet\Report\Result\Result;

final class HelpersTest extends BaseTestCase
{

	public function testToArray1(): void
	{
		$data = [1, 2, 3, 4];
		$r = new Result($data);
		$array = Helpers::toArray($r);

		$this->assertEquals($data, $array);
	}

	public function testToArray2(): void
	{
		$data = [['foo'], ['bar']];
		$r = new Result($data);
		$array = Helpers::toArray($r);

		$this->assertEquals($data, $array);
	}

}
