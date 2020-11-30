<?php
declare(strict_types=1);

namespace Meraki\Email;

use Meraki\Email\Address;
use Meraki\TestSuite\TestCase;

abstract class AddressTest extends TestCase
{
	/**
	 * @test
	 */
	public function is_an_address(): void
	{
		$address = $this->createAddress('Automated-System');

		$this->assertInstanceOf(Address::class, $address);
	}

	/**
	 * @test
	 */
	public function can_get_display_name(): void
	{
		$displayName = 'Automated-System';
		$address = $this->createAddress($displayName);

		$this->assertEquals($displayName, $address->getDisplayName());
	}

	abstract protected function createAddress(string $displayName): Address;
}
