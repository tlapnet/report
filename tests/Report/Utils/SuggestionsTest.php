<?php

namespace Tlapnet\Report\Tests\Utils;

use Tlapnet\Report\Tests\BaseTestCase;
use Tlapnet\Report\Utils\Suggestions;

final class SuggestionsTest extends BaseTestCase
{

    public function testSuggestions()
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
