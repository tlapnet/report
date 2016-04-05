<?php

namespace Tlapnet\Report\Tests\Utils;

use Tlapnet\Report\Tests\BaseTestCase;
use Tlapnet\Report\Utils\Expander;

final class ExpanderTest extends BaseTestCase
{

	public function testStr()
	{
		$params = [
			'foo' => 'bar',
		];

		$expander = new Expander($params);
		$this->assertEquals('example/bar', $expander->str('example/%foo%'));
	}

	public function testArr()
	{
		$params = [
			'foo' => 'bar',
		];

		$expander = new Expander($params);
		$this->assertEquals(['a' => 'bar', 'bar' => 'b'], $expander->arr(['a' => '%foo%', '%foo%' => 'b']));
	}

	public function testExpand()
	{
		$params = [
			'foo' => 'bar',
		];

		$expander = new Expander($params);
		$this->assertEquals('example/bar', $expander->expand('example/%foo%'));
		$this->assertEquals(['a' => 'bar', 'bar' => 'b'], $expander->expand(['a' => '%foo%', '%foo%' => 'b']));
	}

}
