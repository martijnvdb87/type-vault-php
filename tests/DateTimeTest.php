<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\DateTime;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    /** @var array<int, array<string, string>> */
    private array $values = [
        [
            'input' => '1-2-3T4:5:6.7Z',
            'output' => '0001-02-03T04:05:06.700Z',
        ],
        [
            'input' => '0001-01-02T01:23:45.123Z',
            'output' => '0001-01-02T01:23:45.123Z',
        ],
        [
            'input' => '2023-01-02T01:23:45.123Z',
            'output' => '2023-01-02T01:23:45.123Z',
        ],
        [
            'input' => '2023-01-02T01:23:45Z',
            'output' => '2023-01-02T01:23:45.000Z',
        ],
        [
            'input' => '1000-01-01T00:00:00.000Z',
            'output' => '1000-01-01T00:00:00.000Z',
        ],
        [
            'input' => '9999-12-31T23:59:59.999Z',
            'output' => '9999-12-31T23:59:59.999Z',
        ],
    ];

    public function testItSetsValueCorrectly(): void
    {
        foreach ($this->values as $value) {
            $dateTime = new DateTime($value['input']);
            $this->assertEquals($value['output'], $dateTime->value);
        }
    }

    /**
     * @return array<mixed>
     */
    public static function invalidDataSet(): array
    {
        return [
            ['foo'],
            ['#foo'],
            ['foo@example'],
            ['foo@example.'],
            [1],
            [[]],
            [true],
            [false],
            [null],
        ];
    }

    #[DataProviderExternal(self::class, 'invalidDataSet')]
    public function testItShouldThrowExceptionWhenValueIsInvalid(mixed $value): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new DateTime($value);
    }

    public function testItShouldReturnDateTime(): void
    {
        $DateTimeString = '2000-01-01T01:23:45.123Z';
        $dateTime = new DateTime($DateTimeString);

        $this->assertInstanceOf(\DateTime::class, $dateTime->toDateTime());
        $this->assertEquals($DateTimeString, $this->getDateTimeFormat($dateTime->toDateTime()));
    }

    public function testItShouldReturnDateTimeFromDateTime(): void
    {
        $dateTime = DateTime::fromDateTime(new \DateTime('2000-01-01T01:23:45.123Z'));
        $this->assertEquals('2000-01-01T01:23:45.123Z', $dateTime->value);
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $dateTime = new DateTime(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($dateTime->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new DateTime(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $dateTime = new DateTime('2000-01-01T01:23:45.123Z', new TypeOptionsDTO(immutable: false));

        $newDateTime = '2001-02-02T12:45:06.456Z';

        $dateTime->value = $newDateTime;
        $this->assertEquals($newDateTime, $dateTime->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $dateTime = new DateTime('2000-01-01T01:23:45.123Z', new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $dateTime->value = '2001-02-02T12:45:06.456Z';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $dateTime = DateTime::nullable();
        $this->assertNull($dateTime->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        DateTime::immutable('2000-01-01T01:23:45.123Z')->value = '2001-02-02T12:45:06.456Z';
    }

    private function getDateTimeFormat(\DateTime $dateTime): string
    {
        $microsecondString = substr($dateTime->format('u'), 0, 3);

        return $dateTime->format('Y-m-d\TH:i:s') . '.' . $microsecondString . 'Z';
    }
}
