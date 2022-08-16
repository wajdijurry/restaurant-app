<?php

namespace App\Enums;

class UserTypeEnum
{
    const USER_TYPE_CUSTOMER = 'customer';
    const USER_TYPE_MERCHANT = 'merchant';

    /**
     * @return string[]
     */
    public static function getTypes(): array
    {
        return [
            self::USER_TYPE_MERCHANT,
            self::USER_TYPE_CUSTOMER
        ];
    }
}