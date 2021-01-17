<?php
declare(strict_types=1);

namespace Meraki\Email\Message\Field;

use Meraki\Email\Message\Field\InReplyTo as InReplyToField;
use Meraki\Email\Message\Field as HeaderField;
use Meraki\Email\Mailbox;
use Meraki\Email\Message\FieldTest;

/**
 * @covers InReplyToField::
 */
final class InReplyToTest extends FieldTest
{
	/**
	 * @test
	 */
	public function has_correct_name(): void
	{
		$field = $this->createField();

		$actualName = $field->name();

		$this->assertEquals('In-Reply-To', $actualName);
	}

	/**
	 * @test
	 */
	public function can_get_body_surrounded_by_angle_brackets(): void
	{
		$id = '1234@local.machine.example';
		$field = new InReplyToField($id);

		$actualBody = $field->body();

		$this->assertEquals(sprintf('<%s>', $id), $actualBody);
	}

	/**
	 * @test
	 */
	public function can_get_body_with_multiple_ids_surrounded_by_angle_brackets_and_separated_by_space(): void
	{
		$id1 = '1234@local.machine.example';
		$id2 = '5678@local.machine.example';
		$field = new InReplyToField($id1, $id2);

		$actualBody = $field->body();

		$this->assertEquals(sprintf('<%s> <%s>', $id1, $id2), $actualBody);
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
		return new InReplyToField('1234@local.machine.example');
	}
}
