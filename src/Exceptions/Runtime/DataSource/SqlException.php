<?php

namespace Tlapnet\Report\Exceptions\Runtime\DataSource;

use Exception;
use Tlapnet\Report\Exceptions\Runtime\CompileException;

class SqlException extends CompileException
{

	/** @var string */
	private $sql;

	/**
	 * @param string $sql
	 * @param int $code
	 * @param Exception $previous
	 */
	public function __construct($sql, $code, Exception $previous)
	{
		parent::__construct('Invalid SQL', $code, $previous);
		$this->sql = $sql;
	}

	/**
	 * @return string
	 */
	public function getSql()
	{
		return $this->sql;
	}
	
}