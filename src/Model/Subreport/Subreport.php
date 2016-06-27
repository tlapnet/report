<?php

namespace Tlapnet\Report\Model\Subreport;

use Tlapnet\Report\DataSources\DataSource;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Model\Result\EditableResult;
use Tlapnet\Report\Model\Result\Result;
use Tlapnet\Report\Model\Result\Resultable;
use Tlapnet\Report\Model\Parameters\Parameters;
use Tlapnet\Report\Model\Preprocessor\Preprocessor;
use Tlapnet\Report\Model\Preprocessor\Preprocessors;
use Tlapnet\Report\Model\Utils\Metadata;
use Tlapnet\Report\Renderers\Renderer;

class Subreport implements Reportable
{

	/** States */
	const STATE_CREATED = 1;
	const STATE_ATTACHED = 2;
	const STATE_COMPILED = 3;
	const STATE_PREPROCESSED = 4;
	const STATE_RENDERED = 5;

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

	/** @var Preprocessors */
	protected $preprocessors;

	/** @var Resultable|Result */
	protected $rawResult;

	/** @var Resultable|EditableResult */
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
		$this->preprocessors = new Preprocessors();
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
	 * @return Preprocessors
	 */
	public function getPreprocessors()
	{
		return $this->preprocessors;
	}

	/**
	 * @return Metadata
	 */
	public function getMetadata()
	{
		return $this->metadata;
	}

	/**
	 * @return Result|Resultable
	 */
	public function getResult()
	{
		return $this->result;
	}

	/**
	 * @return Result|Resultable
	 */
	public function getRawResult()
	{
		return $this->rawResult;
	}

	/**
	 * @param int $state
	 * @return bool
	 */
	public function isState($state)
	{
		return $this->state === $state;
	}

	/**
	 * PREPROCESSORS ***********************************************************
	 */

	/**
	 * @param string $column
	 * @param Preprocessor $preprocessor
	 * @return Preprocessor
	 */
	public function addPreprocessor($column, $preprocessor)
	{
		$this->preprocessors->add($column, $preprocessor);

		return $preprocessor;
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
		if ($this->state === self::STATE_COMPILED || $this->state === self::STATE_PREPROCESSED) {
			throw new InvalidStateException('Cannot attach parameters in this state.');
		}

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
		$result = $this->dataSource->compile($this->parameters);

		if ($result === NULL) {
			throw new InvalidStateException('Compilation cannot return NULL.');
		}

		if (!$result instanceof Resultable) {
			throw new InvalidStateException(sprintf("DataSource returned object '%s' does not implement '%s'.", get_class($result), Resultable::class));
		}

		$this->result = $this->rawResult = $result;
		$this->state = self::STATE_COMPILED;
	}

	/**
	 * DATA PREPROCESSING ******************************************************
	 */

	/**
	 * @retur void
	 */
	public function preprocess()
	{
		if ($this->state === self::STATE_PREPROCESSED) {
			throw new InvalidStateException('Cannot preprocess twice same report.');
		}

		if ($this->state !== self::STATE_COMPILED) {
			throw new InvalidStateException('Cannot preprocess subreport. Please compiled it first.');
		}

		// Preprocess result
		if (!$this->preprocessors->isEmpty()) {
			$this->result = $this->rawResult->toEditable();
			$this->preprocessors->preprocess($this->result);
			$this->state = self::STATE_PREPROCESSED;
		}
	}

	/**
	 * RENDERING ***************************************************************
	 */

	/**
	 * @return mixed
	 */
	public function render()
	{
		if ($this->state !== self::STATE_COMPILED && $this->state !== self::STATE_PREPROCESSED) {
			throw new InvalidStateException('Cannot render subreport. Please compiled or preprocess it first.');
		}

		$this->state = self::STATE_RENDERED;

		return $this->renderer->render($this->result);
	}

}
