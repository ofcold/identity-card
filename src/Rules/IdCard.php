<?php

namespace Ofcold\IdentityCard\Rules;

use Illuminate\Contracts\Validation\Rule as RuleInterface;
use Ofcold\IdentityCard\IdentityCard;

class IdCard implements RuleInterface
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
		return IdentityCard::validate($value);
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

