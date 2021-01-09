<?php
declare(strict_types=1);

namespace Meraki\Email\Message\Field;

use Meraki\Email\Message\Field\MessageId as MessageIdField;
use Meraki\Email\Message\Field as HeaderField;
use Meraki\Email\Mailbox;
use Meraki\Email\Message\FieldTest;

/**
 * @covers MessageIdField::
 */
final class MessageIdTest extends FieldTest
{
	/**
	 * @test
	 */
	public function has_correct_name(): void
	{
		$field = $this->createField();

		$actualName = $field->name();

		$this->assertEquals('Message-ID', $actualName);
	}

	/**
	 * @test
	 */
	public function can_get_body_surrounded_by_angle_brackets(): void
	{
		$id = '1234@local.machine.example';
		$field = new MessageIdField($id);

		$actualBody = $field->body();

		$this->assertEquals(sprintf('<%s>', $id), $actualBody);
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
		return new MessageIdField('1234@local.machine.example');
	}
}
