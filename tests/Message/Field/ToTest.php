<?php
declare(strict_types=1);

namespace Meraki\Email\Message\Field;

use Meraki\Email\Message\Field\To as ToField;
use Meraki\Email\Message\Field as HeaderField;
use Meraki\Email\Mailbox;
use Meraki\Email\AddressList;
use Meraki\Email\Message\FieldTest;

/**
 * @covers ToField::
 */
final class ToTest extends FieldTest
{
	/**
	 * @test
	 */
	public function has_correct_name(): void
	{
		$field = $this->createField();

		$actualName = $field->name();

		$this->assertEquals('To', $actualName);
	}

	/**
	 * @test
	 */
	public function can_get_address_list_as_body(): void
	{
		$mailbox = Mailbox::withDisplayName('Test Account', 'test@example.com');
		$addressList = new AddressList($mailbox);
		$field = new ToField($addressList);
		$expectedBody = (string) $addressList;

		$actualBody = $field->body();

		$this->assertEquals($expectedBody, $actualBody);
	}

	/**
	 * @test
	 */
	public function can_get_address_list(): void
	{
		$mailbox = Mailbox::withDisplayName('Test Account', 'test@example.com');
		$expectedAddressList = new AddressList($mailbox);
		$field = new ToField($expectedAddressList);

		$actualAddressList = $field->addressList();

		$this->assertSame($expectedAddressList, $actualAddressList);
	}

	/**
	 * @test
	 */
	public function can_only_occur_once_in_message(): void
	{
		$field = $this->createField();

		$occurrenceCount = $field->occurrenceCount();

		$this->assertEquals(1, $occurrenceCount);
	}

	/**
	 * @test
	 */
	public function does_not_have_to_appear_in_message(): void
	{
		$field = $this->createField();

		$isRequired = $field->required();

		$this->assertFalse($isRequired);
	}

	protected function createField(): HeaderField
	{
		$mailbox = Mailbox::withDisplayName('Test Account', 'test@example.com');

		return new ToField(new AddressList($mailbox));
	}
}
