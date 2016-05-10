<?php

namespace Tlapnet\Report\Tests\Utils;

use Tlapnet\Report\Tests\BaseTestCase;
use Tlapnet\Report\Utils\DateTime;

final class DateTimeTest extends BaseTestCase
{

	public function testFrom()
	{
		$this->assertEquals('01.01.2000', DateTime::from('1.1.2000')->format('d.m.Y'));
		$this->assertEquals('01.01.2000', DateTime::from(strtotime('1.1.2000'))->format('d.m.Y'));
	}

}
