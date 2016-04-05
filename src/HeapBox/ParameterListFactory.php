<?php

namespace Tlapnet\Report\HeapBox;

class ParameterListFactory
{

    /**
     * @param array $parameters
     * @return ParameterList
     */
    public static function create(array $parameters)
    {
        $pl = new ParameterList();

        return $pl;
    }

}
