<?php

namespace Tlapnet\Report\Loaders;

use Tlapnet\Report\DataSource\DataSource;
use Tlapnet\Report\HeapBox\HeapBox;
use Tlapnet\Report\HeapBox\ParameterList;
use Tlapnet\Report\Renderers\Renderer;

abstract class AbstractLoader implements Loader
{

    protected function createHeapBox($uid, ParameterList $parameters, DataSource $dataSource, Renderer $renderer)
    {
        $heap = new HeapBox();
    }

}