<?php declare(strict_types = 1);

namespace Tests\Cases\Utils;

use stdClass;
use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Utils\Switcher;

final class SwitcherTest extends BaseTestCase
{

	public function testSwitch(): void
	{
		// One item
		$params = ['foo' => 'bar'];
		$expander = new Switcher($params);
		$this->assertEquals(['example/?', [0 => 'bar']], $expander->execute('example/{foo}'));

		// More items
		$params = ['foo' => 'bar', 'a' => 'foobar', 'b' => 2];
		$expander = new Switcher($params);
		$this->assertEquals(
			[
				'example/?/?/?',
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
		$stdClass = new stdClass();
		$this->assertEquals($stdClass, $expander->execute($stdClass));
	}

}
