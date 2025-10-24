<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\Email;
use Martijnvdb\TypeVault\Types\TypeOptions;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    private $values = [
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

        $this->expectException(TypeVaultValidationError::class);

        foreach ($values as $value) {
            $email = new Email($value);
        }
    }
}
