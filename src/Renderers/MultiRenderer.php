<?php declare(strict_types = 1);

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Result\Result;

class MultiRenderer implements Renderer
{

	/** @var Renderer[] */
	protected $renderers = [];

	/** @var string */
	protected $separator = '';

	/**
	 * @param Renderer[] $renderers
	 */
	public function __construct(array $renderers)
	{
		$this->renderers = $renderers;
	}

	public function setSeparator(string $separator): void
	{
		$this->separator = $separator;
	}

	public function render(Result $result): string
	{
		$parts = [];

		foreach ($this->renderers as $renderer) {
			$parts[] = $renderer->render($result);
		}

		return implode($this->separator, $parts);
	}

}
