<?php
declare(strict_types=1);

namespace Meraki\Email;

use Meraki\Email\Mailbox;
use Countable;
use IteratorAggregate;
use InvalidArgumentException;
use Iterator;
use ArrayIterator;
use RangeException;

// A "group-list" is also a "mailbox-list"
class MailboxList implements Countable, IteratorAggregate
{
	private $mailboxes;

	public function __construct(Mailbox $mailbox, Mailbox ...$mailboxes)
	{
		$this->mailboxes = array_merge([$mailbox], $mailboxes);
	}

	public function add(Mailbox $mailbox): void
	{
		if ($this->contains($mailbox)) {
			throw new InvalidArgumentException('Cannot add the same mailbox more than once.');
		}

		$this->mailboxes[] = $mailbox;
	}

	public function remove(Mailbox $mailbox): void
	{
		if (count($this->mailboxes) === 1) {
			throw new RangeException('A mailbox-list must have at least one mailbox.');
		}

		$index = $this->indexOf($mailbox);

		if ($index >= 0) {
			unset($this->mailboxes[$index]);

			return;
		}

		throw new InvalidArgumentException('Cannot remove an mailbox that does not exist in the list.');
	}

	public function contains(Mailbox $mailbox): bool
	{
		foreach ($this->mailboxes as $storedMailbox) {
			if ($storedMailbox->equals($mailbox)) {
				return true;
			}
		}

		return false;
	}

	public function indexOf(Mailbox $mailbox): int
	{
		foreach ($this->mailboxes as $index => $storedMailbox) {
			if ($storedMailbox->equals($mailbox)) {
				return $index;
			}
		}

		return -1;
	}

	public function equals(self $other): bool
	{
		if (count($this->mailboxes) === count($other->mailboxes)) {
            foreach ($this->mailboxes as $thisMailbox) {
                foreach ($other->mailboxes as $otherMailbox) {
                    if ($thisMailbox->equals($otherMailbox)) {
                        continue 2;
                    }
                }
                
                return false;
            }
            
            return true;
        }
        
        return false;
	}

	public function count(): int
	{
		return count($this->mailboxes);
	}

	public function getIterator(): Iterator
	{
		return new ArrayIterator($this->mailboxes);
	}

	public function __toString(): string
	{
		return implode(',', $this->mailboxes);
	}
}
