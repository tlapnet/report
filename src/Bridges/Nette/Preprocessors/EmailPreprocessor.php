<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\Preprocessors;

use Nette\Utils\Html;
use Tlapnet\Report\Preprocessor\Impl\AbstractPreprocessor;

final class EmailPreprocessor extends AbstractPreprocessor
{

	/**
	 * @param mixed $data
	 */
	public function preprocess($data): Html
	{
		return Html::el('a')->href('mailto:' . $data)->setText($data);
	}

}
