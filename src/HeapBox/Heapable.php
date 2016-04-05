<?php

namespace Tlapnet\Report\HeapBox;

interface Heapable
{

    /**
     * @param array $data
     * @return void
     */
    public function attach(array $data);

    /**
     * @return mixed
     */
    public function render();

    /**
     * @return mixed
     */
    public function compile();

}
