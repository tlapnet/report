<?php declare(strict_types = 1);

namespace Tests\Cases\Utils;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Utils\Suggestions;

final class SuggestionsTest extends BaseTestCase
{

	public function testSuggestions(): void
	{
		$items = ['foo', 'bar'];
		$s = Suggestions::getSuggestion($items, 'fo');
		$this->assertEquals('foo', $s);

		$items = ['foo', 'foobar', 'bar'];
		$s = Suggestions::getSuggestion($items, 'foob');
		$this->assertEquals('foo', $s);

		$items = ['foo', 'foobar', 'bar'];
		$s = Suggestions::getSuggestion($items, 'foobra');
		$this->assertEquals('foobar', $s);

		$items = ['foo', 'foobar', 'bar'];
		$s = Suggestions::getSuggestion($items, 'brambora');
		$this->assertNull($s);
	}

}
