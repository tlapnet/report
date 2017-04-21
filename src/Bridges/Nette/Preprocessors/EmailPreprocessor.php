<?php

namespace Tlapnet\Report\Bridges\Nette\Preprocessors;

use Nette\Utils\Html;
use Tlapnet\Report\Preprocessor\Impl\AbstractPreprocessor;

final class EmailPreprocessor extends AbstractPreprocessor
{

	/**
	 * @param mixed $data
	 * @return Html
	 */
	public function preprocess($data)
	{
		return Html::el('a')->href('mailto:' . $data)->setText($data);
	}

}
