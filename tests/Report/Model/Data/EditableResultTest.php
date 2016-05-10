<?php

namespace Tlapnet\Report\Tests\Model\Data;

use Tlapnet\Report\Model\Data\EditableResult;
use Tlapnet\Report\Tests\BaseTestCase;

final class EditableResultTest extends BaseTestCase
{

    public function testSetData()
    {
        $er = new EditableResult([]);
        $this->assertEquals([], $er->getData());

        $er->setData(['foo', 'bar']);
        $this->assertEquals(['foo', 'bar'], $er->getData());
    }

}
