<?php

namespace Tlapnet\Report\Bridges\Nette\Utils\DataSource;

use Nette\Utils\Random;
use Tlapnet\Report\DataSources\DataSource;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Model\Data\Result;
use Tlapnet\Report\Model\Parameters\Parameters;
use Tlapnet\Report\Utils\DateTime;

class RandomDataSource implements DataSource
{

	/** Types */
	const TYPE_INT = 'int';
	const TYPE_RANGE = 'range';
	const TYPE_STRING = 'string';
	const TYPE_DATE = 'date';
	const TYPE_DATETIME = 'datetime';

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
	 * @param string $name
	 * @param string $format
	 */
	public function addDate($name, $format = 'd.m.Y')
	{
		$this->addColumn($name, [
			'type' => self::TYPE_DATE,
			'format' => $format,
		]);
	}

	/**
	 * @param string $name
	 * @param string $format
	 */
	public function addDateTime($name, $format = 'd.m.Y H:i:s')
	{
		$this->addColumn($name, [
			'type' => self::TYPE_DATETIME,
			'format' => $format,
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
	 * @param Parameters $parameters
	 * @return Result
	 */
	public function compile(Parameters $parameters)
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
			case self::TYPE_DATE:
				return (new DateTime())
					->setTimestamp(mt_rand(1, time()))
					->setFormat($column->format);
			case self::TYPE_DATETIME:
				return (new DateTime())
					->setTimestamp(mt_rand(1, time()))
					->setFormat($column->format);
			default:
				throw new InvalidArgumentException("Unsupported type $column->type");
		}
	}

}
