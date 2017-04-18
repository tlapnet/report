<?php

namespace Tlapnet\Report\Model\Preprocessor\Impl;

final class BooleanPreprocessor extends AbstractPreprocessor
{

	/** @var string */
	private $trueLabel;

	/** @var string */
	private $falseLabel;

	/**
	 * @param string $trueLabel
	 * @param string $falseLabel
	 */
	public function __construct($trueLabel = 'Ano', $falseLabel = 'Ne')
	{
		$this->trueLabel = $trueLabel;
		$this->falseLabel = $falseLabel;
	}

	/**
	 * PREPROCESSING ***********************************************************
	 */

	/**
	 * @param mixed $data
	 * @return string
	 */
	public function preprocess($data)
	{
		return $data ? $this->trueLabel : $this->falseLabel;
	}

}
