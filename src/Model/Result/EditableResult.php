<?php

namespace Tlapnet\Report\Model\Result;

class EditableResult extends Result
{

	/**
	 * @param mixed $data
	 * @return void
	 */
	public function setData($data)
	{
		$this->data = $data;
	}

}
