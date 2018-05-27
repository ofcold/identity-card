<?php

use Ofcold\IdentityCard\IdentityCard;

class IdentityCardTest extends PHPUnit\Framework\TestCase
{
	public function testMakeIdentityCardInstance()
	{
		$idCard = IdentityCard::make('142701198003124054');

		$this->assertEquals(IdentityCard::class, get_class($idCard));
	}

	public function testMakeFalse()
	{
		$idCard = IdentityCard::make('');

		$this->assertEquals(false, $idCard);
	}

	public function testToJson()
	{
		$idCard = IdentityCard::make('142701198003124054');

		$this->assertJson($idCard->toJson());
	}
}