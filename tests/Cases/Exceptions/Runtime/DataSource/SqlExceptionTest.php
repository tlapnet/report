<?php

namespace Tests\Cases\Exceptions\Runtime\DataSource;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;

final class SqlExceptionTest extends BaseTestCase
{

	/**
	 * @covers SqlException::getMessage
	 * @return void
	 */
	public function testDefault()
	{
		$sql = 'SELECT * FROM foo';
		$base = new \Exception('Error during foobar');
		$e = new SqlException($sql, 0, $base);

		$this->assertEquals($sql, $e->getSql());
		$this->assertEquals('Error: Error during foobar', $e->getMessage());
	}

}
