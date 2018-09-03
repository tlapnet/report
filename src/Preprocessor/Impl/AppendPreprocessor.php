<?php declare(strict_types = 1);

namespace Tlapnet\Report\Preprocessor\Impl;

final class AppendPreprocessor extends AbstractPreprocessor
{

	/** @var string */
	protected $append;

	public function __construct(string $append)
	{
		$this->append = $append;
	}

	/**
	 * @param string $data
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function preprocess($data): string
	{
		return $data . $this->append;
	}

}
