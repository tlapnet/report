<?php

namespace Tlapnet\Report\HeapBox;

use Tlapnet\Report\DataSources\DataSource;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Heap\Heap;
use Tlapnet\Report\Renderers\Renderer;

class HeapBox implements Heapable
{

    /** States */
    const STATE_CREATED = 1;
    const STATE_ATTACHED = 2;
    const STATE_COMPILED = 3;
    const STATE_RENDERED = 4;

    /** @var mixed */
    protected $uid;

    /** @var ParameterList */
    protected $parameters;

    /** @var Renderer */
    protected $renderer;

    /** @var DataSource */
    protected $dataSource;

    /** @var Metadata */
    protected $metadata;

    /** @var Heap */
    protected $heap;

    /** @var int */
    protected $state;

    /**
     * @param mixed $uid
     * @param ParameterList $parameters
     * @param DataSource $dataSource
     * @param Renderer $renderer
     */
    public function __construct($uid, ParameterList $parameters, DataSource $dataSource, Renderer $renderer)
    {
        $this->uid = $uid;
        $this->parameters = $parameters;
        $this->renderer = $renderer;
        $this->datasource = $dataSource;
        $this->metadata = new Metadata();
        $this->state = self::STATE_CREATED;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return ParameterList
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return Renderer
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @return DataSource
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * METADATA ****************************************************************
     */

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setOption($key, $value)
    {
        $this->metadata->set($key, $value);
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getOption($key, $default = NULL)
    {
        if (func_num_args() < 2) {
            return $this->metadata->get($key);
        } else {
            return $this->metadata->get($key, $default);
        }
    }

    /**
     * ATTACHING ***************************************************************
     */

    /**
     * @param array $values
     * @return void
     */
    public function attach(array $values)
    {
        $this->parameters->attach($values);
        $this->state = self::STATE_ATTACHED;
    }

    /**
     * COMPILING ***************************************************************
     */

    /**
     * @return void
     */
    public function compile()
    {
        $this->heap = $this->datasource->compile($this->parameters);

        if ($this->heap === NULL) {
            throw new InvalidStateException('Compilation cannot return NULL.');
        }

        if (!$this->heap instanceof Heap) {
            throw new InvalidStateException('Compilation returned object (' . get_class($this->heap) . ') is not subclass of Heap.');
        }

        $this->state = self::STATE_COMPILED;
    }

    /**
     * RENDERING ***************************************************************
     */

    /**
     * @return mixed
     */
    public function render()
    {
        if ($this->state !== self::STATE_COMPILED) {
            throw new InvalidStateException('Cannot render heapbox. Please compiled it first.');
        }

        $this->state = self::STATE_RENDERED;

        return $this->renderer->render($this->heap);
    }

}
