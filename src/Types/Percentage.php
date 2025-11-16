<?php

namespace Martijnvdb\TypeVault\Types;

class Percentage extends BaseNumber
{
    protected function min(): int
    {
        return 0;
    }

    protected function max(): int
    {
        return 1;
    }
}
