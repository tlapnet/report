<?php declare(strict_types = 1);

namespace Tests\Cases\DataSources;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\DataSources\DevNullDataSource;
use Tlapnet\Report\Parameters\Parameters;

final class DevNullDataSourceTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$ds = new DevNullDataSource();
		$this->assertSame([], $ds->compile(new Parameters())->getData());
	}

}
