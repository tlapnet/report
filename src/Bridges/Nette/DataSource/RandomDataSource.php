<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\DataSource;

use Nette\Utils\Random;
use Tlapnet\Report\DataSources\DataSource;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Result\Result;
use Tlapnet\Report\Result\Resultable;
use Tlapnet\Report\Utils\DateTime;

class RandomDataSource implements DataSource
{

	// Types
	public const TYPE_INT = 'int';
	public const TYPE_FLOAT = 'float';
	public const TYPE_RANGE = 'range';
	public const TYPE_STRING = 'string';
	public const TYPE_DATE = 'date';
	public const TYPE_DATETIME = 'datetime';
	public const TYPE_EMAIL = 'email';

	/** @var object[] */
	protected $columns = [];

	/** @var int */
	protected $rows = 100;

	/**
	 * @param mixed[] $options
	 */
	protected function addColumn(string $name, array $options): void
	{
		if (isset($this->columns[$name])) {
			throw new InvalidStateException(sprintf('Column "%s" already exists', $name));
		}

		$column = ['name' => $name] + $options;
		$this->columns[$name] = (object) $column;
	}

	public function addString(string $name, int $length = 20): void
	{
		$this->addColumn($name, [
			'type' => self::TYPE_STRING,
			'length' => $length,
		]);
	}

	public function addInt(string $name, int $length = 10): void
	{
		$this->addColumn($name, [
			'type' => self::TYPE_INT,
			'length' => $length,
		]);
	}

	public function addFloat(string $name, int $length = 10): void
	{
		$this->addColumn($name, [
			'type' => self::TYPE_FLOAT,
			'length' => $length,
		]);
	}

	public function addRange(string $name, int $from, int $to): void
	{
		$this->addColumn($name, [
			'type' => self::TYPE_RANGE,
			'range' => [$from, $to],
		]);
	}

	public function addDate(string $name, string $format = 'd.m.Y'): void
	{
		$this->addColumn($name, [
			'type' => self::TYPE_DATE,
			'format' => $format,
		]);
	}

	public function addDateTime(string $name, string $format = 'd.m.Y H:i:s'): void
	{
		$this->addColumn($name, [
			'type' => self::TYPE_DATETIME,
			'format' => $format,
		]);
	}

	public function addEmail(string $name): void
	{
		$this->addColumn($name, [
			'type' => self::TYPE_EMAIL,
		]);
	}

	public function setRows(int $rows): void
	{
		$this->rows = $rows;
	}

	/**
	 * @return Result
	 */
	public function compile(Parameters $parameters): Resultable
	{
		$data = [];

		for ($i = 1; $i <= $this->rows; $i++) {
			$row = [];
			foreach ($this->columns as $column) {
				$row[$column->name] = $this->generate($column);
			}

			$data[] = $row;
		}

		return new Result($data);
	}

	/**
	 * @return mixed
	 */
	protected function generate(object $column)
	{
		switch ($column->type) {
			case self::TYPE_INT:
				return Random::generate($column->length, '0-9');
			case self::TYPE_FLOAT:
				return mt_rand() / mt_getrandmax();
			case self::TYPE_STRING:
				return Random::generate($column->length, 'a-zA-Z0-9');
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
			case self::TYPE_EMAIL:
				return sprintf('%s@%s.%s', Random::generate(10), Random::generate(10), Random::generate(3, 'a-z'));
			default:
				throw new InvalidArgumentException(sprintf('Unsupported type "%s"', $column->type));
		}
	}

}
