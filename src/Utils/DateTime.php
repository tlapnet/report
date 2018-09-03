<?php declare(strict_types = 1);

namespace Tlapnet\Report\Utils;

use DateTime as DT;
use DateTimeInterface;
use DateTimeZone;
use JsonSerializable;

/**
 * DateTime
 *
 * Based on Nette\Utils\DateTime
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
class DateTime extends DT implements JsonSerializable
{

	// minute in seconds
	public const MINUTE = 60;

	// hour in seconds
	public const HOUR = 60 * self::MINUTE;

	// day in seconds
	public const DAY = 24 * self::HOUR;

	// week in seconds
	public const WEEK = 7 * self::DAY;

	// average month in seconds
	public const MONTH = 2629800;

	// average year in seconds
	public const YEAR = 31557600;

	/** @var string */
	protected $format = 'Y-m-d H:i:s';

	/**
	 * DateTime object factory.
	 *
	 * @param string|int|DateTimeInterface $time
	 */
	public static function from($time): self
	{
		if ($time instanceof DateTimeInterface) {
			return new static($time->format('Y-m-d H:i:s'), $time->getTimezone());

		} elseif (is_numeric($time)) {
			return (new static('@' . $time))
				->setTimezone(new DateTimeZone(date_default_timezone_get()));

		} else {
			return new static($time);
		}
	}

	public function getFormat(): string
	{
		return $this->format;
	}

	public function setFormat(string $format): self
	{
		$this->format = $format;

		return $this;
	}

	public function __toString(): string
	{
		return $this->format($this->format);
	}

	public function modifyClone(string $modify = ''): self
	{
		$dolly = clone $this;

		return $modify !== '' ? $dolly->modify($modify) : $dolly;
	}

	/**
	 * Returns JSON representation in ISO 8601 (used by JavaScript).
	 */
	public function jsonSerialize(): string
	{
		return $this->format('c');
	}

}
