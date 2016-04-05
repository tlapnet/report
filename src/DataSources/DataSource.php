<?php

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Heap\Heap;
use Tlapnet\Report\HeapBox\ParameterList;

interface DataSource
{

    /**
     * @param ParameterList $parameters
     * @return Heap
     */
    public function compile(ParameterList $parameters);

}
