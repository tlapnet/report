<?php

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Result\Result;

class MultiRenderer implements Renderer
{

	/** @var Renderer[] */
	protected $renderers = [];

	/** @var mixed */
	protected $separator = NULL;

	/**
	 * @param Renderer[] $renderers
	 */
	public function __construct(array $renderers)
	{
		$this->renderers = $renderers;
	}

	/**
	 * @param mixed $separator
	 * @return void
	 */
	public function setSeparator($separator)
	{
		$this->separator = $separator;
	}

	/**
	 * RENDERING ***************************************************************
	 */

	/**
	 * @param Result $result
	 * @return string
	 */
	public function render(Result $result)
	{
		$parts = [];

		foreach ($this->renderers as $renderer) {
			$parts[] = $renderer->render($result);
		}

		return implode($this->separator, $parts);
	}

}
