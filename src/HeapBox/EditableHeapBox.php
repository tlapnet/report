<?php

namespace Tlapnet\Report\HeapBox;

use Tlapnet\Report\DataSources\DataSource;
use Tlapnet\Report\Renderers\Renderer;

class EditableHeapBox extends HeapBox
{

    /**
     * @param ParameterList $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @param Renderer $renderer
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param DataSource $dataSource
     */
    public function setDataSource($dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * @param Metadata $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

}
