<?php

namespace Tests\Cases\Utils;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Utils\DateTime;

final class DateTimeTest extends BaseTestCase
{

	/**
	 * @covers DateTime::from
	 * @covers DateTime::format
	 * @return void
	 */
	public function testFrom()
	{
		$this->assertEquals('01.01.2000', DateTime::from('1.1.2000')->format('d.m.Y'));
		$this->assertEquals('01.01.2000', DateTime::from(strtotime('1.1.2000'))->format('d.m.Y'));
	}

}
