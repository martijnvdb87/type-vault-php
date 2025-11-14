<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\FloatingPoint;
use PHPUnit\Framework\TestCase;

class FloatingPointTest extends TestCase
{
    /** @var array<int|float> */
    private array $values = [
        PHP_INT_MIN,
        -99 - 1,
        0,
        1,
        99,
        PHP_INT_MAX,
        -2,
        -1.9,
        -1.1,
        -1,
        -0.9,
        -0.1,
        0,
        0.1,
        0.9,
        1,
        1.1,
        1.9,
        2,
        3.14159265359
    ];

    public function testItSetsValueCorrectly(): void
    {
        foreach ($this->values as $value) {
            $floatingPoint = new FloatingPoint($value);
            $this->assertEquals($value, $floatingPoint->value);
        }
    }

    public function testItShouldThrowExceptionWhenValueIsInvalid(): void
    {
        $values = [
            'foo',
            'foo@example',
            'foo@example.',
            [],
            true,
            false,
            null,
        ];

        foreach ($values as $value) {
            try {
                new FloatingPoint($value);
                $this->fail();
            } catch (TypeVaultValidationError $expected) {
                $this->assertInstanceOf(TypeVaultValidationError::class, $expected);
            }
        }
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $floatingPoint = new FloatingPoint(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($floatingPoint->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new FloatingPoint(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $floatingPoint = new FloatingPoint(1.23, new TypeOptionsDTO(immutable: false));

        $newFloatingPoint = 4.56;

        $floatingPoint->value = $newFloatingPoint;
        $this->assertEquals($newFloatingPoint, $floatingPoint->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $floatingPoint = new FloatingPoint(1.23, new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $floatingPoint->value = 4.56;
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $floatingPoint = FloatingPoint::nullable();
        $this->assertNull($floatingPoint->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        FloatingPoint::immutable(1.23)->value = 4.56;
    }
}
