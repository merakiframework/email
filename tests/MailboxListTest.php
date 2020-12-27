<?php
declare(strict_types=1);

namespace Meraki\Email;

use Meraki\Email\MailboxList;
use Meraki\Email\Mailbox;
use Meraki\TestSuite\TestCase;
use Countable;
use IteratorAggregate;

final class MailboxListTest extends TestCase
{
	/**
	 * @test
	 */
	public function mailbox_provided_during_creation_is_added(): void
	{
		$mailbox = Mailbox::withoutDisplayName('test@example.com');

		$mailboxList = new MailboxList($mailbox);

		$this->assertNotEmpty($mailboxList);
		$this->assertCount(1, $mailboxList);
		$this->assertTrue($mailboxList->contains($mailbox));
	}

	/**
	 * @test
	 */
	public function can_be_counted(): void
	{
		$mailbox = Mailbox::withoutDisplayName('test@example.com');

		$mailboxList = new MailboxList($mailbox);

		$this->assertInstanceOf(Countable::class, $mailboxList);
	}

	/**
	 * @test
	 */
	public function can_be_looped_over(): void
	{
		$mailbox = Mailbox::withoutDisplayName('test@example.com');

		$mailboxList = new MailboxList($mailbox);

		$this->assertInstanceOf(IteratorAggregate::class, $mailboxList);
	}

	/**
	 * @test
	 */
	public function adding_a_mailbox_increases_the_count(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$mailboxList = new MailboxList($mailbox1);

		$mailboxList->add($mailbox2);

		$this->assertCount(2, $mailboxList);
	}

	/**
	 * @test
	 */
	public function mailbox_exists_when_added(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$mailboxList = new MailboxList($mailbox1);

		$mailboxList->add($mailbox2);

		$this->assertTrue($mailboxList->contains($mailbox2));
	}

	/**
	 * @test
	 */
	public function removing_a_mailbox_decreases_the_count(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$mailboxList = new MailboxList($mailbox1, $mailbox2);

		$mailboxList->remove($mailbox2);

		$this->assertCount(1, $mailboxList);
	}

	/**
	 * @test
	 */
	public function mailbox_doesnt_exist_when_removed(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$mailboxList = new MailboxList($mailbox1, $mailbox2);

		$mailboxList->remove($mailbox2);

		$this->assertFalse($mailboxList->contains($mailbox2));
	}

	/**
	 * @test
	 */
	public function list_cannot_be_made_empty_by_removing_mailboxes(): void
	{
		$exception = new RangeException('A mailbox-list must have at least one mailbox.');
		$mailbox = Mailbox::withoutDisplayName('test@example.com');
		$mailboxList = new MailboxList($mailbox);

		$this->expectExceptionObject($exception);

		$mailboxList->remove($mailbox);
	}

	/**
	 * @test
	 */
	public function mailbox_doesnt_exist_in_list_if_not_added(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$mailboxList = new MailboxList($mailbox1);

		$this->assertFalse($mailboxList->contains($mailbox2));
	}

	/**
	 * @test
	 */
	public function two_mailbox_lists_are_not_equal_if_they_differ_in_mailbox_count(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$mailboxList1 = new MailboxList($mailbox1, $mailbox2);
		$mailboxList2 = new MailboxList($mailbox1);

		$this->assertFalse($mailboxList1->equals($mailboxList2));
	}

	/**
	 * @test
	 */
	public function two_mailbox_lists_are_not_equal_if_they_have_different_mailboxes(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$mailboxList1 = new MailboxList($mailbox1);
		$mailboxList2 = new MailboxList($mailbox2);

		$this->assertFalse($mailboxList1->equals($mailboxList2));
	}

	/**
	 * @test
	 */
	public function two_mailbox_lists_are_equal_if_they_have_same_mailboxes(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$mailboxList1 = new MailboxList($mailbox1, $mailbox2);
		$mailboxList2 = new MailboxList($mailbox1, $mailbox2);

		$this->assertTrue($mailboxList1->equals($mailboxList2));
	}

	/**
	 * @test
	 */
	public function two_mailbox_lists_are_equal_if_they_have_same_mailboxes_regardless_of_order(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$mailboxList1 = new MailboxList($mailbox1, $mailbox2);
		$mailboxList2 = new MailboxList($mailbox2, $mailbox1);

		$this->assertTrue($mailboxList1->equals($mailboxList2));
	}

	/**
	 * @test
	 */
	public function casting_to_string_returns_mailboxes_separated_by_commas(): void
	{
		$mailboxList = new MailboxList(
			Mailbox::withDisplayName('Nathan Bishop', 'nbish11@hotmail.com'),
			Mailbox::withoutDisplayName('test@example.com')
		);

		$builtMailboxList = (string) $mailboxList;

		$this->assertEquals('"Nathan Bishop"<nbish11@hotmail.com>,<test@example.com>', $builtMailboxList);
	}
}
