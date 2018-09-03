<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\UI\Response;

use Nette\Application\IResponse as UIResponse;
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Tracy\Debugger;

class DebugResponse implements UIResponse
{

	/** @var mixed */
	private $payload;

	/**
	 * @param mixed $payload
	 */
	public function __construct($payload)
	{
		$this->payload = $payload;
	}

	/**
	 * @return mixed
	 */
	public function getPayload()
	{
		return $this->payload;
	}

	public function send(IRequest $httpRequest, IResponse $httpResponse): void
	{
		Debugger::dump($this->payload);
	}

}
