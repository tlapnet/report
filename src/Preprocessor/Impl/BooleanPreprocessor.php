<?php declare(strict_types = 1);

namespace Tlapnet\Report\Preprocessor\Impl;

final class BooleanPreprocessor extends AbstractPreprocessor
{

	/** @var string */
	private $trueLabel;

	/** @var string */
	private $falseLabel;

	public function __construct(string $trueLabel = 'Ano', string $falseLabel = 'Ne')
	{
		$this->trueLabel = $trueLabel;
		$this->falseLabel = $falseLabel;
	}

	/**
	 * @param bool $data
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function preprocess($data): string
	{
		return $data ? $this->trueLabel : $this->falseLabel;
	}

}
