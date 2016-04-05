<?php

namespace Tlapnet\Report\Bridges\Dibi\DataSources;

use Tlapnet\Report\DataSource\AbstractDatabaseDataSource;
use Tlapnet\Report\Heap\Heap;
use Tlapnet\Report\HeapBox\ParameterList;

class DibiDataSource extends AbstractDatabaseDataSource
{

    /**
     * @param ParameterList $parameters
     * @return Heap
     */
    public function compile(ParameterList $parameters)
    {
        $stop();
    }

}
