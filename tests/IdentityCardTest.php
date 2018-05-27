<?php

use Ofcold\IdentityCard\IdentityCard;

class IdentityCardTest extends PHPUnit\Framework\TestCase
{
	protected $idCard;

	public function setUp()
	{
		$this->idCard = IdentityCard::make('142701198003124054');
	}

	public function testMakeIdentityCardInstance()
	{
		$this->assertEquals(IdentityCard::class, get_class($this->idCard));
	}

	public function testMakeFalse()
	{
		$this->assertEquals(false, !is_a($this->idCard, IdentityCard::class));
	}

	public function testArea()
	{
		$this->assertEquals('山西省 运城地区 运城市', $this->idCard->getArea());
	}

	public function testGender()
	{
		$this->assertEquals('男', $this->idCard->getGender());
	}

	public function testBirthday()
	{
		$this->assertEquals('1980-03-12', $this->idCard->getBirthday());
	}

	public function testAge()
	{
		$this->assertEquals('38', $this->idCard->getAge());
	}

	public function testConstellation()
	{
		$this->assertEquals('双鱼座', $this->idCard->getConstellation());
	}

	public function testToJson()
	{
		$this->assertJson($this->idCard->toJson());
	}
}