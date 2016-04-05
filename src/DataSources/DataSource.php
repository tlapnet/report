<?php

namespace Tlapnet\Report\DataSource;

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
