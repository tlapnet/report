<?php

namespace Tlapnet\Report\Bridges\Nette\Form;

use Nette\Application\UI\Form as UIForm;

class Form extends UIForm
{

	/**
	 * @return array
	 */
	public function getRealValues()
	{
		$values = [];

		foreach ($this->getValues() as $k => $v) {
			// Skip empty and null values
			if (strlen($v) <= 0 || $v === NULL) {
				continue;
			}

			// Otherwise, append to array
			$values[$k] = $v;
		}

		return $values;
	}

}
