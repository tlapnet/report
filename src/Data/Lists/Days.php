<?php

namespace Tlapnet\Report\Data\Lists;

class Days extends AbstractList
{

	/**
	 * Build days list
	 */
	protected function __construct()
	{
		parent::__construct([
			'cs' => [
				1 => 'Pondělí',
				2 => 'Úterý',
				3 => 'Středa',
				4 => 'Čtvrtek',
				5 => 'Pátek',
				6 => 'Sobota',
				7 => 'Nedělě',
			],
			'sk' => [
				1 => 'Pondelok',
				2 => 'Utorok',
				3 => 'Streda',
				4 => 'Štvrtok',
				5 => 'Piatok',
				6 => 'Sobota',
				7 => 'Nedeľa',
			]]);
	}

}
