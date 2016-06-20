<?php

namespace Tlapnet\Report\Model\Parameters\Parameter;

final class TextParameter extends Parameter
{

	/**
	 * @param string $name
	 */
	public function __construct($name)
	{
		parent::__construct($name, Parameter::TYPE_TEXT);
	}

}
