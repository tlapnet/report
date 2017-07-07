<?php

namespace Tests\Cases\Utils;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Utils\Arrays;

final class ArraysTest extends BaseTestCase
{

	/**
	 * @covers Arrays::pop
	 * @return void
	 */
	public function testPop()
	{
		$this->assertEquals('bar', Arrays::pop(['foo', 'bar']));
	}

	/**
	 * @covers Arrays::shift
	 * @return void
	 */
	public function testShift()
	{
		$this->assertEquals('foo', Arrays::shift(['foo', 'bar']));
	}

}
