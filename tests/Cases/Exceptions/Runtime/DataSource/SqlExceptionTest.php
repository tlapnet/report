<?php declare(strict_types = 1);

namespace Tests\Cases\Exceptions\Runtime\DataSource;

use Exception;
use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;

final class SqlExceptionTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$sql = 'SELECT * FROM foo';
		$base = new Exception('Error during foobar');
		$e = new SqlException($sql, 0, $base);

		$this->assertEquals($sql, $e->getSql());
		$this->assertEquals('Error: Error during foobar', $e->getMessage());
	}

}
