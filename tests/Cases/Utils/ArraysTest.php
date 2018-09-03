<?php declare(strict_types = 1);

namespace Tests\Cases\Utils;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Utils\Arrays;

final class ArraysTest extends BaseTestCase
{

	public function testPop(): void
	{
		$this->assertEquals('bar', Arrays::pop(['foo', 'bar']));
	}

	public function testShift(): void
	{
		$this->assertEquals('foo', Arrays::shift(['foo', 'bar']));
	}

}
