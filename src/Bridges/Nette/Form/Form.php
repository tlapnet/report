<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\Form;

use Nette\Application\UI\Form as UIForm;

class Form extends UIForm
{

	/**
	 * @return mixed[]
	 */
	public function getRealValues(): array
	{
		$values = [];

		foreach ($this->getValues() as $k => $v) {
			// Skip empty and null values
			if (strlen($v) <= 0 || $v === null) {
				continue;
			}

			// Otherwise, append to array
			$values[$k] = $v;
		}

		return $values;
	}

}
