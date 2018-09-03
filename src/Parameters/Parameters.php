<?php declare(strict_types = 1);

namespace Tlapnet\Report\Parameters;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Parameters\Impl\Parameter;
use Tlapnet\Report\Subreport\Attachable;
use Tlapnet\Report\Utils\Expander;
use Tlapnet\Report\Utils\Suggestions;
use Tlapnet\Report\Utils\Switcher;

class Parameters implements Attachable
{

	// States
	public const STATE_EMPTY = 1;
	public const STATE_ATTACHED = 2;

	/** @var Parameter[] */
	private $parameters = [];

	/** @var int */
	private $state;

	/**
	 * Creates parameters
	 */
	public function __construct()
	{
		$this->state = self::STATE_EMPTY;
	}

	public function add(Parameter $parameter): void
	{
		$this->parameters[$parameter->getName()] = $parameter;
	}

	public function get(string $name): Parameter
	{
		if (!isset($this->parameters[$name])) {
			$hint = Suggestions::getSuggestion(array_keys($this->parameters), $name);
			throw new InvalidArgumentException(Suggestions::format(sprintf('Unknown parameter "%s"', $name), $hint));
		}

		return $this->parameters[$name];
	}

	/**
	 * @return Parameter[]
	 */
	public function getAll(): array
	{
		return $this->parameters;
	}

	/**
	 * @param mixed[] $data
	 */
	public function attach(array $data): void
	{
		$attached = [];
		foreach ($data as $key => $value) {
			// Fetch parameter
			$p = $this->get($key);

			// Set value to parameter
			$p->setValue($value);

			// Controle check - if we really have data
			if ($p->hasValue()) {
				$attached[] = $value;
			}
		}

		// Change state only if data was changed
		if ($attached !== []) {
			$this->state = self::STATE_ATTACHED;
		}
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$array = [];
		foreach ($this->parameters as $parameter) {
			// Fill only if parameter has a right value
			// or if parameter has default value
			// or if parameter can provide/compute value
			if ($parameter->hasValue()) {
				$array[$parameter->getName()] = $parameter->getValue();
			} elseif ($parameter->hasDefaultValue()) {
				$array[$parameter->getName()] = $parameter->getDefaultValue();
			} elseif ($parameter->canProvide()) {
				$array[$parameter->getName()] = $parameter->getProvidedValue();
			}
		}

		return $array;
	}

	public function isEmpty(): bool
	{
		return count($this->parameters) <= 0;
	}


	public function isAttached(): bool
	{
		return $this->state === self::STATE_ATTACHED;
	}

	public function canSwitch(): bool
	{
		if ($this->isEmpty()) return false;

		foreach ($this->parameters as $parameter) {
			if (!$parameter->canProvide()) return false;
		}

		return true;
	}

	public function createExpander(): Expander
	{
		return new Expander($this->toArray());
	}

	public function createSwitcher(): Switcher
	{
		return new Switcher($this->toArray());
	}

}
