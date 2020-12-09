<?php
declare(strict_types=1);

namespace Meraki\Email;

use Meraki\Email\AddressTest;
use Meraki\Email\Address;
use Meraki\Email\Group;
use Meraki\Email\DisplayName;
use Meraki\Email\Mailbox;
use Meraki\Email\MailboxList;
use Countable;

final class GroupTest extends AddressTest
{
	/**
	 * @test
	 */
	public function group_has_a_display_name_when_created(): void
	{
		$expectedDisplayName = new DisplayName('Automated System');
		$group = new Group($expectedDisplayName);

		$actualDisplayName = $group->getDisplayName();

		$this->assertSame($expectedDisplayName, $actualDisplayName);
	}

	/**
	 * @test
	 */
	public function group_has_no_mailboxes_when_created(): void
	{
		$group = new Group(new DisplayName('Automated System'));

		$mailboxList = $group->getMailboxList();

		// a mailbox-list MUST have at least one mailbox, therefore, 
		// no mailboxes mean no mailbox-list. Ergo, return null
		$this->assertNull($mailboxList);
	}

	/**
	 * @test
	 */
	public function mailboxes_can_be_provided_to_group_when_created(): void
	{
		$expectedMailboxList = new MailboxList(Mailbox::withoutDisplayName('test@example.com'));
		$group = new Group(new DisplayName('Test Group'), $expectedMailboxList);

		$actualMailboxList = $group->getMailboxList();

		$this->assertSame($expectedMailboxList, $actualMailboxList);
	}

	/**
	 * @test
	 */
	public function two_groups_are_not_equal_if_the_display_names_are_different(): void
	{
		$group1 = $this->createGroup('Automated System');
		$group2 = $this->createGroup('No Replies');

		$this->assertFalse($group1->equals($group2));
	}

	/**
	 * @test
	 */
	public function two_groups_are_not_equal_if_display_names_are_different_but_mailbox_list_is_same(): void
	{
		$mailboxList = new MailboxList(Mailbox::withoutDisplayName('test@example.com'));
		$group1 = new Group(new DisplayName('Automated System 1'), $mailboxList);
		$group2 = new Group(new DisplayName('Automated System 2'), $mailboxList);

		$areEqual = $group1->equals($group2);

		$this->assertFalse($areEqual);
	}

	/**
	 * @test
	 */
	public function two_groups_are_not_equal_if_there_are_mailboxes_in_one_but_not_the_other(): void
	{
		$displayName = new DisplayName('Automated System');
		$mailboxList = new MailboxList(Mailbox::withoutDisplayName('test1@example.com'));
		$group1 = new Group($displayName, $mailboxList);
		$group2 = new Group($displayName);

		$this->assertFalse($group1->equals($group2));
	}

	/**
	 * @test
	 */
	public function two_groups_are_equal_if_they_have_the_same_display_name_regardless_of_case(): void
	{
		$displayName = 'Automated System';
		$mixedCase = $this->createGroup($displayName);
		$lowerCase = $this->createGroup(strtolower($displayName));
		$upperCase = $this->createGroup(strtoupper($displayName));

		$this->assertTrue($mixedCase->equals($lowerCase));
		$this->assertTrue($mixedCase->equals($upperCase));
		$this->assertTrue($lowerCase->equals($upperCase));
	}

	/**
	 * @test
	 */
	public function group_has_correct_syntatic_structure_without_grouplist(): void
	{
		$group = $this->createGroup('Automated System');

		$actual = (string) $group;

		$this->assertEquals('"Automated System":;', $actual);
	}

	/**
	 * @test
	 */
	public function group_has_correct_syntatic_structure_with_grouplist(): void
	{
		$displayName = new DisplayName('IT Support Team');
		$johnDoe = Mailbox::withDisplayName('John Doe', 'john.doe@example.com');
		$janeDoe = Mailbox::withDisplayName('Jane Doe', 'jane.doe@example.com');
		$group = new Group($displayName, new MailboxList($johnDoe, $janeDoe));

		$actual = (string) $group;

		$this->assertEquals(
			'"IT Support Team":"John Doe"<john.doe@example.com>,"Jane Doe"<jane.doe@example.com>;',
			$actual
		);
	}
	
	protected function createAddress(string $displayName): Address
	{
		return $this->createGroup($displayName);
	}

	private function createGroup(string $displayName): Group
	{
		return new Group(new DisplayName($displayName));
	}
}
