<?php
declare(strict_types=1);

namespace Meraki\Email;

use Meraki\Email\Address;
use Countable;
use IteratorAggregate;
use InvalidArgumentException;
use Traversable;
use ArrayIterator;
use RangeException;

final class AddressList implements Countable, IteratorAggregate
{
	private $addresses;

	public function __construct(Address $address, Address ...$addresses)
	{
		$this->addresses = array_merge([$address], $addresses);
	}

	public function add(Address $address): void
	{
		$this->addresses[] = $address;
	}

	public function remove(Address $address): void
	{
		if (count($this->addresses) === 1) {
			throw new RangeException('An address-list must have at least one address.');
		}

		$index = $this->indexOf($address);

		if ($index >= 0) {
			unset($this->addresses[$index]);
			return;
		}

		throw new InvalidArgumentException('Cannot remove an address that does not exist in the list.');
	}

	public function contains(Address $address): bool
	{
		foreach ($this->addresses as $storedAddress) {
			if ($storedAddress->equals($address)) {
				return true;
			}
		}

		return false;
	}

	public function indexOf(Address $address): int
	{
		foreach ($this->addresses as $index => $storedAddress) {
			if ($storedAddress->equals($address)) {
				return $index;
			}
		}

		return -1;
	}

	public function count(): int
	{
		return count($this->addresses);
	}

	public function getIterator(): Traversable
	{
		return new ArrayIterator($this->addresses);
	}

	public function __toString(): string
	{
		return implode(',', $this->addresses);
	}

	public function __toArray(): array
	{
		return $this->addresses;
	}

	public function equals(self $other): bool
	{
		return count(array_diff($this->addresses, $other->addresses)) === 0;
	}
}
