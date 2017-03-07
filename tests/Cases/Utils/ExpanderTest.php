<?php

namespace Tests\Cases\Utils;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Utils\Expander;

final class ExpanderTest extends BaseTestCase
{

	/**
	 * @covers Expander::doSingle
	 * @return void
	 */
	public function testStr()
	{
		$params = ['foo' => 'bar'];

		$expander = new Expander($params);
		$this->assertEquals('example/bar', $expander->doSingle('example/{foo}'));
	}

	/**
	 * @covers Expander::doArray
	 * @return void
	 */
	public function testArr()
	{
		$params = ['foo' => 'bar'];

		$expander = new Expander($params);
		$this->assertEquals(['a' => 'bar', 'bar' => 'b'], $expander->doArray(['a' => '{foo}', '{foo}' => 'b']));
	}

	/**
	 * @covers Expander::execute
	 * @return void
	 */
	public function testExpand()
	{
		$params = ['foo' => 'bar'];

		$expander = new Expander($params);
		$this->assertEquals('example/bar', $expander->execute('example/{foo}'));
		$this->assertEquals(['a' => 'bar', 'bar' => 'b'], $expander->execute(['a' => '{foo}', '{foo}' => 'b']));

		// Unsupported type
		$stdClass = new \stdClass();
		$this->assertEquals($stdClass, $expander->execute($stdClass));
	}

}
