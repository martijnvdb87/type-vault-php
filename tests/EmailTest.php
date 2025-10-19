<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\Types\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testEmail(): void
    {
        $email = new Email('foo@example.com');

        $this->assertEquals('foo@example.com', $email->value);
    }
}
