<?php
declare(strict_types=1);

namespace Meraki\Email;

use Meraki\Email\AddressList;
use Meraki\Email\Mailbox;
use Meraki\Email\Address;
use Meraki\Email\Group;
use Meraki\TestSuite\TestCase;
use Countable;
use IteratorAggregate;
use RangeException;

/**
 * @covers AddressList::
 */
final class AddressListTest extends TestCase
{
	/**
	 * @test
	 */
	public function address_provided_during_creation_is_added(): void
	{
		$mailbox = Mailbox::withoutDisplayName('test@example.com');

		$addressList = new AddressList($mailbox);

		$this->assertNotEmpty($addressList);
		$this->assertCount(1, $addressList);
		$this->assertTrue($addressList->contains($mailbox));
	}

	/**
	 * @test
	 */
	public function can_be_counted(): void
	{
		$mailbox = Mailbox::withoutDisplayName('test@example.com');

		$addressList = new AddressList($mailbox);

		$this->assertInstanceOf(Countable::class, $addressList);
	}

	/**
	 * @test
	 */
	public function can_be_looped_over(): void
	{
		$mailbox = Mailbox::withoutDisplayName('test@example.com');

		$addressList = new AddressList($mailbox);

		$this->assertInstanceOf(IteratorAggregate::class, $addressList);
	}

	/**
	 * @test
	 */
	public function adding_an_address_increases_the_count(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$addressList = new AddressList($mailbox1);

		$addressList->add($mailbox2);

		$this->assertCount(2, $addressList);
	}

	/**
	 * @test
	 */
	public function address_exists_when_added(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$addressList = new AddressList($mailbox1);

		$addressList->add($mailbox2);

		$this->assertTrue($addressList->contains($mailbox2));
	}

	/**
	 * @test
	 */
	public function removing_an_address_decreases_the_count(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$addressList = new AddressList($mailbox1, $mailbox2);

		$addressList->remove($mailbox2);

		$this->assertCount(1, $addressList);
	}

	/**
	 * @test
	 */
	public function address_doesnt_exist_when_removed(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$addressList = new AddressList($mailbox1, $mailbox2);

		$addressList->remove($mailbox2);

		$this->assertFalse($addressList->contains($mailbox2));
	}

	/**
	 * @test
	 */
	public function list_cannot_be_made_empty_by_removing_an_address(): void
	{
		$exception = new RangeException('An address-list must have at least one address.');
		$mailbox = Mailbox::withoutDisplayName('test@example.com');
		$addressList = new AddressList($mailbox);

		$this->expectExceptionObject($exception);

		$addressList->remove($mailbox);
	}

	/**
	 * @test
	 */
	public function address_doesnt_exist_in_list_if_not_added(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$addressList = new AddressList($mailbox1);

		$this->assertFalse($addressList->contains($mailbox2));
	}

	/**
	 * @test
	 */
	public function two_address_lists_are_not_equal_if_they_differ_in_address_count(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$addressList1 = new AddressList($mailbox1, $mailbox2);
		$addressList2 = new AddressList($mailbox1);

		$this->assertFalse($addressList1->equals($addressList2));
	}

	/**
	 * @test
	 */
	public function two_address_lists_are_not_equal_if_they_have_different_addresses(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$addressList1 = new AddressList($mailbox1);
		$addressList2 = new AddressList($mailbox2);

		$this->assertFalse($addressList1->equals($addressList2));
	}

	/**
	 * @test
	 */
	public function two_address_lists_are_equal_if_they_have_same_addresses(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$addressList1 = new AddressList($mailbox1, $mailbox2);
		$addressList2 = new AddressList($mailbox1, $mailbox2);

		$this->assertTrue($addressList1->equals($addressList2));
	}

	/**
	 * @test
	 */
	public function two_address_lists_are_equal_if_they_have_same_addresses_regardless_of_order(): void
	{
		$mailbox1 = Mailbox::withoutDisplayName('test1@example.com');
		$mailbox2 = Mailbox::withoutDisplayName('test2@example.com');
		$addressList1 = new AddressList($mailbox1, $mailbox2);
		$addressList2 = new AddressList($mailbox2, $mailbox1);

		$this->assertTrue($addressList1->equals($addressList2));
	}

	/**
	 * @test
	 */
	public function casting_to_string_returns_addresses_separated_by_commas(): void
	{
		$addressList = new AddressList(
			Mailbox::withDisplayName('Nathan Bishop', 'nbish11@hotmail.com'),
			Mailbox::withoutDisplayName('test@example.com')
		);

		$builtaddressList = (string) $addressList;

		$this->assertEquals('"Nathan Bishop"<nbish11@hotmail.com>,<test@example.com>', $builtaddressList);
	}
}
