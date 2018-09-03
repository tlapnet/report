<?php declare(strict_types = 1);

namespace Tlapnet\Report\Preprocessor\Impl;

final class PrependPreprocessor extends AbstractPreprocessor
{

	/** @var string */
	protected $prepend;

	public function __construct(string $prepend)
	{
		$this->prepend = $prepend;
	}

	/**
	 * @param string $data
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function preprocess($data): string
	{
		return $this->prepend . $data;
	}

}
