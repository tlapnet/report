<?php declare(strict_types = 1);

namespace Tlapnet\Report\Result;

class EditableResult extends Result implements Mutable
{

	/**
	 * @param mixed $data
	 */
	public function setData($data): void
	{
		$this->data = $data;
	}

}
