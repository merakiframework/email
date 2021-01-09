<?php
declare(strict_types=1);

namespace Meraki\Email\Message\Field;

use Meraki\Email\Message\Field\From as FromField;
use Meraki\Email\Message\Field as HeaderField;
use Meraki\Email\Mailbox;
use Meraki\Email\MailboxList;
use Meraki\Email\Message\FieldTest;

/**
 * @covers FromField::
 */
final class FromTest extends FieldTest
{
	/**
	 * @test
	 */
	public function has_correct_name(): void
	{
		$field = $this->createField();

		$actualName = $field->name();

		$this->assertEquals('From', $actualName);
	}

	/**
	 * @test
	 */
	public function can_get_mailbox_list_as_body(): void
	{
		$mailbox = Mailbox::withDisplayName('Test Account', 'test@example.com');
		$mailboxList = new MailboxList($mailbox);
		$field = new FromField($mailboxList);
		$expectedBody = (string) $mailboxList;

		$actualBody = $field->body();

		$this->assertEquals($expectedBody, $actualBody);
	}

	/**
	 * @test
	 */
	public function can_get_mailbox_list(): void
	{
		$mailbox = Mailbox::withDisplayName('Test Account', 'test@example.com');
		$expectedMailboxList = new MailboxList($mailbox);
		$field = new FromField($expectedMailboxList);

		$actualMailboxList = $field->mailboxList();

		$this->assertSame($expectedMailboxList, $actualMailboxList);
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
	public function must_appear_in_message(): void
	{
		$field = $this->createField();

		$isRequired = $field->required();

		$this->assertTrue($isRequired);
	}

	protected function createField(): HeaderField
	{
		$mailbox = Mailbox::withDisplayName('Test Account', 'test@example.com');

		return new FromField(new MailboxList($mailbox));
	}
}
