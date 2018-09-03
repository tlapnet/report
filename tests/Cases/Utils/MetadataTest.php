<?php declare(strict_types = 1);

namespace Tests\Cases\Utils;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Utils\Metadata;

final class MetadataTest extends BaseTestCase
{

	public function testMethods(): void
	{
		$md = new Metadata();
		$md->set('foo', 'bar');
		$this->assertEquals('bar', $md->get('foo'));
		$this->assertTrue($md->has('foo'));

		$md->set('foo', 'bar2');
		$this->assertEquals('bar2', $md->get('foo'));
		$this->assertTrue($md->has('foo'));

		$this->assertFalse($md->has('foobar'));
	}

	/**
	 * @coversNothing
	 */
	public function testSuggestions1(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Unknown key "fod", did you mean "foo"?');

		$md = new Metadata();
		$md->set('foo', 'bar');
		$md->get('fod');
	}

	/**
	 * @coversNothing
	 */
	public function testSuggestions2(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Unknown key "bar".');

		$md = new Metadata();
		$md->set('foo', 'bar');
		$md->get('bar');
	}

}
