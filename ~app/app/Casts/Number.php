<?php
namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Number implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return trim($value);
    }

    public function set($model, $key, $value, $attributes)
    {
        return trim($value);
    }
}
