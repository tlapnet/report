<?php declare(strict_types = 1);

namespace Tlapnet\Report\Exceptions\Runtime\DataSource;

use Throwable;
use Tlapnet\Report\Exceptions\Runtime\CompileException;

class SqlException extends CompileException
{

	/** @var string */
	private $sql;

	public function __construct(string $sql, int $code = 0, ?Throwable $previous = null)
	{
		if ($previous !== null) {
			parent::__construct('Error: ' . $previous->getMessage(), $code, $previous);
		} else {
			parent::__construct('', $code);
		}

		$this->sql = $sql;
	}

	public function getSql(): string
	{
		return $this->sql;
	}

}
