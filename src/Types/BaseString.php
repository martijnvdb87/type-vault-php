<?php

namespace Martijnvdb\TypeVault\Types;

/**
 * @property string $value
 */
abstract class BaseString extends Type
{
    public function __construct(string | null $value, TypeOptions | null $options = null)
    {
        parent::__construct($value, $options);
    }

    protected function validate(mixed $value): bool
    {
        return is_string($value);
    }

    /**
     * @param string $value
     */
    public static function nullable(mixed $value): static
    {
        return new static($value, new TypeOptions(nullable: true));
    }

    /**
     * @param string $value
     */
    public static function immutable(mixed $value): static
    {
        return new static($value, new TypeOptions(immutable: true));
    }
}
