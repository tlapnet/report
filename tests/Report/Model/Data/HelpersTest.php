<?php

namespace Tlapnet\Report\Tests\Model\Data;

use Tlapnet\Report\Model\Data\Helpers;
use Tlapnet\Report\Model\Data\Result;
use Tlapnet\Report\Tests\BaseTestCase;

final class HelpersTest extends BaseTestCase
{

    public function testToArray1()
    {
        $data = [1, 2, 3, 4];
        $r = new Result($data);
        $array = Helpers::toArray($r);

        $this->assertEquals($data, $array);
    }

    public function testToArray2()
    {
        $data = [['foo'], ['bar']];
        $r = new Result($data);
        $array = Helpers::toArray($r);

        $this->assertEquals($data, $array);
    }
}
