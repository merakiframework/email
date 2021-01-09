<?php
declare(strict_types=1);

namespace Meraki\Email\Message\Field;

use Meraki\Email\Message\Field as OriginatorField;
use Meraki\Email\MailboxList;
use Meraki\Email\Mailbox;
use Countable;
use IteratorAggregate;
use ArrayIterator;

final class From implements OriginatorField, Countable, IteratorAggregate
{
	private $mailboxList;

	public function __construct(MailboxList $mailboxList)
	{	
		$this->mailboxList = $mailboxList;
	}

	public function required(): bool
	{
		return true;
	}

	public function occurrenceCount(): int
	{
		return 1;
	}

	public function name(): string
	{
		return 'From';
	}

	public function body(): string
	{
		return (string) $this->mailboxList;
	}

	public function mailboxList(): MailboxList
	{
		return $this->mailboxList;
	}

	public function count(): int
	{
		return $this->mailboxList->count();
	}

	public function getIterator(): ArrayIterator
	{
		return $this->mailboxList->getIterator();
	}

	public function __call(string $methodName, array $args)
	{
		// forward unknown method calls to `MailboxList`
		return call_user_func_array([$this->mailboxList, $methodName], $args);
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
