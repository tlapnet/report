<?php declare(strict_types = 1);

namespace Tlapnet\Report\Data\Lists;

use Tlapnet\Report\Exceptions\Logic\InvalidStateException;

abstract class AbstractList
{

	/** @var self|null */
	protected static $instance;

	/** @var mixed[] */
	protected $data = [];

	/** @var callable[] */
	protected $filters = [];

	/** @var string */
	protected $lang = 'cs';

	/**
	 * @param mixed[] $data
	 */
	protected function __construct(array $data = [])
	{
		$this->data = $data;
	}

	public static function lang(string $lang): self
	{
		self::getInstance()->lang = $lang;

		return self::getInstance();
	}

	/**
	 * @return mixed[]
	 */
	public static function get(): array
	{
		$that = self::getInstance();

		if (!isset($that->data[$that->lang])) {
			throw new InvalidStateException(sprintf('Missing dataset for "%s" language', $that->lang));
		}

		$data = $that->data[$that->lang];

		if ($that->filters !== []) {
			foreach ($that->filters as $filter) {
				$data = array_map($filter, $data);
			}
			$that->filters = [];
		}

		return $data;
	}

	public static function lower(): self
	{
		self::getInstance()->filters[] = function ($item) {
			return mb_strtolower($item);
		};

		return self::getInstance();
	}

	public static function upper(): self
	{
		self::getInstance()->filters[] = function ($item) {
			return mb_strtoupper($item);
		};

		return self::getInstance();
	}

	public static function ucwords(): self
	{
		self::getInstance()->filters[] = function ($item) {
			return ucwords($item);
		};

		return self::getInstance();
	}

	public static function ucfirst(): self
	{
		self::getInstance()->filters[] = function ($item) {
			return ucfirst($item);
		};

		return self::getInstance();
	}

	public static function getInstance(): self
	{
		if (self::$instance === null) {
			self::$instance = new static();
		}

		return self::$instance;
	}

}
