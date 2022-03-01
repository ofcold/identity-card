<?php

namespace Ofcold\IdentityCard;

class IdentityCard
{
	/**
	 * The IdentityCard isntance.
	 *
	 * @var  IdentityCard|null
	 */
	protected static ?IdentityCard $instance = null;

	/**
	 * The user id card.
	 *
	 * @var  string
	 */
	protected static string $idCard;

	/**
	 * Get the lcoale slog.
	 *
	 * @var  string
	 */
	protected static string $locale;

	/**
	 * Create an new IdentityCard instance.
	 *
	 * @param  string  $idCard
	 * @param  string  $locale
	 *
	 * @return  $this|boolean
	 */
	public static function make(string $idCard, string $locale = 'zh-cn')
	{
		static::$idCard = $idCard;

		static::$locale = in_array($locale, ['zh-cn', 'en']) ? $locale : 'zh-cn';

		if (static::validate(static::$idCard) === false) {
			return false;
		}

		return static::$instance ?: static::$instance = new static;
	}

	/**
	 * Get the locale.
	 *
	 * @return  string
	 */
	public static function getLocale(): string
	{
		return static::$locale ?: 'zh-cn';
	}

	/**
	 * Verify your ID card is legal.
	 *
	 * @param string $idCard
	 *
	 * @return  bool
	 */
	public static function validate(string $idCard): bool
	{
		$id = strtoupper($idCard);

		if (static::checkFirst($id) === true) {
			$iYear  = substr($id, 6, 4);
			$iMonth = substr($id, 10, 2);
			$iDay   = substr($id, 12, 2);
			if (checkdate($iMonth, $iDay, $iYear)) {
				return static::getIDCardVerifyNumber(substr($id, 0, 17)) != substr($id, 17, 1) ? false : true;
			}
		}

		return false;
	}

	/**
	 * Through the regular expression preliminary detection ID number illegality.
	 *
	 * @param  string  $idCard
	 *
	 * @return  bool
	 */
	protected static function checkFirst(string $idCard): bool
	{
		return preg_match('/^\d{6}(18|19|20)\d{2}(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])\d{3}(\d|X)$/', $idCard);
	}

	/**
	 * According to the first 17 digits of ID card to calculate the last check digit of ID card
	 *
	 * @param  string  $idcardBase
	 *
	 * @return  string
	 */
	protected static function getIDCardVerifyNumber(string $idCard): string
	{
		$factors = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];

		$numbers = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];

		$checksum = 0;

		for ($i = 0; $i < strlen($idCard); $i++) {
			$checksum += substr($idCard, $i, 1) * $factors[$i];
		}

		$mod = $checksum % 11;

		return $numbers[$mod];
	}

	/**
	 * Constellations（Data from Wikipedia https://zh.wikipedia.org/wiki/%E8%A5%BF%E6%B4%8B%E5%8D%A0%E6%98%9F%E8%A1%93）
	 *
	 * @var  array
	 */
	protected $constellations = [
		'zh-cn'	=> [
			// 1.21-2.19 [Aquarius]
			'水瓶座',
			// 2.20-3.20 [Pisces]
			'双鱼座',
			// 3.21-4.19 [Aries]
			'白羊座',
			// 4.20-5.20 [Taurus]
			'金牛座',
			// 5.21-6.21 [Gemini]
			'双子座',
			// 6.22-7.22 [Cancer]
			'巨蟹座',
			// 7.23-8.22 [Leo]
			'狮子座',
			// 8.23-9.22 [Virgo]
			'处女座',
			// 9.23-10.23 [Libra]
			'天秤座',
			// 10.24-11.21 [Scorpio]
			'天蝎座',
			// 11.22-12.20 [Sagittarius]
			'射手座',
			// 12.21-1.20 [Capricorn]
			'摩羯座',
		],
		'en'	=> [
			'Aquarius',
			'Pisces',
			'Aries',
			'Taurus',
			'Gemini',
			'Cancer',
			'Leo',
			'Virgo',
			'Libra',
			'Scorpio',
			'Sagittarius',
			'Capricorn',
		]
	];

	/**
	 * Constellation edge day cut data.
	 *
	 * @var  array
	 */
	protected $constellationEdgeDays = [21, 20, 21, 20, 21, 22, 23, 23, 23, 24, 22, 21];

	/**
	 * People's Republic of China provincial administrative divisions code (excluding Hong Kong, Macao and Taiwan regions).
	 *
	 * @var  array
	 */
	protected static $regions = [];

	/**
	 * Stop building an ID card instance.
	 *
	 * @return  void
	 *
	 * @throws  InvalidArgumentException
	 */
	protected function __construct()
	{
		static::$regions = static::$regions ?: RegionsData::items();
	}

	/**
	 * Get region with ID card.
	 *
	 * @return  string
	 */
	public function getArea(): string
	{
		return "{$this->getProvince()} {$this->getCity()} {$this->getCounty()}";
	}

	/**
	 * Get the province.
	 *
	 * @return  string|null
	 */
	public function getProvince(): ?string
	{
		$k = substr(static::$idCard, 0, 2) . '0000';

		if (! isset(static::$regions[$k])) {
			return null;
		}

		return static::$regions[$k][static::$locale];
	}

	/**
	 * Get the city.
	 *
	 * @return  string|null
	 */
	public function getCity(): ?string
	{
		$k = substr(static::$idCard, 0, 4) . '00';

		if (! isset(static::$regions[$k])) {
			return null;
		}

		return static::$regions[$k][static::$locale];
	}

	/**
	 * Get the county.
	 *
	 * @return  string|null
	 */
	public function getCounty(): ?string
	{
		$k = substr(static::$idCard, 0, 6);

		if (! isset(static::$regions[$k])) {
			return null;
		}

		return static::$regions[$k][static::$locale];
	}

	/**
	 * Get the user gender.
	 *
	 * @return  string
	 */
	public function getGender(): string
	{
		$loale = [
			'zh-cn'	=> ['female' => '女', 'male'	=> '男'],
			'en'	=> ['female' => 'Female', 'male'	=> 'Male']
		][static::getLocale()];

		return $loale[(substr(static::$idCard, 16, 1) % 2 == 0) ? 'female' : 'male'];
	}

	/**
	 * Get birthday date information.
	 *
	 * @param string $format Dateformat Default example: 'Y-m-d'
	 *
	 * @return  string
	 */
	public function getBirthday(string $format = 'Y-m-d'): string
	{
		return date(
			$format,
			mktime(
				0,
				0,
				0,
				substr(static::$idCard, 10, 2),
				substr(static::$idCard, 12, 2),
				substr(static::$idCard, 6, 4))
		);
	}

	/**
	 * Get the user age.
	 *
	 * @return  int
	 */
	public function getAge(): int
	{
		$today	= strtotime('today');

		$diff = floor(($today - strtotime(substr(static::$idCard, 6, 8)))/86400/365);

		return (int) strtotime(substr(static::$idCard,6,8).' +'.$diff.'years') > $today ? ($diff + 1) : $diff;
	}

	/**
	 * Return user zodiac.
	 *
	 * @return  string
	 */
	public function getZodiac(): string
	{
		$locale = [
			'zh-cn'	=> ['牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪', '鼠'],
			'en'	=> ['Cow', 'Tiger', 'Rabbit', 'Dragon', 'Snake', 'Horse', 'Sheep', 'Monkey', 'Chicken', 'Dog', 'Pig', 'Rat']
		][static::getLocale()];

		return $locale[abs(substr(static::$idCard, 6, 4) - 1901) % 12];
	}

	/**
	 * Get the user constellation.
	 *
	 * @return  string
	 */
	public function getConstellation(): string
	{
		$month = (int) substr(static::$idCard, 10, 2);

		$month = $month - 1;

		$day = (int) substr(static::$idCard, 12, 2);

		if ($day >= $this->constellationEdgeDays[$month]) {
			$month = $month + 1;
		}

		if ($month > 0) {
			return $this->constellations[static::getLocale()][$month];
		}

		return $this->constellations[static::getLocale()][11];
	}

	/**
	 * Get the personal information of item as JSON.
	 *
	 * @param  int  $options
	 *
	 * @return  string
	 */
	public function toJson(int $options = 0): string
	{
		return json_encode($this->toArray(), $options);
	}

	/**
	 * Get the personal information of item as a plain array.
	 *
	 * @return  array
	 */
	public function toArray(): array
	{
		return [
			'area'			=> $this->getArea(),
			'province'		=> $this->getProvince(),
			'city'			=> $this->getCity(),
			'county'		=> $this->getCounty(),
			'gender'		=> $this->getGender(),
			'birthday'		=> $this->getBirthday(),
			'zodiac'		=> $this->getZodiac(),
			'age'			=> $this->getAge(),
			'constellation'	=> $this->getConstellation()
		];
	}

	/**
	 * Get a attibutes value from the object.
	 *
	 * @param  string $key
	 *
	 * @return mixed
	 */
	public function __get($key)
	{
		$result = $this->toArray();

		return $result[$key] ?? $result;
	}

	/**
	 * Returns a string json of this object.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->toJson();
	}

}
