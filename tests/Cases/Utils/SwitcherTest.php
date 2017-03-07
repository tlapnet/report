<?php

namespace Tests\Cases\Utils;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Utils\Switcher;

final class SwitcherTest extends BaseTestCase
{

	/**
	 * @covers Switcher::execute
	 * @return void
	 */
	public function testSwitch()
	{
		// One item
		$params = ['foo' => 'bar'];
		$expander = new Switcher($params);
		$this->assertEquals(['example/?', [0 => 'bar']], $expander->execute('example/{foo}'));

		// More items
		$params = ['foo' => 'bar', 'a' => 'foobar', 'b' => 2];
		$expander = new Switcher($params);
		$this->assertEquals(
			['example/?/?/?',
				[
					0 => 'bar',
					1 => 2,
					2 => 'foobar',
				],
			],
			$expander->execute('example/{foo}/{b}/{a}')
		);

		// Not found
		$params = ['foo' => 'bar'];
		$expander = new Switcher($params);
		$this->assertEquals(['example/{foobar}', []], $expander->execute('example/{foobar}'));

		// Unsupported type
		$stdClass = new \stdClass();
		$this->assertEquals($stdClass, $expander->execute($stdClass));
	}

}
