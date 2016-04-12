<?php

namespace Tlapnet\Report\Model\Subreport;

use Tlapnet\Report\DataSources\DataSource;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Model\Data\Result;
use Tlapnet\Report\Renderers\Renderer;

class Subreport implements Reportable
{

	/** States */
	const STATE_CREATED = 1;
	const STATE_ATTACHED = 2;
	const STATE_COMPILED = 3;
	const STATE_RENDERED = 4;

	/** @var mixed */
	protected $sid;

	/** @var Parameters */
	protected $parameters;

	/** @var Renderer */
	protected $renderer;

	/** @var DataSource */
	protected $dataSource;

	/** @var Metadata */
	protected $metadata;
	
	/** @var array  */
	protected $preprocessors = [];

	/** @var Result */
	protected $result;

	/** @var int */
	protected $state;

	/**
	 * @param mixed $sid
	 * @param Parameters $parameters
	 * @param DataSource $dataSource
	 * @param Renderer $renderer
	 */
	public function __construct($sid, Parameters $parameters, DataSource $dataSource, Renderer $renderer)
	{
		$this->sid = $sid;
		$this->parameters = $parameters;
		$this->renderer = $renderer;
		$this->dataSource = $dataSource;
		$this->metadata = new Metadata();
		$this->state = self::STATE_CREATED;
	}

	/**
	 * @return mixed
	 */
	public function getSid()
	{
		return $this->sid;
	}

	/**
	 * @return Parameters
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
	 * @param string $key
	 * @return bool
	 */
	public function hasOption($key)
	{
		return $this->metadata->has($key);
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
		$this->result = $this->dataSource->compile($this->parameters);

		if ($this->result === NULL) {
			throw new InvalidStateException('Compilation cannot return NULL.');
		}

		if (!$this->result instanceof Result) {
			throw new InvalidStateException(sprintf('Compilation returned object (%s) is not subclass of %s.', get_class($this->result), Result::class));
		}

		$this->state = self::STATE_COMPILED;
	}

	/**
	 * DATA PREPROCESSING ******************************************************
	 */

	public function preprocess()
	{
		
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

		return $this->renderer->render($this->result);
	}

}
