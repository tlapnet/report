<?php declare(strict_types = 1);

namespace Tlapnet\Report\Data\Lists;

class Months extends AbstractList
{

	/**
	 * Build months list
	 */
	protected function __construct()
	{
		parent::__construct([
			'cs' => [
				1 => 'Leden',
				2 => 'Únor',
				3 => 'Březen',
				4 => 'Duben',
				5 => 'Květen',
				6 => 'Červen',
				7 => 'Červenec',
				8 => 'Srpen',
				9 => 'Září',
				10 => 'Říjen',
				11 => 'Listopad',
				12 => 'Prosinec',
			],
			'sk' => [
				1 => 'Január',
				2 => 'Február',
				3 => 'Marec',
				4 => 'Apríl',
				5 => 'Máj',
				6 => 'Jún',
				7 => 'Júl',
				8 => 'August',
				9 => 'September',
				10 => 'Október',
				11 => 'November',
				12 => 'December',
			]]);
	}

}
