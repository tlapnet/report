<?php

namespace Tlapnet\Report\Result;

interface Editable
{

	/**
	 * @return Mutable
	 */
	public function toEditable();

}
