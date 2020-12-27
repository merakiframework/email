<?php
declare(strict_types=1);

namespace Meraki\Email;

use Meraki\Email\GroupList;
use Meraki\Email\Mailbox;
use Meraki\TestSuite\TestCase;
use Countable;
use IteratorAggregate;
use RangeException;

final class GroupListTest extends TestCase
{
	/**
	 * @test
	 */
	public function mailbox_provided_during_creation_is_added(): void
	{
		$mailbox = Mailbox::withoutDisplayName('test@example.com');

		$groupList = new GroupList($mailbox);

		$this->assertNotEmpty($groupList);
		$this->assertCount(1, $groupList);
		$this->assertTrue($groupList->contains($mailbox));
	}

	/**
	 * @test
	 */
	public function can_be_counted(): void
	{
		$mailbox = Mailbox::withoutDisplayName('test@example.com');

		$groupList = new GroupList($mailbox);

		$this->assertInstanceOf(Countable::class, $groupList);
	}

	/**
	 * @test
	 */
	public function can_be_looped_over(): void
	{
		$mailbox = Mailbox::withoutDisplayName('test@example.com');

		$groupList = new GroupList($mailbox);

		$this->assertInstanceOf(IteratorAggregate::class, $groupList);
	}

	/**
	 * @test
	 */
	public function adding_a_mailbox_increases_the_count(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$groupList = new GroupList($mailbox1);

		$groupList->add($mailbox2);

		$this->assertCount(2, $groupList);
	}

	/**
	 * @test
	 */
	public function mailbox_exists_when_added(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$groupList = new GroupList($mailbox1);

		$groupList->add($mailbox2);

		$this->assertTrue($groupList->contains($mailbox2));
	}

	/**
	 * @test
	 */
	public function removing_a_mailbox_decreases_the_count(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$groupList = new GroupList($mailbox1, $mailbox2);

		$groupList->remove($mailbox2);

		$this->assertCount(1, $groupList);
	}

	/**
	 * @test
	 */
	public function mailbox_doesnt_exist_when_removed(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$groupList = new GroupList($mailbox1, $mailbox2);

		$groupList->remove($mailbox2);

		$this->assertFalse($groupList->contains($mailbox2));
	}

	/**
	 * @test
	 */
	public function list_cannot_be_made_empty_by_removing_mailboxes(): void
	{
		$exception = new RangeException('A mailbox-list must have at least one mailbox.');
		$mailbox = Mailbox::withoutDisplayName('test@example.com');
		$groupList = new GroupList($mailbox);

		$this->expectExceptionObject($exception);

		$groupList->remove($mailbox);
	}

	/**
	 * @test
	 */
	public function mailbox_doesnt_exist_in_list_if_not_added(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$groupList = new GroupList($mailbox1);

		$this->assertFalse($groupList->contains($mailbox2));
	}

	/**
	 * @test
	 */
	public function two_mailbox_lists_are_not_equal_if_they_differ_in_mailbox_count(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$groupList1 = new GroupList($mailbox1, $mailbox2);
		$groupList2 = new GroupList($mailbox1);

		$this->assertFalse($groupList1->equals($groupList2));
	}

	/**
	 * @test
	 */
	public function two_mailbox_lists_are_not_equal_if_they_have_different_mailboxes(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$groupList1 = new GroupList($mailbox1);
		$groupList2 = new GroupList($mailbox2);

		$this->assertFalse($groupList1->equals($groupList2));
	}

	/**
	 * @test
	 */
	public function two_mailbox_lists_are_equal_if_they_have_same_mailboxes(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$groupList1 = new GroupList($mailbox1, $mailbox2);
		$groupList2 = new GroupList($mailbox1, $mailbox2);

		$this->assertTrue($groupList1->equals($groupList2));
	}

	/**
	 * @test
	 */
	public function two_mailbox_lists_are_equal_if_they_have_same_mailboxes_regardless_of_order(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$groupList1 = new GroupList($mailbox1, $mailbox2);
		$groupList2 = new GroupList($mailbox2, $mailbox1);

		$this->assertTrue($groupList1->equals($groupList2));
	}

	/**
	 * @test
	 */
	public function casting_to_string_returns_mailboxes_separated_by_commas(): void
	{
		$groupList = new GroupList(
			Mailbox::withDisplayName('Nathan Bishop', 'nbish11@hotmail.com'),
			Mailbox::withoutDisplayName('test@example.com')
		);

		$builtMailboxList = (string) $groupList;

		$this->assertEquals('"Nathan Bishop"<nbish11@hotmail.com>,<test@example.com>', $builtMailboxList);
	}
}
