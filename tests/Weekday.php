<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\Weekday;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class WeekdayTest extends TestCase
{
    public function testItSetsValueCorrectly(): void
    {
        $values = [
            Weekday::Monday(),
            Weekday::Tuesday(),
            Weekday::Wednesday(),
            Weekday::Thursday(),
            Weekday::Friday(),
            Weekday::Saturday(),
            Weekday::Sunday(),
        ];

        foreach ($values as $value) {
            $weekday = new Weekday($value->value);
            $this->assertEquals($value->value, $weekday->value);
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
        new Weekday($value);
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $weekday = new Weekday(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($weekday->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new Weekday(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $weekday = new Weekday('monday', new TypeOptionsDTO(immutable: false));

        $newWeekday = 'tuesday';

        $weekday->value = $newWeekday;
        $this->assertEquals($newWeekday, $weekday->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $weekday = new Weekday('monday', new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $weekday->value = 'tuesday';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $weekday = Weekday::nullable();
        $this->assertNull($weekday->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        Weekday::immutable('monday')->value = 'tuesday';
    }
}
