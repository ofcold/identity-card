<?php

namespace Ofcold\IdentityCard\Rules;

use Illuminate\Contracts\Validation\Rule;
use Ofcold\IdentityCard\IdentityCard;

/**
 * class IdCard
 *
 * PHP business application development core system
 *
 * This content is released under the Business System Toll License (MST)
 *
 * @link     https://ofcold.com
 *
 * @author   Bill Li (bill.li@ofcold.com) [Owner]
 *
 * @copyright  Copyright (c) 2017-2019 Bill Li, Ofcold Institute of Technology. All rights reserved.
 */
class IdCard implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return IdentityCard::make($value, app()->getLocale()) !== false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Please enter a valid Id Card');
    }
}

