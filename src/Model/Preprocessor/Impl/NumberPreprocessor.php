<?php

namespace Tlapnet\Report\Model\Preprocessor\Impl;

class NumberPreprocessor extends AbstractPreprocessor
{

	/** @var int */
	protected $decimals = 2;

	/** @var string */
	protected $decpoint = '.';

	/** @var string */
	protected $thousandspoint = ' ';

	/**
	 * @param string $suffix
	 */
	public function setSuffix($suffix)
	{
		$this->suffix = $suffix;
	}

	/**
	 * @param int $decimals
	 */
	public function setDecimals($decimals)
	{
		$this->decimals = $decimals;
	}

	/**
	 * @param string $decpoint
	 */
	public function setDecpoint($decpoint)
	{
		$this->decpoint = $decpoint;
	}

	/**
	 * @param string $thousandspoint
	 */
	public function setThousandspoint($thousandspoint)
	{
		$this->thousandspoint = $thousandspoint;
	}

	/**
	 * PREPROCESSING ***********************************************************
	 */

	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function preprocess($data)
	{
		return number_format($data, $this->decimals, $this->decpoint, $this->thousandspoint);
	}

}
