<?php
declare(strict_types=1);

namespace Meraki\Email;

use Meraki\Email\AddressTest;
use Meraki\Email\Address;
use Meraki\Email\Mailbox;

final class MailboxTest extends AddressTest
{
	/**
	 * @test
	 */
	public function display_name_is_set_when_created_with_it(): void
	{
		$expectedDisplayName = 'Test-Account';
		$mailbox = Mailbox::withDisplayName($expectedDisplayName, 'test@example.com');

		$actualDisplayName = (string) $mailbox->getDisplayName();

		$this->assertEquals($expectedDisplayName, $actualDisplayName);
	}

	/**
	 * @test
	 */
	public function display_name_is_not_available_when_created_without_it(): void
	{
		$mailbox = Mailbox::withoutDisplayName('test@example.com');

		$this->assertNull($mailbox->getDisplayName());
	}

	/**
	 * @test
	 */
	public function address_is_set_when_created_with_display_name(): void
	{
		$expectedAddress = 'test@example.com';
		$mailbox = Mailbox::withDisplayName('Test Account', $expectedAddress);

		$actualAddress = $mailbox->getAddress();

		$this->assertEquals($expectedAddress, $actualAddress);
	}

	/**
	 * @test
	 */
	public function address_is_set_when_created_without_display_name(): void
	{
		$expectedAddress = 'test@example.com';
		$mailbox = Mailbox::withoutDisplayName($expectedAddress);

		$actualAddress = $mailbox->getAddress();

		$this->assertEquals($expectedAddress, $actualAddress);
	}

	/**
	 * @test
	 */
	public function address_has_angle_brackets_surrounding_it_when_used_as_literal_value(): void
	{
		$email = 'test@example.com';
		$mailbox = Mailbox::withoutDisplayName($email);

		$mailbox = (string) $mailbox;

		$this->assertEquals(sprintf('<%s>', $email), $mailbox);
	}

	/**
	 * @test
	 */
	public function display_name_is_prefixed_to_angle_address_if_exists_when_used_as_literal_value(): void
	{
		$displayName = 'Test-Account';
		$email = 'test@example.com';
		$mailbox = Mailbox::withDisplayName($displayName, $email);

		$mailbox = (string) $mailbox;

		$this->assertEquals(sprintf('%s<%s>', $displayName, $email), $mailbox);
	}

	/**
	 * @test
	 */
	public function addresses_are_equal_if_they_have_the_same_value_regardless_of_case(): void
	{
		$email = 'TeST@exAMple.com';
		$mixedCase = Mailbox::withoutDisplayName($email);
		$lowerCase = Mailbox::withoutDisplayName(strtolower($email));
		$upperCase = Mailbox::withoutDisplayName(strtoupper($email));

		$this->assertTrue($mixedCase->equals($lowerCase));
		$this->assertTrue($mixedCase->equals($upperCase));
		$this->assertTrue($lowerCase->equals($upperCase));
	}

	protected function createAddress(string $displayName): Address
	{
		return Mailbox::withDisplayName($displayName, 'automated@example.com');
	}
}
