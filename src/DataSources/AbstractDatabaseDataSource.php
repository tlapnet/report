<?php

namespace Tlapnet\Report\DataSources;

abstract class AbstractDatabaseDataSource implements DataSource
{

    /** @var array */
    protected $config = [];

    /** @var mixed */
    protected $sql;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * @param mixed $sql
     */
    public function setSql($sql)
    {
        $this->sql = $sql;
    }

}
