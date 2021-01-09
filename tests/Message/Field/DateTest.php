<?php
declare(strict_types=1);

namespace Meraki\Email\Message\Field;

use Meraki\Email\Message\Field as HeaderField;
use Meraki\Email\Message\Field\Date as DateField;
use Meraki\Email\Message\FieldTest;
use DateTimeImmutable as DateTime;

/**
 * @covers DateField::
 */
final class DateTest extends FieldTest
{
	/**
	 * @test
	 */
	public function has_the_correct_name(): void
	{
		$dateField = $this->createField();

		$fieldName = $dateField->name();

		$this->assertEquals('Date', $fieldName);
	}

	/**
	 * @test
	 */
	public function can_get_datetime_as_field_body(): void
	{
		$dateField = $this->createField();

		$fieldBody = $dateField->body();

		$this->assertEquals('Thu, 01 Jan 1970 00:00:01 +0000', $fieldBody);
	}

	/**
	 * @test
	 */
	public function can_get_datetime_instance(): void
	{
		$expectedDateTime = new DateTime('@1');
		$dateField = new DateField($expectedDateTime);

		$actualDateTime = $dateField->dateTime();

		$this->assertSame($expectedDateTime, $actualDateTime);
	}

	/**
	 * @test
	 */
	public function must_appear_in_message_header(): void
	{
		$dateField = $this->createField();

		$isRequired = $dateField->required();

		$this->assertTrue($isRequired);
	}

	/**
	 * @test
	 */
	public function can_only_occur_once_in_message_header(): void
	{
		$dateField = $this->createField();

		$occurrenceCount = $dateField->occurrenceCount();

		$this->assertEquals(1, $occurrenceCount);
	}

	/**
	 * @test
	 */
	public function can_get_as_field_line(): void
	{
		$dateField = $this->createField();

		$fieldLine = (string) $dateField;

		$this->assertEquals("Date: Thu, 01 Jan 1970 00:00:01 +0000\r\n", $fieldLine);
	}

	protected function createField(): HeaderField
	{
		return new DateField(new DateTime('@1'));
	}
}
