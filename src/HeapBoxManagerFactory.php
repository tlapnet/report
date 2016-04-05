<?php

namespace Tlapnet\Report;

use Tlapnet\Report\Loaders\Loader;

class HeapBoxManagerFactory implements IHeapBoxManagerFactory
{

    /** @var Loader */
    protected $loader;

    /**
     * @param Loader $loader
     */
    public function setLoader($loader)
    {
        $this->loader = $loader;
    }

    /**
     * @return HeapBoxManager
     */
    public function create()
    {
        $stop();
    }

}
