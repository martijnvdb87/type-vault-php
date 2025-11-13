<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\Month;
use PHPUnit\Framework\TestCase;

class MonthTest extends TestCase
{
    public function testItSetsValueCorrectly(): void
    {
        $values = [
            Month::January(),
            Month::February(),
            Month::March(),
            Month::April(),
            Month::May(),
            Month::June(),
            Month::July(),
            Month::August(),
            Month::September(),
            Month::October(),
            Month::November(),
            Month::December()
        ];

        foreach ($values as $value) {
            $month = new Month($value->value);
            $this->assertEquals($value->value, $month->value);
        }
    }

    public function testItShouldThrowExceptionWhenValueIsInvalid(): void
    {
        $values = [
            'foo',
            'foo@example',
            'foo@example.',
            '1',
            [],
            true,
            false,
            null
        ];

        foreach ($values as $value) {
            try {
                new Month($value);
                $this->fail();
            } catch (TypeVaultValidationError $expected) {
                $this->assertInstanceOf(TypeVaultValidationError::class, $expected);
            }
        }
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $month = new Month(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($month->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new Month(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $month = new Month('january', new TypeOptionsDTO(immutable: false));

        $newMonth = 'february';

        $month->value = $newMonth;
        $this->assertEquals($newMonth, $month->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $month = new Month('january', new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $month->value = 'february';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $month = Month::nullable();
        $this->assertNull($month->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        Month::immutable('january')->value = 'february';
    }
}
