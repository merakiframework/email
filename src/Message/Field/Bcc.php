<?php
declare(strict_types=1);

namespace Meraki\Email\Message\Field;

use Meraki\Email\Message\Field as DestinationField;
use Meraki\Email\AddressList;
use Countable;
use IteratorAggregate;
use Traversable;

final class Bcc implements DestinationField, Countable, IteratorAggregate
{
	private $addressList;

	public function __construct(AddressList $addressList)
	{
		$this->addressList = $addressList;
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
		return 'Bcc';
	}

	public function body(): string
	{
		return (string) $this->addressList;
	}

	public function addressList(): AddressList
	{
		return $this->addressList;
	}

	public function count(): int
	{
		return $this->addressList->count();
	}

	public function getIterator(): Traversable
	{
		return $this->addressList->getIterator();
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

	public function __call(string $methodName, array $args)
	{
		// forward unknown method calls to `MailboxList`
		return call_user_func_array([$this->addressList, $methodName], $args);
	}

	public function __toString(): string
	{
		return sprintf("%s: %s\r\n", $this->name(), $this->body());
	}
}
