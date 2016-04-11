<?php

namespace Tlapnet\Report\Bridges\Nette\Utils\DataSource;

use Nette\Utils\Random;
use Tlapnet\Report\DataSources\DataSource;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Model\Subreport\ParameterList;
use Tlapnet\Report\Model\Data\Result;

class RandomDataSource implements DataSource
{

	/** Types */
	const TYPE_INT = 'int';
	const TYPE_RANGE = 'range';
	const TYPE_STRING = 'string';

	/** @var array */
	protected $columns = [];

	/** @var int */
	protected $rows = 100;

	/**
	 * @param string $name
	 * @param array $options
	 */
	protected function addColumn($name, array $options)
	{
		if (isset($this->columns[$name])) {
			throw new InvalidStateException("Column '$name' already exists'");
		}

		$column = ['name' => $name] + $options;
		$this->columns[$name] = (object)$column;
	}

	/**
	 * @param string $name
	 * @param int $length
	 */
	public function addString($name, $length = 20)
	{
		$this->addColumn($name, [
			'type' => self::TYPE_STRING,
			'length' => $length,
		]);
	}

	/**
	 * @param string $name
	 * @param int $lenght
	 */
	public function addInt($name, $length = 10)
	{
		$this->addColumn($name, [
			'type' => self::TYPE_INT,
			'length' => $length,
		]);
	}

	/**
	 * @param string $name
	 * @param int $from
	 * @param int $to
	 */
	public function addRange($name, $from, $to)
	{
		$this->addColumn($name, [
			'type' => self::TYPE_RANGE,
			'range' => [$from, $to],
		]);
	}

	/**
	 * @param int $rows
	 */
	public function setRows($rows)
	{
		$this->rows = $rows;
	}

	/**
	 * COMPILING ***************************************************************
	 */

	/**
	 * @param ParameterList $parameters
	 * @return Result
	 */
	public function compile(ParameterList $parameters)
	{
		$data = [];

		for ($i = 1; $i <= $this->rows; $i++) {
			$row = [];
			foreach ($this->columns as $column) {
				$row[$column->name] = $this->generate($column);
			}

			$data[] = $row;
		}

		$report = new Result($data);

		return $report;
	}


	/**
	 * HELPERS *****************************************************************
	 */

	/**
	 * @param object $column
	 * @return mixed
	 */
	protected function generate($column)
	{
		switch ($column->type) {
			case self::TYPE_INT:
				return Random::generate($column->length, '0-9');
			case self::TYPE_STRING:
				return Random::generate($column->length, 'a-zA-Z0-9\s');
			case self::TYPE_RANGE:
				return mt_rand($column->range[0], $column->range[1]);
			default:
				throw new InvalidArgumentException("Unsupported type $column->type");
		}
	}


}