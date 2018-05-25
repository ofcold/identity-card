<?php

use Ofcold\IdentityCard\IdentityCard;

class IdentityCardTest extends PHPUnit\Framework\TestCase
{
	public function testMake()
	{
		$idCard = IdentityCard::make('142701198003124054');

		$this->assertEquals(IdentityCard::class, get_class($idCard));
	}
}