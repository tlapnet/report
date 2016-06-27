<?php

namespace Tlapnet\Report\Tests\Utils;

use Tlapnet\Report\Tests\BaseTestCase;
use Tlapnet\Report\Utils\Expander;

final class ExpanderTest extends BaseTestCase
{

	public function testStr()
	{
		$params = ['foo' => 'bar'];

		$expander = new Expander($params);
		$this->assertEquals('example/bar', $expander->doSingle('example/{foo}'));
	}

	public function testArr()
	{
		$params = ['foo' => 'bar'];

		$expander = new Expander($params);
		$this->assertEquals(['a' => 'bar', 'bar' => 'b'], $expander->doArray(['a' => '{foo}', '{foo}' => 'b']));
	}

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
