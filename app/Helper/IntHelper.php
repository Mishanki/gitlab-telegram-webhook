<?php

namespace App\Helper;

class IntHelper
{
    /**
     * @param null|string $value
     * @param null|string $returnIfNull
     *
     * @return null|int|string
     */
    public static function stringToIntOrNullShort(?string $value, ?string $returnIfNull = null): null|int|string
    {
        return $value !== null ? (int) $value : $returnIfNull;
    }
}
