<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    /** @var string[] */
    private array $values = [
        'user@example.com',
        'hello.world@domain.net',
        'newsletter+weekly@service.org',
        'admin+alerts@monitoring.io',
        'user_123@datahub.com',
        'dev-team@project42.dev',
        'Support@Example.COM',
        'John.DOE@Mail.org',
        'customer-service@ecommerce.biz',
        'a!b#c$d%e&f\'g*h+i-j=k@weirdmail.com',
        'first.last@sub.domain.co.uk',
        'contact.us@info.example.nl',
        'user@xn--fsq.com',
    ];

    public function testItSetsValueCorrectly(): void
    {
        foreach ($this->values as $value) {
            $email = new Email($value);
            $this->assertEquals($value, $email->value);
        }
    }

    public function testItShouldThrowExceptionWhenValueIsInvalid(): void
    {
        $values = [
            'foo',
            'foo@example',
            'foo@example.',
            1,
            [],
            true,
            false,
            null,
        ];

        foreach ($values as $value) {
            try {
                new Email($value);
                $this->fail();
            } catch (TypeVaultValidationError $expected) {
                $this->assertInstanceOf(TypeVaultValidationError::class, $expected);
            }
        }
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $email = new Email(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($email->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new Email(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $email = new Email('user@example.com', new TypeOptionsDTO(immutable: false));

        $newEmail = 'new-email@example.com';

        $email->value = $newEmail;
        $this->assertEquals($newEmail, $email->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $email = new Email('user@example.com', new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $email->value = 'new-email@example.com';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $email = Email::nullable();
        $this->assertNull($email->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        Email::immutable('user@example.com')->value = 'new-email@example.com';
    }
}
