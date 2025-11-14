<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\Duration;
use PHPUnit\Framework\TestCase;

class DurationTest extends TestCase
{
    /** @var array<int, array<string, string>> */
    private array $values = [
        [
            'input' => 'P3Y6M4DT12H30M5S',
            'output' => 'P3Y6M0W4DT12H30M5S',
        ],
        [
            'input' => 'P0Y0M0DT0H0M0S',
            'output' => 'P0Y0M0W0DT0H0M0S',
        ],
        [
            'input' => 'P1Y',
            'output' => 'P1Y0M0W0DT0H0M0S',
        ],
        [
            'input' => 'P1M',
            'output' => 'P0Y1M0W0DT0H0M0S',
        ],
        [
            'input' => 'P1D',
            'output' => 'P0Y0M0W1DT0H0M0S',
        ],
        [
            'input' => 'PT1H',
            'output' => 'P0Y0M0W0DT1H0M0S',
        ],
        [
            'input' => 'PT1M',
            'output' => 'P0Y0M0W0DT0H1M0S',
        ],
        [
            'input' => 'PT1S',
            'output' => 'P0Y0M0W0DT0H0M1S',
        ],
        [
            'input' => 'P0.1MT2.3S',
            'output' => 'P0Y0.1M0W0DT0H0M2.3S',
        ],
    ];

    public function testItSetsValueCorrectly(): void
    {
        foreach ($this->values as $value) {
            $duration = new Duration($value['input']);
            $this->assertEquals($value['output'], $duration->value);
        }
    }

    public function testItShouldThrowExceptionWhenValueIsSupported(): void
    {
        $values = [
            '3Y6M4DT12H30M5S',
            'P3Y6M4D12H30M5S',
            '',
            'P3Y6M4DT12H30M5',
            '1234567890',
            'P3Y6M4DT12H30M5Sx',
            '2000-01-01T00:00:00.000Z',
            '2000-01-01',
            '00:00:00',
        ];

        $this->expectException(TypeVaultValidationError::class);

        foreach ($values as $value) {
            new Duration($value);
        }
    }

    public function testItShouldThrowExceptionWhenValueIsInvalid(): void
    {
        $values = [
            'example',
            '#foo',
            null,
            1,
            [],
            true,
            false,
        ];

        foreach ($values as $value) {
            try {
                new Duration($value);
                $this->fail();
            } catch (TypeVaultValidationError $expected) {
                $this->assertInstanceOf(TypeVaultValidationError::class, $expected);
            }
        }
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $duration = new Duration(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($duration->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new Duration(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $duration = new Duration('P3Y6M4DT12H30M5S', new TypeOptionsDTO(immutable: false));

        $newDuration = 'P0Y0M0W0DT0H0M0S';

        $duration->value = $newDuration;
        $this->assertEquals($newDuration, $duration->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $duration = new Duration('P3Y6M4DT12H30M5S', new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $duration->value = 'P0Y0M0W0DT0H0M0S';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $duration = Duration::nullable();
        $this->assertNull($duration->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        Duration::immutable('P3Y6M4DT12H30M5S')->value = 'P0Y0M0W0DT0H0M0S';
    }
}
