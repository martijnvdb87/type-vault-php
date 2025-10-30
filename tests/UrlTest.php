<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\Url;
use Martijnvdb\TypeVault\Types\TypeOptions;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    /** @var string[] */
    private array $values = [
        'http://example.com',
        'https://example.com',
        'https://blog.example.com',
        'https://shop.uk.example.co.uk',
        'https://example.com/about',
        'https://example.com/products/item/123',
        'https://example.com/search?q=regex',
        'https://example.com/api?user=john&id=42',
        'https://example.com/docs#section2',
        'https://example.com/page#top',
        'https://example.com:8080/dashboard',
        'https://user:pass@example.com',
        'https://xn--fsq.com',
        'https://example.com/.well-known/security.txt',
        'https://example.com/?empty=',
        'https://example.com/path/with%20spaces',
    ];

    public function testItSetsValueCorrectly(): void
    {
        foreach ($this->values as $value) {
            $url = new Url($value);
            $this->assertEquals($value, $url->value);
        }
    }

    public function testItShouldThrowExceptionWhenValueIsInvalid(): void
    {
        $values = [
            1,
            [],
            true,
            false,
            null,
            'foo',
            'bar',
        ];

        $this->expectException(TypeVaultValidationError::class);

        foreach ($values as $value) {
            new Url($value);
        }
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $url = new Url(null, new TypeOptions(nullable: true));
        $this->assertNull($url->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new Url(null, new TypeOptions(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $url = new Url('http://example.com', new TypeOptions(immutable: false));

        $newUrl = 'https://blog.example.com';

        $url->value = $newUrl;
        $this->assertEquals($newUrl, $url->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $url = new Url('http://example.com', new TypeOptions(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $url->value = 'https://blog.example.com';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $url = Url::nullable(null);
        $this->assertNull($url->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        Url::immutable('http://example.com')->value = 'https://blog.example.com';
    }
}
