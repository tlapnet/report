<?php

namespace Tlapnet\Report\Model\Subreport;

class Preprocessors
{

	/** @var Preprocessor[] */
	private $preprocessors = [];

	/**
	 * @param Preprocessor $preprocessor
	 */
	public function add(Preprocessor $preprocessor)
	{
		$this->preprocessors[] = $preprocessor;
	}

}
