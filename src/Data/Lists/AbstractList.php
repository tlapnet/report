<?php

namespace Tlapnet\Report\Data\Lists;

use Tlapnet\Report\Exceptions\Logic\InvalidStateException;

abstract class AbstractList
{

	/** @var static */
	protected static $instance;

	/** @var array */
	protected $data = [];

	/** @var callable[] */
	protected $filters = [];

	/** @var string */
	protected $lang = 'cs';

	/**
	 * @param array $data
	 */
	protected function __construct(array $data = [])
	{
		$this->data = $data;
	}

	/**
	 * @param string $lang
	 * @return static
	 */
	public static function lang($lang)
	{
		self::getInstance()->lang = $lang;

		return self::getInstance();
	}

	/**
	 * @return mixed
	 */
	public static function get()
	{
		$that = self::getInstance();

		if ($that->lang && !isset($that->data[$that->lang])) {
			throw new InvalidStateException(sprintf('Missing dataset for "%s" language', $that->lang));
		}

		$data = $that->data[$that->lang];

		if ($that->filters) {
			foreach ($that->filters as $filter) {
				$data = array_map($filter, $data);
			}
			$that->filters = [];
		}

		return $data;
	}

	/**
	 * FILTERS *****************************************************************
	 */

	/**
	 * @return static
	 */
	public static function lower()
	{
		self::getInstance()->filters[] = function ($item) {
			return mb_strtolower($item);
		};

		return self::getInstance();
	}

	/**
	 * @return static
	 */
	public static function upper()
	{
		self::getInstance()->filters[] = function ($item) {
			return mb_strtoupper($item);
		};

		return self::getInstance();
	}

	/**
	 * @return static
	 */
	public static function ucwords()
	{
		self::getInstance()->filters[] = function ($item) {
			return ucwords($item);
		};

		return self::getInstance();
	}

	/**
	 * @return static
	 */
	public static function ucfirst()
	{
		self::getInstance()->filters[] = function ($item) {
			return ucfirst($item);
		};

		return self::getInstance();
	}

	/**
	 * SINGLETON ***************************************************************
	 */

	/**
	 * @return static
	 */
	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new static();
		}

		return self::$instance;
	}

}
