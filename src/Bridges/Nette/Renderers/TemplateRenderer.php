<?php

namespace Tlapnet\Report\Bridges\Nette\Renderers;

use Nette\Application\UI\ITemplate;
use Nette\Application\UI\ITemplateFactory;
use Tlapnet\Report\Renderers\Renderer;

abstract class TemplateRenderer implements Renderer
{

	/** @var ITemplateFactory */
	private $templateFactory;

	public function __construct(ITemplateFactory $templateFactory)
	{
		$this->templateFactory = $templateFactory;
	}

	protected function createTemplate(): ITemplate
	{
		return $this->templateFactory->createTemplate();
	}

}
