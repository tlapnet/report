<?php declare(strict_types = 1);

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Parameters\Parameters;

abstract class AbstractDatabaseDataSource implements DataSource
{

	/** @var string|null */
	protected $sql;

	/** @var string|null */
	protected $defaultSql;

	/** @var bool */
	protected $pure = true;

	public function getSql(): ?string
	{
		return $this->sql;
	}

	public function setSql(string $sql): void
	{
		$this->sql = $sql;
	}

	public function getDefaultSql(): ?string
	{
		return $this->defaultSql;
	}

	public function setDefaultSql(string $sql): void
	{
		$this->defaultSql = $sql;
	}

	public function getRealSql(Parameters $parameters): string
	{
		// If dynamic parameters are not filled
		// and also we've default sql query
		if (!$parameters->isAttached() && $this->getDefaultSql() !== null) {
			$sql = $this->getDefaultSql();
		} else {
			$sql = $this->getSql();
		}

		// Control check - we need any sql query!
		if ($sql === null) {
			throw new InvalidStateException('You have to fill sql query. Via setSql() or setDefaultSql().');
		}

		return $sql;
	}

}
