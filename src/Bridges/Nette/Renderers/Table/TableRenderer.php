<?php

namespace Tlapnet\Report\Bridges\Nette\Renderers\Table;

use Tlapnet\Report\Bridges\Nette\Renderers\TemplateRenderer;
use Tlapnet\Report\Heap\Heap;

class TableRenderer extends TemplateRenderer
{

    /** @var array */
    protected $columns = [];

    /**
     * @param string $name
     * @param string $title
     */
    public function addColumn($name, $title)
    {
        $this->columns[$name] = (object)[
            'name' => $name,
            'title' => $title,
        ];
    }

    /**
     * @param Heap $heap
     * @return mixed
     */
    public function render(Heap $heap)
    {
        $template = $this->createTemplate();
        $template->setFile(__DIR__ . '/templates/table.latte');
        $template->columns = $this->columns;
        $template->rows = $heap;
        $template->render();
    }

}
