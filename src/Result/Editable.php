<?php declare(strict_types = 1);

namespace Tlapnet\Report\Result;

interface Editable
{

	public function toEditable(): Mutable;

}
