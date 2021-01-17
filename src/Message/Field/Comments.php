<?php
declare(strict_types=1);

namespace Meraki\Email\Message\Field;

use Meraki\Email\Message\Field as InformationalField;

final class Comments implements InformationalField
{
	private $body;

	public function __construct(string $unstructured)
	{
		$this->body = $unstructured;
	}

	public function required(): bool
	{
		return false;
	}

	public function occurrenceCount(): int
	{
		return 1;
	}

	public function name(): string
	{
		return 'Comments';
	}

	public function body(): string
	{
		return $this->body;
	}

	public function normalise(): self
	{
		return $this->makeReadable()->fold();
	}

	public function fold(): self
	{
		return new self($this->name(), $this->body());
	}

	public function unfold(): self
	{
		return new self($this->name(), $this->body());
	}

	public function makeReadable(): self
	{
		return new self($this->name(), $this->body());
	}

	public function __toString(): string
	{
		return sprintf("%s: %s\r\n", $this->name(), $this->body());
	}
}
