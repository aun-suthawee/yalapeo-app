<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Boolean implements CastsAttributes
{
    public function get($model, $key, $value, $attributes): bool
    {
        return (boolean)$value;
    }

    public function set($model, $key, $value, $attributes): int
    {
        return (int)filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
