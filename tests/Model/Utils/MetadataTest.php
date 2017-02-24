<?php

namespace Tlapnet\Report\Tests\Model\Utils;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Model\Utils\Metadata;
use Tlapnet\Report\Tests\BaseTestCase;

final class MetadataTest extends BaseTestCase
{

	/**
	 * @covers Metadata::set
	 * @covers Metadata::get
	 * @covers Metadata::has
	 * @return void
	 */
	public function testMethods()
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
	 * @return void
	 */
	public function testSuggestions1()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Unknown key "fod", did you mean "foo"?');

		$md = new Metadata();
		$md->set('foo', 'bar');
		$md->get('fod');
	}

	/**
	 * @coversNothing
	 * @return void
	 */
	public function testSuggestions2()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Unknown key "bar".');

		$md = new Metadata();
		$md->set('foo', 'bar');
		$md->get('bar');
	}

}
