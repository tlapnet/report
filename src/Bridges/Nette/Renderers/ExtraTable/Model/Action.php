<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\Model;

class Action extends Component
{

	/** @var object|null */
	private $link;

	/** @var object|null */
	private $label;

	/** @var string|null */
	private $title;

	public function getLink(): ?object
	{
		return $this->link;
	}

	public function getLabel(): ?object
	{
		return $this->label;
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function title(string $title): self
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * @param mixed[]  $args
	 */
	public function link(string $destination, array $args = []): self
	{
		$this->link = (object) [
			'destination' => $destination,
			'args' => $args,
		];

		return $this;
	}

	public function label(string $label): self
	{
		$this->label = (object) [
			'name' => $label,
		];

		return $this;
	}

}
