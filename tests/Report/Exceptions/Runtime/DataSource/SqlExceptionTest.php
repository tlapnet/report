<?php

namespace Tlapnet\Report\Tests\Exceptions\Runtime\DataSource;

use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;
use Tlapnet\Report\Tests\BaseTestCase;

final class SqlExceptionTest extends BaseTestCase
{

	public function testDefault()
	{
		$sql = 'SELECT * FROM foo';
		$base = new \Exception('Error during foobar');
		$e = new SqlException($sql, 0, $base);

		$this->assertEquals($sql, $e->getSql());
		$this->assertEquals('Error: Error during foobar', $e->getMessage());
	}

}
