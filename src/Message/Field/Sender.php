<?php
declare(strict_types=1);

namespace Meraki\Email\Message\Field;

use Meraki\Email\Message\Field as OriginatorField;
use Meraki\Email\Mailbox;

final class Sender implements OriginatorField
{
	private $mailbox;

	public function __construct(Mailbox $mailbox)
	{
		$this->mailbox = $mailbox;
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
		return 'Sender';
	}

	public function body(): string
	{
		return (string) $this->mailbox;
	}

	public function mailbox(): Mailbox
	{
		return $this->mailbox;
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
