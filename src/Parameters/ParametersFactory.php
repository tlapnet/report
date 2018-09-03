<?php declare(strict_types = 1);

namespace Tlapnet\Report\Parameters;

use Tlapnet\Report\Parameters\Impl\Parameter;

class ParametersFactory
{

	/**
	 * @param Parameter[] $parameters
	 */
	public static function create(array $parameters): Parameters
	{
		$p = new Parameters();

		foreach ($parameters as $parameter) {
			$p->add($parameter);
		}

		return $p;
	}

}
