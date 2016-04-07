<?php

namespace Tlapnet\Report\Utils;

use DateTime as DT;
use JsonSerializable;

/**
 * DateTime
 *
 * Based on Nette\Utils\DateTime
 *
 * @see https://api.nette.org/2.3.9/source-Utils.DateTime.php.html
 */
class DateTime extends DT implements JsonSerializable
{

	/** minute in seconds */
	const MINUTE = 60;

	/** hour in seconds */
	const HOUR = 60 * self::MINUTE;

	/** day in seconds */
	const DAY = 24 * self::HOUR;

	/** week in seconds */
	const WEEK = 7 * self::DAY;

	/** average month in seconds */
	const MONTH = 2629800;

	/** average year in seconds */
	const YEAR = 31557600;


	/**
	 * DateTime object factory.
	 * @param string|int|\DateTimeInterface $time
	 * @return self
	 */
	public static function from($time)
	{
		if ($time instanceof \DateTimeInterface) {
			return new static($time->format('Y-m-d H:i:s'), $time->getTimezone());

		} elseif (is_numeric($time)) {
			if ($time <= self::YEAR) {
				$time += time();
			}

			return (new static('@' . $time))->setTimeZone(new \DateTimeZone(date_default_timezone_get()));

		} else { // textual or NULL
			return new static($time);
		}
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->format('Y-m-d H:i:s');
	}

	/**
	 * @param string $modify
	 * @return self
	 */
	public function modifyClone($modify = '')
	{
		$dolly = clone $this;

		return $modify ? $dolly->modify($modify) : $dolly;
	}

	/**
	 * Returns JSON representation in ISO 8601 (used by JavaScript).
	 * @return string
	 */
	public function jsonSerialize()
	{
		return $this->format('c');
	}

}