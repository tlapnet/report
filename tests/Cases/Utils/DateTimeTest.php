<?php declare(strict_types = 1);

namespace Tests\Cases\Utils;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Utils\DateTime;

final class DateTimeTest extends BaseTestCase
{

	public function testFrom(): void
	{
		$this->assertEquals('01.01.2000', DateTime::from('1.1.2000')->format('d.m.Y'));
		$this->assertEquals('01.01.2000', DateTime::from(strtotime('1.1.2000'))->format('d.m.Y'));
	}

	public function testFormat(): void
	{
		$dt = DateTime::from('20.01.2000 10:11:12');
		$this->assertEquals('Y-m-d H:i:s', $dt->getFormat());
		$this->assertEquals('2000-01-20 10:11:12', $dt->__toString());

		$dt->setFormat('Y-m-d');
		$this->assertEquals('Y-m-d', $dt->getFormat());
		$this->assertEquals('2000-01-20', $dt->__toString());
	}

}
