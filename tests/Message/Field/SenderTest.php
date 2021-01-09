<?php
declare(strict_types=1);

namespace Meraki\Email\Message\Field;

use Meraki\Email\Message\Field\Sender as SenderField;
use Meraki\Email\Message\Field as HeaderField;
use Meraki\Email\Mailbox;
use Meraki\Email\Message\FieldTest;

/**
 * @covers SenderField::
 */
final class SenderTest extends FieldTest
{
	/**
	 * @test
	 */
	public function has_correct_name(): void
	{
		$field = $this->createField();

		$actualName = $field->name();

		$this->assertEquals('Sender', $actualName);
	}

	/**
	 * @test
	 */
	public function can_get_mailbox_as_body(): void
	{
		$mailbox = Mailbox::withDisplayName('Test Account', 'test@example.com');
		$field = new SenderField($mailbox);
		$expectedBody = (string) $mailbox;

		$actualBody = $field->body();

		$this->assertEquals($expectedBody, $actualBody);
	}

	/**
	 * @test
	 */
	public function can_get_mailbox(): void
	{
		$expectedMailbox = Mailbox::withDisplayName('Test Account', 'test@example.com');
		$field = new SenderField($expectedMailbox);

		$actualMailbox = $field->mailbox();

		$this->assertSame($expectedMailbox, $actualMailbox);
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
	public function is_not_required_to_appear_in_message(): void
	{
		$field = $this->createField();

		$isRequired = $field->required();

		$this->assertFalse($isRequired);
	}

	protected function createField(): HeaderField
	{
		$mailbox = Mailbox::withDisplayName('Test Account', 'test@example.com');

		return new SenderField($mailbox);
	}
}
