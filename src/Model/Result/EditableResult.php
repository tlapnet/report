<?php

namespace Tlapnet\Report\Model\Result;

class EditableResult extends Result
{

	/**
	 * @param mixed $data
	 */
	public function setData($data)
	{
		$this->data = $data;
	}

}
