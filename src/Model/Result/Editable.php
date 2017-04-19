<?php

namespace Tlapnet\Report\Model\Result;

interface Editable
{

	/**
	 * @return Mutable
	 */
	public function toEditable();

}
