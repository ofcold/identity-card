<?php

namespace Ofcold\IdentityCard;

/**
 * Class IdentityCard
 *
 * @link  https://ofcold.com
 * @link  https://ofcold.com/license
 *
 * @author  Ofcold <support@ofcold.com>
 * @author  Olivia Fu <olivia@ofcold.com>
 * @author  Bill Li <bill.li@ofcold.com>
 *
 * @package	Ofcold\IdentityCard\IdentityCard
 *
 * @copyright  Copyright (c) 2017-2018, Ofcold. All rights reserved.
 */
class IdentityCard
{
	/**
	 * The IdentityCard isntance.
	 *
	 * @var  IdentityCard|null
	 */
	protected static $instance;

	/**
	 * The user id card.
	 *
	 * @var  string
	 */
	protected static $idCard;

	/**
	 * Get the lcoale slog.
	 *
	 * @var  string
	 */
	protected static $locale;

	/**
	 * Create an new IdentityCard instance.
	 *
	 * @param  string  $idCard
	 * @param  string  $locale
	 *
	 * @return  $this
	 */
	public static function make(string $idCard, string $locale = 'zh-cn')
	{
		static::$idCard = $idCard;

		static::$locale = in_array($locale, ['zh-cn', 'en']) ? $locale : 'zh-cn';

		if ( static::check() === false )
		{
			return false;
		}

		return static::$instance ?: static::$instance = new static;
	}

	/**
	 * Get the locale.
	 *
	 * @return  string
	 */
	public static function getLocale() : string
	{
		return static::$locale ?: 'zh-cn';
	}

	/**
	 * Verify your ID card is legal.
	 *
	 * @return  bool
	 */
	protected static function check() : bool
	{
		$id = strtoupper(static::$idCard);

		if ( static::checkFirst($id) === true )
		{
			$iYear  = substr($id, 6, 4);
			$iMonth = substr($id, 10, 2);
			$iDay   = substr($id, 12, 2);
			if ( checkdate($iMonth, $iDay, $iYear) )
			{
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
	protected static function checkFirst(string $idCard) : bool
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
	protected static function getIDCardVerifyNumber(string $idcardBase) : string
	{
		$factor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];

		$verifyNumberList = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];

		$checksum = 0;

		for ( $i = 0; $i < strlen($idcardBase); $i++ )
		{
			$checksum += substr($idcardBase, $i, 1) * $factor[$i];
		}

		$mod = $checksum % 11;

		return $verifyNumberList[$mod];
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
			'魔羯座',
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
	protected $regions = [
		'110000'	=> [
			'zh-cn'			=> '北京市',
			'en'			=> 'bei jing shi',
		],
		'110101'	=> [
			'zh-cn'			=> '东城区',
			'en'			=> 'dong cheng qu',
		],
		'110102'	=> [
			'zh-cn'			=> '西城区',
			'en'			=> 'xi cheng qu',
		],
		'110103'	=> [
			'zh-cn'			=> '崇文区',
			'en'			=> 'chong wen qu',
		],
		'110104'	=> [
			'zh-cn'			=> '宣武区',
			'en'			=> 'xuan wu qu',
		],
		'110105'	=> [
			'zh-cn'			=> '朝阳区',
			'en'			=> 'zhao yang qu',
		],
		'110106'	=> [
			'zh-cn'			=> '丰台区',
			'en'			=> 'feng tai qu',
		],
		'110107'	=> [
			'zh-cn'			=> '石景山区',
			'en'			=> 'shi jing shan qu',
		],
		'110108'	=> [
			'zh-cn'			=> '海淀区',
			'en'			=> 'hai dian qu',
		],
		'110109'	=> [
			'zh-cn'			=> '门头沟区',
			'en'			=> 'men tou gou qu',
		],
		'110110'	=> [
			'zh-cn'			=> '燕山区',
			'en'			=> 'yan shan qu',
		],
		'110111'	=> [
			'zh-cn'			=> '房山区',
			'en'			=> 'fang shan qu',
		],
		'110112'	=> [
			'zh-cn'			=> '通州区',
			'en'			=> 'tong zhou qu',
		],
		'110113'	=> [
			'zh-cn'			=> '顺义区',
			'en'			=> 'shun yi qu',
		],
		'110114'	=> [
			'zh-cn'			=> '昌平区',
			'en'			=> 'chang ping qu',
		],
		'110115'	=> [
			'zh-cn'			=> '大兴区',
			'en'			=> 'da xing qu',
		],
		'110116'	=> [
			'zh-cn'			=> '怀柔区',
			'en'			=> 'huai rou qu',
		],
		'110117'	=> [
			'zh-cn'			=> '平谷区',
			'en'			=> 'ping gu qu',
		],
		'110118'	=> [
			'zh-cn'			=> '密云区',
			'en'			=> 'mi yun qu',
		],
		'110119'	=> [
			'zh-cn'			=> '延庆区',
			'en'			=> 'yan qing qu',
		],
		'110221'	=> [
			'zh-cn'			=> '昌平县',
			'en'			=> 'chang ping xian',
		],
		'110222'	=> [
			'zh-cn'			=> '顺义县',
			'en'			=> 'shun yi xian',
		],
		'110224'	=> [
			'zh-cn'			=> '大兴县',
			'en'			=> 'da xing xian',
		],
		'110225'	=> [
			'zh-cn'			=> '房山县',
			'en'			=> 'fang shan xian',
		],
		'110226'	=> [
			'zh-cn'			=> '平谷县',
			'en'			=> 'ping gu xian',
		],
		'110227'	=> [
			'zh-cn'			=> '怀柔县',
			'en'			=> 'huai rou xian',
		],
		'110228'	=> [
			'zh-cn'			=> '密云县',
			'en'			=> 'mi yun xian',
		],
		'110229'	=> [
			'zh-cn'			=> '延庆县',
			'en'			=> 'yan qing xian',
		],
		'120000'	=> [
			'zh-cn'			=> '天津市',
			'en'			=> 'tian jin shi',
		],
		'120101'	=> [
			'zh-cn'			=> '和平区',
			'en'			=> 'he ping qu',
		],
		'120102'	=> [
			'zh-cn'			=> '河东区',
			'en'			=> 'he dong qu',
		],
		'120103'	=> [
			'zh-cn'			=> '河西区',
			'en'			=> 'he xi qu',
		],
		'120104'	=> [
			'zh-cn'			=> '南开区',
			'en'			=> 'nan kai qu',
		],
		'120105'	=> [
			'zh-cn'			=> '河北区',
			'en'			=> 'he bei qu',
		],
		'120106'	=> [
			'zh-cn'			=> '红桥区',
			'en'			=> 'hong qiao qu',
		],
		'120107'	=> [
			'zh-cn'			=> '塘沽区',
			'en'			=> 'tang gu qu',
		],
		'120108'	=> [
			'zh-cn'			=> '汉沽区',
			'en'			=> 'han gu qu',
		],
		'120109'	=> [
			'zh-cn'			=> '大港区',
			'en'			=> 'da gang qu',
		],
		'120110'	=> [
			'zh-cn'			=> '东丽区',
			'en'			=> 'dong li qu',
		],
		'120111'	=> [
			'zh-cn'			=> '西青区',
			'en'			=> 'xi qing qu',
		],
		'120112'	=> [
			'zh-cn'			=> '津南区',
			'en'			=> 'jin nan qu',
		],
		'120113'	=> [
			'zh-cn'			=> '北辰区',
			'en'			=> 'bei chen qu',
		],
		'120114'	=> [
			'zh-cn'			=> '武清区',
			'en'			=> 'wu qing qu',
		],
		'120115'	=> [
			'zh-cn'			=> '宝坻区',
			'en'			=> 'bao di qu',
		],
		'120116'	=> [
			'zh-cn'			=> '滨海新区',
			'en'			=> 'bin hai xin qu',
		],
		'120117'	=> [
			'zh-cn'			=> '宁河区',
			'en'			=> 'ning he qu',
		],
		'120118'	=> [
			'zh-cn'			=> '静海区',
			'en'			=> 'jing hai qu',
		],
		'120119'	=> [
			'zh-cn'			=> '蓟州区',
			'en'			=> 'ji zhou qu',
		],
		'120221'	=> [
			'zh-cn'			=> '宁河县',
			'en'			=> 'ning he xian',
		],
		'120222'	=> [
			'zh-cn'			=> '武清县',
			'en'			=> 'wu qing xian',
		],
		'120223'	=> [
			'zh-cn'			=> '静海县',
			'en'			=> 'jing hai xian',
		],
		'120224'	=> [
			'zh-cn'			=> '宝坻县',
			'en'			=> 'bao di xian',
		],
		'130000'	=> [
			'zh-cn'			=> '河北省',
			'en'			=> 'he bei sheng',
		],
		'130100'	=> [
			'zh-cn'			=> '石家庄市',
			'en'			=> 'shi jia zhuang shi',
		],
		'130102'	=> [
			'zh-cn'			=> '长安区',
			'en'			=> 'chang an qu',
		],
		'130103'	=> [
			'zh-cn'			=> '桥东区',
			'en'			=> 'qiao dong qu',
		],
		'130104'	=> [
			'zh-cn'			=> '桥西区',
			'en'			=> 'qiao xi qu',
		],
		'130105'	=> [
			'zh-cn'			=> '新华区',
			'en'			=> 'xin hua qu',
		],
		'130107'	=> [
			'zh-cn'			=> '井陉矿区',
			'en'			=> 'jing xing kuang qu',
		],
		'130108'	=> [
			'zh-cn'			=> '裕华区',
			'en'			=> 'yu hua qu',
		],
		'130109'	=> [
			'zh-cn'			=> '藁城区',
			'en'			=> 'gao cheng qu',
		],
		'130110'	=> [
			'zh-cn'			=> '鹿泉区',
			'en'			=> 'lu quan qu',
		],
		'130111'	=> [
			'zh-cn'			=> '栾城区',
			'en'			=> 'luan cheng qu',
		],
		'130121'	=> [
			'zh-cn'			=> '井陉县',
			'en'			=> 'jing xing xian',
		],
		'130122'	=> [
			'zh-cn'			=> '获鹿县',
			'en'			=> 'huo lu xian',
		],
		'130123'	=> [
			'zh-cn'			=> '正定县',
			'en'			=> 'zheng ding xian',
		],
		'130124'	=> [
			'zh-cn'			=> '栾城县',
			'en'			=> 'luan cheng xian',
		],
		'130125'	=> [
			'zh-cn'			=> '行唐县',
			'en'			=> 'xing tang xian',
		],
		'130126'	=> [
			'zh-cn'			=> '灵寿县',
			'en'			=> 'ling shou xian',
		],
		'130127'	=> [
			'zh-cn'			=> '高邑县',
			'en'			=> 'gao yi xian',
		],
		'130128'	=> [
			'zh-cn'			=> '深泽县',
			'en'			=> 'shen ze xian',
		],
		'130129'	=> [
			'zh-cn'			=> '赞皇县',
			'en'			=> 'zan huang xian',
		],
		'130130'	=> [
			'zh-cn'			=> '无极县',
			'en'			=> 'wu ji xian',
		],
		'130131'	=> [
			'zh-cn'			=> '平山县',
			'en'			=> 'ping shan xian',
		],
		'130132'	=> [
			'zh-cn'			=> '元氏县',
			'en'			=> 'yuan shi xian',
		],
		'130181'	=> [
			'zh-cn'			=> '辛集市',
			'en'			=> 'xin ji shi',
		],
		'130182'	=> [
			'zh-cn'			=> '藁城市',
			'en'			=> 'gao cheng shi',
		],
		'130183'	=> [
			'zh-cn'			=> '晋州市',
			'en'			=> 'jin zhou shi',
		],
		'130184'	=> [
			'zh-cn'			=> '新乐市',
			'en'			=> 'xin le shi',
		],
		'130185'	=> [
			'zh-cn'			=> '鹿泉市',
			'en'			=> 'lu quan shi',
		],
		'130200'	=> [
			'zh-cn'			=> '唐山市',
			'en'			=> 'tang shan shi',
		],
		'130202'	=> [
			'zh-cn'			=> '路南区',
			'en'			=> 'lu nan qu',
		],
		'130203'	=> [
			'zh-cn'			=> '路北区',
			'en'			=> 'lu bei qu',
		],
		'130204'	=> [
			'zh-cn'			=> '古冶区',
			'en'			=> 'gu ye qu',
		],
		'130205'	=> [
			'zh-cn'			=> '开平区',
			'en'			=> 'kai ping qu',
		],
		'130207'	=> [
			'zh-cn'			=> '丰南区',
			'en'			=> 'feng nan qu',
		],
		'130208'	=> [
			'zh-cn'			=> '丰润区',
			'en'			=> 'feng run qu',
		],
		'130209'	=> [
			'zh-cn'			=> '曹妃甸区',
			'en'			=> 'cao fei dian qu',
		],
		'130221'	=> [
			'zh-cn'			=> '丰润县',
			'en'			=> 'feng run xian',
		],
		'130222'	=> [
			'zh-cn'			=> '丰南县',
			'en'			=> 'feng nan xian',
		],
		'130224'	=> [
			'zh-cn'			=> '滦南县',
			'en'			=> 'luan nan xian',
		],
		'130225'	=> [
			'zh-cn'			=> '乐亭县',
			'en'			=> 'le ting xian',
		],
		'130226'	=> [
			'zh-cn'			=> '迁安县',
			'en'			=> 'qian an xian',
		],
		'130227'	=> [
			'zh-cn'			=> '迁西县',
			'en'			=> 'qian xi xian',
		],
		'130228'	=> [
			'zh-cn'			=> '遵化县',
			'en'			=> 'zun hua xian',
		],
		'130229'	=> [
			'zh-cn'			=> '玉田县',
			'en'			=> 'yu tian xian',
		],
		'130230'	=> [
			'zh-cn'			=> '唐海县',
			'en'			=> 'tang hai xian',
		],
		'130281'	=> [
			'zh-cn'			=> '遵化市',
			'en'			=> 'zun hua shi',
		],
		'130282'	=> [
			'zh-cn'			=> '丰南市',
			'en'			=> 'feng nan shi',
		],
		'130283'	=> [
			'zh-cn'			=> '迁安市',
			'en'			=> 'qian an shi',
		],
		'130300'	=> [
			'zh-cn'			=> '秦皇岛市',
			'en'			=> 'qin huang dao shi',
		],
		'130302'	=> [
			'zh-cn'			=> '海港区',
			'en'			=> 'hai gang qu',
		],
		'130303'	=> [
			'zh-cn'			=> '山海关区',
			'en'			=> 'shan hai guan qu',
		],
		'130304'	=> [
			'zh-cn'			=> '北戴河区',
			'en'			=> 'bei dai he qu',
		],
		'130306'	=> [
			'zh-cn'			=> '抚宁区',
			'en'			=> 'fu ning qu',
		],
		'130321'	=> [
			'zh-cn'			=> '青龙满族自治县',
			'en'			=> 'qing long man zu zi zhi xian',
		],
		'130322'	=> [
			'zh-cn'			=> '昌黎县',
			'en'			=> 'chang li xian',
		],
		'130323'	=> [
			'zh-cn'			=> '抚宁县',
			'en'			=> 'fu ning xian',
		],
		'130324'	=> [
			'zh-cn'			=> '卢龙县',
			'en'			=> 'lu long xian',
		],
		'130400'	=> [
			'zh-cn'			=> '邯郸市',
			'en'			=> 'han dan shi',
		],
		'130402'	=> [
			'zh-cn'			=> '邯山区',
			'en'			=> 'han shan qu',
		],
		'130403'	=> [
			'zh-cn'			=> '丛台区',
			'en'			=> 'cong tai qu',
		],
		'130404'	=> [
			'zh-cn'			=> '复兴区',
			'en'			=> 'fu xing qu',
		],
		'130406'	=> [
			'zh-cn'			=> '峰峰矿区',
			'en'			=> 'feng feng kuang qu',
		],
		'130407'	=> [
			'zh-cn'			=> '肥乡区',
			'en'			=> 'fei xiang qu',
		],
		'130408'	=> [
			'zh-cn'			=> '永年区',
			'en'			=> 'yong nian qu',
		],
		'130421'	=> [
			'zh-cn'			=> '邯郸县',
			'en'			=> 'han dan xian',
		],
		'130422'	=> [
			'zh-cn'			=> '武安县',
			'en'			=> 'wu an xian',
		],
		'130423'	=> [
			'zh-cn'			=> '临漳县',
			'en'			=> 'lin zhang xian',
		],
		'130424'	=> [
			'zh-cn'			=> '成安县',
			'en'			=> 'cheng an xian',
		],
		'130425'	=> [
			'zh-cn'			=> '大名县',
			'en'			=> 'da ming xian',
		],
		'130428'	=> [
			'zh-cn'			=> '肥乡县',
			'en'			=> 'fei xiang xian',
		],
		'130429'	=> [
			'zh-cn'			=> '永年县',
			'en'			=> 'yong nian xian',
		],
		'130431'	=> [
			'zh-cn'			=> '鸡泽县',
			'en'			=> 'ji ze xian',
		],
		'130432'	=> [
			'zh-cn'			=> '广平县',
			'en'			=> 'guang ping xian',
		],
		'130433'	=> [
			'zh-cn'			=> '馆陶县',
			'en'			=> 'guan tao xian',
		],
		'130435'	=> [
			'zh-cn'			=> '曲周县',
			'en'			=> 'qu zhou xian',
		],
		'130481'	=> [
			'zh-cn'			=> '武安市',
			'en'			=> 'wu an shi',
		],
		'130500'	=> [
			'zh-cn'			=> '邢台市',
			'en'			=> 'xing tai shi',
		],
		'130502'	=> [
			'zh-cn'			=> '桥东区',
			'en'			=> 'qiao dong qu',
		],
		'130503'	=> [
			'zh-cn'			=> '桥西区',
			'en'			=> 'qiao xi qu',
		],
		'130521'	=> [
			'zh-cn'			=> '邢台县',
			'en'			=> 'xing tai xian',
		],
		'130522'	=> [
			'zh-cn'			=> '临城县',
			'en'			=> 'lin cheng xian',
		],
		'130523'	=> [
			'zh-cn'			=> '内丘县',
			'en'			=> 'nei qiu xian',
		],
		'130524'	=> [
			'zh-cn'			=> '柏乡县',
			'en'			=> 'bai xiang xian',
		],
		'130525'	=> [
			'zh-cn'			=> '隆尧县',
			'en'			=> 'long yao xian',
		],
		'130527'	=> [
			'zh-cn'			=> '南和县',
			'en'			=> 'nan he xian',
		],
		'130528'	=> [
			'zh-cn'			=> '宁晋县',
			'en'			=> 'ning jin xian',
		],
		'130529'	=> [
			'zh-cn'			=> '巨鹿县',
			'en'			=> 'ju lu xian',
		],
		'130530'	=> [
			'zh-cn'			=> '新河县',
			'en'			=> 'xin he xian',
		],
		'130531'	=> [
			'zh-cn'			=> '广宗县',
			'en'			=> 'guang zong xian',
		],
		'130532'	=> [
			'zh-cn'			=> '平乡县',
			'en'			=> 'ping xiang xian',
		],
		'130534'	=> [
			'zh-cn'			=> '清河县',
			'en'			=> 'qing he xian',
		],
		'130535'	=> [
			'zh-cn'			=> '临西县',
			'en'			=> 'lin xi xian',
		],
		'130581'	=> [
			'zh-cn'			=> '南宫市',
			'en'			=> 'nan gong shi',
		],
		'130582'	=> [
			'zh-cn'			=> '沙河市',
			'en'			=> 'sha he shi',
		],
		'130600'	=> [
			'zh-cn'			=> '保定市',
			'en'			=> 'bao ding shi',
		],
		'130602'	=> [
			'zh-cn'			=> '竞秀区',
			'en'			=> 'jing xiu qu',
		],
		'130603'	=> [
			'zh-cn'			=> '北市区',
			'en'			=> 'bei shi qu',
		],
		'130604'	=> [
			'zh-cn'			=> '南市区',
			'en'			=> 'nan shi qu',
		],
		'130606'	=> [
			'zh-cn'			=> '莲池区',
			'en'			=> 'lian chi qu',
		],
		'130607'	=> [
			'zh-cn'			=> '满城区',
			'en'			=> 'man cheng qu',
		],
		'130608'	=> [
			'zh-cn'			=> '清苑区',
			'en'			=> 'qing yuan qu',
		],
		'130609'	=> [
			'zh-cn'			=> '徐水区',
			'en'			=> 'xu shui qu',
		],
		'130621'	=> [
			'zh-cn'			=> '满城县',
			'en'			=> 'man cheng xian',
		],
		'130622'	=> [
			'zh-cn'			=> '清苑县',
			'en'			=> 'qing yuan xian',
		],
		'130623'	=> [
			'zh-cn'			=> '涞水县',
			'en'			=> 'lai shui xian',
		],
		'130624'	=> [
			'zh-cn'			=> '阜平县',
			'en'			=> 'fu ping xian',
		],
		'130625'	=> [
			'zh-cn'			=> '徐水县',
			'en'			=> 'xu shui xian',
		],
		'130626'	=> [
			'zh-cn'			=> '定兴县',
			'en'			=> 'ding xing xian',
		],
		'130628'	=> [
			'zh-cn'			=> '高阳县',
			'en'			=> 'gao yang xian',
		],
		'130629'	=> [
			'zh-cn'			=> '容城县',
			'en'			=> 'rong cheng xian',
		],
		'130630'	=> [
			'zh-cn'			=> '涞源县',
			'en'			=> 'lai yuan xian',
		],
		'130631'	=> [
			'zh-cn'			=> '望都县',
			'en'			=> 'wang du xian',
		],
		'130632'	=> [
			'zh-cn'			=> '安新县',
			'en'			=> 'an xin xian',
		],
		'130634'	=> [
			'zh-cn'			=> '曲阳县',
			'en'			=> 'qu yang xian',
		],
		'130636'	=> [
			'zh-cn'			=> '顺平县',
			'en'			=> 'shun ping xian',
		],
		'130637'	=> [
			'zh-cn'			=> '博野县',
			'en'			=> 'bo ye xian',
		],
		'130681'	=> [
			'zh-cn'			=> '涿州市',
			'en'			=> 'zhuo zhou shi',
		],
		'130682'	=> [
			'zh-cn'			=> '定州市',
			'en'			=> 'ding zhou shi',
		],
		'130683'	=> [
			'zh-cn'			=> '安国市',
			'en'			=> 'an guo shi',
		],
		'130684'	=> [
			'zh-cn'			=> '高碑店市',
			'en'			=> 'gao bei dian shi',
		],
		'130700'	=> [
			'zh-cn'			=> '张家口市',
			'en'			=> 'zhang jia kou shi',
		],
		'130702'	=> [
			'zh-cn'			=> '桥东区',
			'en'			=> 'qiao dong qu',
		],
		'130703'	=> [
			'zh-cn'			=> '桥西区',
			'en'			=> 'qiao xi qu',
		],
		'130704'	=> [
			'zh-cn'			=> '茶坊区',
			'en'			=> 'cha fang qu',
		],
		'130705'	=> [
			'zh-cn'			=> '宣化区',
			'en'			=> 'xuan hua qu',
		],
		'130706'	=> [
			'zh-cn'			=> '下花园区',
			'en'			=> 'xia hua yuan qu',
		],
		'130707'	=> [
			'zh-cn'			=> '庞家堡区',
			'en'			=> 'pang jia bao qu',
		],
		'130708'	=> [
			'zh-cn'			=> '万全区',
			'en'			=> 'wan quan qu',
		],
		'130709'	=> [
			'zh-cn'			=> '崇礼区',
			'en'			=> 'chong li qu',
		],
		'130721'	=> [
			'zh-cn'			=> '宣化县',
			'en'			=> 'xuan hua xian',
		],
		'130722'	=> [
			'zh-cn'			=> '张北县',
			'en'			=> 'zhang bei xian',
		],
		'130723'	=> [
			'zh-cn'			=> '康保县',
			'en'			=> 'kang bao xian',
		],
		'130724'	=> [
			'zh-cn'			=> '沽源县',
			'en'			=> 'gu yuan xian',
		],
		'130725'	=> [
			'zh-cn'			=> '尚义县',
			'en'			=> 'shang yi xian',
		],
		'130727'	=> [
			'zh-cn'			=> '阳原县',
			'en'			=> 'yang yuan xian',
		],
		'130728'	=> [
			'zh-cn'			=> '怀安县',
			'en'			=> 'huai an xian',
		],
		'130729'	=> [
			'zh-cn'			=> '万全县',
			'en'			=> 'wan quan xian',
		],
		'130730'	=> [
			'zh-cn'			=> '怀来县',
			'en'			=> 'huai lai xian',
		],
		'130731'	=> [
			'zh-cn'			=> '涿鹿县',
			'en'			=> 'zhuo lu xian',
		],
		'130732'	=> [
			'zh-cn'			=> '赤城县',
			'en'			=> 'chi cheng xian',
		],
		'130733'	=> [
			'zh-cn'			=> '崇礼县',
			'en'			=> 'chong li xian',
		],
		'130800'	=> [
			'zh-cn'			=> '承德市',
			'en'			=> 'cheng de shi',
		],
		'130802'	=> [
			'zh-cn'			=> '双桥区',
			'en'			=> 'shuang qiao qu',
		],
		'130803'	=> [
			'zh-cn'			=> '双滦区',
			'en'			=> 'shuang luan qu',
		],
		'130804'	=> [
			'zh-cn'			=> '鹰手营子矿区',
			'en'			=> 'ying shou ying zi kuang qu',
		],
		'130821'	=> [
			'zh-cn'			=> '承德县',
			'en'			=> 'cheng de xian',
		],
		'130822'	=> [
			'zh-cn'			=> '兴隆县',
			'en'			=> 'xing long xian',
		],
		'130823'	=> [
			'zh-cn'			=> '平泉县',
			'en'			=> 'ping quan xian',
		],
		'130824'	=> [
			'zh-cn'			=> '滦平县',
			'en'			=> 'luan ping xian',
		],
		'130825'	=> [
			'zh-cn'			=> '隆化县',
			'en'			=> 'long hua xian',
		],
		'130826'	=> [
			'zh-cn'			=> '丰宁满族自治县',
			'en'			=> 'feng ning man zu zi zhi xian',
		],
		'130827'	=> [
			'zh-cn'			=> '宽城满族自治县',
			'en'			=> 'kuan cheng man zu zi zhi xian',
		],
		'130828'	=> [
			'zh-cn'			=> '围场满族蒙古族自治县',
			'en'			=> 'wei chang man zu meng gu zu zi zhi xian',
		],
		'130900'	=> [
			'zh-cn'			=> '沧州市',
			'en'			=> 'cang zhou shi',
		],
		'130902'	=> [
			'zh-cn'			=> '新华区',
			'en'			=> 'xin hua qu',
		],
		'130903'	=> [
			'zh-cn'			=> '运河区',
			'en'			=> 'yun he qu',
		],
		'130923'	=> [
			'zh-cn'			=> '东光县',
			'en'			=> 'dong guang xian',
		],
		'130924'	=> [
			'zh-cn'			=> '海兴县',
			'en'			=> 'hai xing xian',
		],
		'130925'	=> [
			'zh-cn'			=> '盐山县',
			'en'			=> 'yan shan xian',
		],
		'130926'	=> [
			'zh-cn'			=> '肃宁县',
			'en'			=> 'su ning xian',
		],
		'130927'	=> [
			'zh-cn'			=> '南皮县',
			'en'			=> 'nan pi xian',
		],
		'130928'	=> [
			'zh-cn'			=> '吴桥县',
			'en'			=> 'wu qiao xian',
		],
		'130930'	=> [
			'zh-cn'			=> '孟村回族自治县',
			'en'			=> 'meng cun hui zu zi zhi xian',
		],
		'130981'	=> [
			'zh-cn'			=> '泊头市',
			'en'			=> 'bo tou shi',
		],
		'130982'	=> [
			'zh-cn'			=> '任丘市',
			'en'			=> 'ren qiu shi',
		],
		'130983'	=> [
			'zh-cn'			=> '黄骅市',
			'en'			=> 'huang hua shi',
		],
		'130984'	=> [
			'zh-cn'			=> '河间市',
			'en'			=> 'he jian shi',
		],
		'131000'	=> [
			'zh-cn'			=> '廊坊市',
			'en'			=> 'lang fang shi',
		],
		'131002'	=> [
			'zh-cn'			=> '安次区',
			'en'			=> 'an ci qu',
		],
		'131003'	=> [
			'zh-cn'			=> '广阳区',
			'en'			=> 'guang yang qu',
		],
		'131021'	=> [
			'zh-cn'			=> '三河县',
			'en'			=> 'san he xian',
		],
		'131022'	=> [
			'zh-cn'			=> '固安县',
			'en'			=> 'gu an xian',
		],
		'131023'	=> [
			'zh-cn'			=> '永清县',
			'en'			=> 'yong qing xian',
		],
		'131024'	=> [
			'zh-cn'			=> '香河县',
			'en'			=> 'xiang he xian',
		],
		'131025'	=> [
			'zh-cn'			=> '大城县',
			'en'			=> 'dai cheng xian',
		],
		'131026'	=> [
			'zh-cn'			=> '文安县',
			'en'			=> 'wen an xian',
		],
		'131028'	=> [
			'zh-cn'			=> '大厂回族自治县',
			'en'			=> 'da chang hui zu zi zhi xian',
		],
		'131081'	=> [
			'zh-cn'			=> '霸州市',
			'en'			=> 'ba zhou shi',
		],
		'131082'	=> [
			'zh-cn'			=> '三河市',
			'en'			=> 'san he shi',
		],
		'131100'	=> [
			'zh-cn'			=> '衡水市',
			'en'			=> 'heng shui shi',
		],
		'131102'	=> [
			'zh-cn'			=> '桃城区',
			'en'			=> 'tao cheng qu',
		],
		'131103'	=> [
			'zh-cn'			=> '冀州区',
			'en'			=> 'ji zhou qu',
		],
		'131121'	=> [
			'zh-cn'			=> '枣强县',
			'en'			=> 'zao qiang xian',
		],
		'131122'	=> [
			'zh-cn'			=> '武邑县',
			'en'			=> 'wu yi xian',
		],
		'131123'	=> [
			'zh-cn'			=> '武强县',
			'en'			=> 'wu qiang xian',
		],
		'131124'	=> [
			'zh-cn'			=> '饶阳县',
			'en'			=> 'rao yang xian',
		],
		'131125'	=> [
			'zh-cn'			=> '安平县',
			'en'			=> 'an ping xian',
		],
		'131126'	=> [
			'zh-cn'			=> '故城县',
			'en'			=> 'gu cheng xian',
		],
		'131128'	=> [
			'zh-cn'			=> '阜城县',
			'en'			=> 'fu cheng xian',
		],
		'131181'	=> [
			'zh-cn'			=> '冀州市',
			'en'			=> 'ji zhou shi',
		],
		'131182'	=> [
			'zh-cn'			=> '深州市',
			'en'			=> 'shen zhou shi',
		],
		'132100'	=> [
			'zh-cn'			=> '邯郸地区',
			'en'			=> 'han dan di qu',
		],
		'132101'	=> [
			'zh-cn'			=> '邯郸市',
			'en'			=> 'han dan shi',
		],
		'132102'	=> [
			'zh-cn'			=> '邯山区',
			'en'			=> 'han shan qu',
		],
		'132103'	=> [
			'zh-cn'			=> '丛台区',
			'en'			=> 'cong tai qu',
		],
		'132104'	=> [
			'zh-cn'			=> '复兴区',
			'en'			=> 'fu xing qu',
		],
		'132106'	=> [
			'zh-cn'			=> '峰峰矿区',
			'en'			=> 'feng feng kuang qu',
		],
		'132121'	=> [
			'zh-cn'			=> '大名县',
			'en'			=> 'da ming xian',
		],
		'132123'	=> [
			'zh-cn'			=> '曲周县',
			'en'			=> 'qu zhou xian',
		],
		'132125'	=> [
			'zh-cn'			=> '鸡泽县',
			'en'			=> 'ji ze xian',
		],
		'132126'	=> [
			'zh-cn'			=> '肥乡县',
			'en'			=> 'fei xiang xian',
		],
		'132127'	=> [
			'zh-cn'			=> '广平县',
			'en'			=> 'guang ping xian',
		],
		'132128'	=> [
			'zh-cn'			=> '成安县',
			'en'			=> 'cheng an xian',
		],
		'132129'	=> [
			'zh-cn'			=> '临漳县',
			'en'			=> 'lin zhang xian',
		],
		'132131'	=> [
			'zh-cn'			=> '武安县',
			'en'			=> 'wu an xian',
		],
		'132133'	=> [
			'zh-cn'			=> '永年县',
			'en'			=> 'yong nian xian',
		],
		'132134'	=> [
			'zh-cn'			=> '邯郸县',
			'en'			=> 'han dan xian',
		],
		'132135'	=> [
			'zh-cn'			=> '馆陶县',
			'en'			=> 'guan tao xian',
		],
		'132200'	=> [
			'zh-cn'			=> '邢台地区',
			'en'			=> 'xing tai di qu',
		],
		'132201'	=> [
			'zh-cn'			=> '南宫市',
			'en'			=> 'nan gong shi',
		],
		'132202'	=> [
			'zh-cn'			=> '沙河市',
			'en'			=> 'sha he shi',
		],
		'132203'	=> [
			'zh-cn'			=> '桥西区',
			'en'			=> 'qiao xi qu',
		],
		'132221'	=> [
			'zh-cn'			=> '邢台县',
			'en'			=> 'xing tai xian',
		],
		'132222'	=> [
			'zh-cn'			=> '沙河县',
			'en'			=> 'sha he xian',
		],
		'132223'	=> [
			'zh-cn'			=> '临城县',
			'en'			=> 'lin cheng xian',
		],
		'132224'	=> [
			'zh-cn'			=> '内丘县',
			'en'			=> 'nei qiu xian',
		],
		'132225'	=> [
			'zh-cn'			=> '柏乡县',
			'en'			=> 'bai xiang xian',
		],
		'132226'	=> [
			'zh-cn'			=> '隆尧县',
			'en'			=> 'long yao xian',
		],
		'132228'	=> [
			'zh-cn'			=> '南和县',
			'en'			=> 'nan he xian',
		],
		'132229'	=> [
			'zh-cn'			=> '宁晋县',
			'en'			=> 'ning jin xian',
		],
		'132230'	=> [
			'zh-cn'			=> '南宫县',
			'en'			=> 'nan gong xian',
		],
		'132231'	=> [
			'zh-cn'			=> '巨鹿县',
			'en'			=> 'ju lu xian',
		],
		'132232'	=> [
			'zh-cn'			=> '新河县',
			'en'			=> 'xin he xian',
		],
		'132233'	=> [
			'zh-cn'			=> '广宗县',
			'en'			=> 'guang zong xian',
		],
		'132234'	=> [
			'zh-cn'			=> '平乡县',
			'en'			=> 'ping xiang xian',
		],
		'132236'	=> [
			'zh-cn'			=> '清河县',
			'en'			=> 'qing he xian',
		],
		'132237'	=> [
			'zh-cn'			=> '临西县',
			'en'			=> 'lin xi xian',
		],
		'132300'	=> [
			'zh-cn'			=> '石家庄地区',
			'en'			=> 'shi jia zhuang di qu',
		],
		'132301'	=> [
			'zh-cn'			=> '辛集市',
			'en'			=> 'xin ji shi',
		],
		'132302'	=> [
			'zh-cn'			=> '藁城市',
			'en'			=> 'gao cheng shi',
		],
		'132303'	=> [
			'zh-cn'			=> '晋州市',
			'en'			=> 'jin zhou shi',
		],
		'132304'	=> [
			'zh-cn'			=> '新乐市',
			'en'			=> 'xin le shi',
		],
		'132321'	=> [
			'zh-cn'			=> '束鹿县',
			'en'			=> 'shu lu xian',
		],
		'132323'	=> [
			'zh-cn'			=> '深泽县',
			'en'			=> 'shen ze xian',
		],
		'132324'	=> [
			'zh-cn'			=> '无极县',
			'en'			=> 'wu ji xian',
		],
		'132325'	=> [
			'zh-cn'			=> '藁城县',
			'en'			=> 'gao cheng xian',
		],
		'132327'	=> [
			'zh-cn'			=> '栾城县',
			'en'			=> 'luan cheng xian',
		],
		'132328'	=> [
			'zh-cn'			=> '正定县',
			'en'			=> 'zheng ding xian',
		],
		'132329'	=> [
			'zh-cn'			=> '新乐县',
			'en'			=> 'xin le xian',
		],
		'132330'	=> [
			'zh-cn'			=> '高邑县',
			'en'			=> 'gao yi xian',
		],
		'132331'	=> [
			'zh-cn'			=> '元氏县',
			'en'			=> 'yuan shi xian',
		],
		'132332'	=> [
			'zh-cn'			=> '赞皇县',
			'en'			=> 'zan huang xian',
		],
		'132333'	=> [
			'zh-cn'			=> '井陉县',
			'en'			=> 'jing xing xian',
		],
		'132334'	=> [
			'zh-cn'			=> '获鹿县',
			'en'			=> 'huo lu xian',
		],
		'132335'	=> [
			'zh-cn'			=> '平山县',
			'en'			=> 'ping shan xian',
		],
		'132336'	=> [
			'zh-cn'			=> '灵寿县',
			'en'			=> 'ling shou xian',
		],
		'132337'	=> [
			'zh-cn'			=> '行唐县',
			'en'			=> 'xing tang xian',
		],
		'132400'	=> [
			'zh-cn'			=> '保定地区',
			'en'			=> 'bao ding di qu',
		],
		'132401'	=> [
			'zh-cn'			=> '定州市',
			'en'			=> 'ding zhou shi',
		],
		'132402'	=> [
			'zh-cn'			=> '涿州市',
			'en'			=> 'zhuo zhou shi',
		],
		'132403'	=> [
			'zh-cn'			=> '安国市',
			'en'			=> 'an guo shi',
		],
		'132404'	=> [
			'zh-cn'			=> '高碑店市',
			'en'			=> 'gao bei dian shi',
		],
		'132422'	=> [
			'zh-cn'			=> '满城县',
			'en'			=> 'man cheng xian',
		],
		'132423'	=> [
			'zh-cn'			=> '徐水县',
			'en'			=> 'xu shui xian',
		],
		'132424'	=> [
			'zh-cn'			=> '涞源县',
			'en'			=> 'lai yuan xian',
		],
		'132425'	=> [
			'zh-cn'			=> '定兴县',
			'en'			=> 'ding xing xian',
		],
		'132426'	=> [
			'zh-cn'			=> '顺平县',
			'en'			=> 'shun ping xian',
		],
		'132428'	=> [
			'zh-cn'			=> '望都县',
			'en'			=> 'wang du xian',
		],
		'132429'	=> [
			'zh-cn'			=> '涞水县',
			'en'			=> 'lai shui xian',
		],
		'132431'	=> [
			'zh-cn'			=> '清苑县',
			'en'			=> 'qing yuan xian',
		],
		'132432'	=> [
			'zh-cn'			=> '高阳县',
			'en'			=> 'gao yang xian',
		],
		'132433'	=> [
			'zh-cn'			=> '安新县',
			'en'			=> 'an xin xian',
		],
		'132435'	=> [
			'zh-cn'			=> '容城县',
			'en'			=> 'rong cheng xian',
		],
		'132436'	=> [
			'zh-cn'			=> '新城县',
			'en'			=> 'xin cheng xian',
		],
		'132437'	=> [
			'zh-cn'			=> '曲阳县',
			'en'			=> 'qu yang xian',
		],
		'132438'	=> [
			'zh-cn'			=> '阜平县',
			'en'			=> 'fu ping xian',
		],
		'132440'	=> [
			'zh-cn'			=> '安国县',
			'en'			=> 'an guo xian',
		],
		'132441'	=> [
			'zh-cn'			=> '博野县',
			'en'			=> 'bo ye xian',
		],
		'132500'	=> [
			'zh-cn'			=> '张家口地区',
			'en'			=> 'zhang jia kou di qu',
		],
		'132501'	=> [
			'zh-cn'			=> '张家口市',
			'en'			=> 'zhang jia kou shi',
		],
		'132502'	=> [
			'zh-cn'			=> '桥东区',
			'en'			=> 'qiao dong qu',
		],
		'132503'	=> [
			'zh-cn'			=> '桥西区',
			'en'			=> 'qiao xi qu',
		],
		'132504'	=> [
			'zh-cn'			=> '茶坊区',
			'en'			=> 'cha fang qu',
		],
		'132505'	=> [
			'zh-cn'			=> '宣化区',
			'en'			=> 'xuan hua qu',
		],
		'132506'	=> [
			'zh-cn'			=> '下花园区',
			'en'			=> 'xia hua yuan qu',
		],
		'132507'	=> [
			'zh-cn'			=> '庞家堡区',
			'en'			=> 'pang jia bao qu',
		],
		'132521'	=> [
			'zh-cn'			=> '张北县',
			'en'			=> 'zhang bei xian',
		],
		'132522'	=> [
			'zh-cn'			=> '康保县',
			'en'			=> 'kang bao xian',
		],
		'132523'	=> [
			'zh-cn'			=> '沽源县',
			'en'			=> 'gu yuan xian',
		],
		'132524'	=> [
			'zh-cn'			=> '尚义县',
			'en'			=> 'shang yi xian',
		],
		'132526'	=> [
			'zh-cn'			=> '阳原县',
			'en'			=> 'yang yuan xian',
		],
		'132527'	=> [
			'zh-cn'			=> '怀安县',
			'en'			=> 'huai an xian',
		],
		'132528'	=> [
			'zh-cn'			=> '万全县',
			'en'			=> 'wan quan xian',
		],
		'132529'	=> [
			'zh-cn'			=> '怀来县',
			'en'			=> 'huai lai xian',
		],
		'132530'	=> [
			'zh-cn'			=> '涿鹿县',
			'en'			=> 'zhuo lu xian',
		],
		'132531'	=> [
			'zh-cn'			=> '宣化县',
			'en'			=> 'xuan hua xian',
		],
		'132532'	=> [
			'zh-cn'			=> '赤城县',
			'en'			=> 'chi cheng xian',
		],
		'132533'	=> [
			'zh-cn'			=> '崇礼县',
			'en'			=> 'chong li xian',
		],
		'132600'	=> [
			'zh-cn'			=> '承德地区',
			'en'			=> 'cheng de di qu',
		],
		'132601'	=> [
			'zh-cn'			=> '承德市',
			'en'			=> 'cheng de shi',
		],
		'132602'	=> [
			'zh-cn'			=> '双桥区',
			'en'			=> 'shuang qiao qu',
		],
		'132603'	=> [
			'zh-cn'			=> '双滦区',
			'en'			=> 'shuang luan qu',
		],
		'132604'	=> [
			'zh-cn'			=> '鹰手营子矿区',
			'en'			=> 'ying shou ying zi kuang qu',
		],
		'132621'	=> [
			'zh-cn'			=> '承德县',
			'en'			=> 'cheng de xian',
		],
		'132622'	=> [
			'zh-cn'			=> '宽城满族自治县',
			'en'			=> 'kuan cheng man zu zi zhi xian',
		],
		'132623'	=> [
			'zh-cn'			=> '兴隆县',
			'en'			=> 'xing long xian',
		],
		'132624'	=> [
			'zh-cn'			=> '平泉县',
			'en'			=> 'ping quan xian',
		],
		'132625'	=> [
			'zh-cn'			=> '青龙县',
			'en'			=> 'qing long xian',
		],
		'132626'	=> [
			'zh-cn'			=> '滦平县',
			'en'			=> 'luan ping xian',
		],
		'132627'	=> [
			'zh-cn'			=> '丰宁满族自治县',
			'en'			=> 'feng ning man zu zi zhi xian',
		],
		'132628'	=> [
			'zh-cn'			=> '隆化县',
			'en'			=> 'long hua xian',
		],
		'132629'	=> [
			'zh-cn'			=> '围场满族蒙古族自治县',
			'en'			=> 'wei chang man zu meng gu zu zi zhi xian',
		],
		'132700'	=> [
			'zh-cn'			=> '唐山地区',
			'en'			=> 'tang shan di qu',
		],
		'132701'	=> [
			'zh-cn'			=> '秦皇岛市',
			'en'			=> 'qin huang dao shi',
		],
		'132702'	=> [
			'zh-cn'			=> '海港区',
			'en'			=> 'hai gang qu',
		],
		'132703'	=> [
			'zh-cn'			=> '山海关区',
			'en'			=> 'shan hai guan qu',
		],
		'132704'	=> [
			'zh-cn'			=> '北戴河区',
			'en'			=> 'bei dai he qu',
		],
		'132721'	=> [
			'zh-cn'			=> '丰润县',
			'en'			=> 'feng run xian',
		],
		'132722'	=> [
			'zh-cn'			=> '丰南县',
			'en'			=> 'feng nan xian',
		],
		'132724'	=> [
			'zh-cn'			=> '滦南县',
			'en'			=> 'luan nan xian',
		],
		'132725'	=> [
			'zh-cn'			=> '乐亭县',
			'en'			=> 'le ting xian',
		],
		'132726'	=> [
			'zh-cn'			=> '迁安县',
			'en'			=> 'qian an xian',
		],
		'132727'	=> [
			'zh-cn'			=> '迁西县',
			'en'			=> 'qian xi xian',
		],
		'132728'	=> [
			'zh-cn'			=> '遵化县',
			'en'			=> 'zun hua xian',
		],
		'132729'	=> [
			'zh-cn'			=> '玉田县',
			'en'			=> 'yu tian xian',
		],
		'132730'	=> [
			'zh-cn'			=> '唐海县',
			'en'			=> 'tang hai xian',
		],
		'132731'	=> [
			'zh-cn'			=> '昌黎县',
			'en'			=> 'chang li xian',
		],
		'132732'	=> [
			'zh-cn'			=> '抚宁县',
			'en'			=> 'fu ning xian',
		],
		'132733'	=> [
			'zh-cn'			=> '卢龙县',
			'en'			=> 'lu long xian',
		],
		'132800'	=> [
			'zh-cn'			=> '廊坊地区',
			'en'			=> 'lang fang di qu',
		],
		'132801'	=> [
			'zh-cn'			=> '廊坊市',
			'en'			=> 'lang fang shi',
		],
		'132821'	=> [
			'zh-cn'			=> '三河县',
			'en'			=> 'san he xian',
		],
		'132822'	=> [
			'zh-cn'			=> '大厂回族自治县',
			'en'			=> 'da chang hui zu zi zhi xian',
		],
		'132823'	=> [
			'zh-cn'			=> '香河县',
			'en'			=> 'xiang he xian',
		],
		'132824'	=> [
			'zh-cn'			=> '安次县',
			'en'			=> 'an ci xian',
		],
		'132825'	=> [
			'zh-cn'			=> '永清县',
			'en'			=> 'yong qing xian',
		],
		'132826'	=> [
			'zh-cn'			=> '固安县',
			'en'			=> 'gu an xian',
		],
		'132828'	=> [
			'zh-cn'			=> '文安县',
			'en'			=> 'wen an xian',
		],
		'132829'	=> [
			'zh-cn'			=> '大城县',
			'en'			=> 'dai cheng xian',
		],
		'132900'	=> [
			'zh-cn'			=> '沧州地区',
			'en'			=> 'cang zhou di qu',
		],
		'132901'	=> [
			'zh-cn'			=> '沧州市',
			'en'			=> 'cang zhou shi',
		],
		'132902'	=> [
			'zh-cn'			=> '泊头市',
			'en'			=> 'bo tou shi',
		],
		'132903'	=> [
			'zh-cn'			=> '任丘市',
			'en'			=> 'ren qiu shi',
		],
		'132904'	=> [
			'zh-cn'			=> '黄骅市',
			'en'			=> 'huang hua shi',
		],
		'132905'	=> [
			'zh-cn'			=> '河间市',
			'en'			=> 'he jian shi',
		],
		'132922'	=> [
			'zh-cn'			=> '河间县',
			'en'			=> 'he jian xian',
		],
		'132923'	=> [
			'zh-cn'			=> '肃宁县',
			'en'			=> 'su ning xian',
		],
		'132925'	=> [
			'zh-cn'			=> '交河县',
			'en'			=> 'jiao he xian',
		],
		'132926'	=> [
			'zh-cn'			=> '吴桥县',
			'en'			=> 'wu qiao xian',
		],
		'132927'	=> [
			'zh-cn'			=> '东光县',
			'en'			=> 'dong guang xian',
		],
		'132928'	=> [
			'zh-cn'			=> '南皮县',
			'en'			=> 'nan pi xian',
		],
		'132929'	=> [
			'zh-cn'			=> '盐山县',
			'en'			=> 'yan shan xian',
		],
		'132930'	=> [
			'zh-cn'			=> '黄骅县',
			'en'			=> 'huang hua xian',
		],
		'132931'	=> [
			'zh-cn'			=> '孟村回族自治县',
			'en'			=> 'meng cun hui zu zi zhi xian',
		],
		'132933'	=> [
			'zh-cn'			=> '任丘县',
			'en'			=> 'ren qiu xian',
		],
		'132934'	=> [
			'zh-cn'			=> '海兴县',
			'en'			=> 'hai xing xian',
		],
		'133000'	=> [
			'zh-cn'			=> '衡水地区',
			'en'			=> 'heng shui di qu',
		],
		'133001'	=> [
			'zh-cn'			=> '衡水市',
			'en'			=> 'heng shui shi',
		],
		'133002'	=> [
			'zh-cn'			=> '冀州市',
			'en'			=> 'ji zhou shi',
		],
		'133003'	=> [
			'zh-cn'			=> '深州市',
			'en'			=> 'shen zhou shi',
		],
		'133021'	=> [
			'zh-cn'			=> '衡水县',
			'en'			=> 'heng shui xian',
		],
		'133023'	=> [
			'zh-cn'			=> '枣强县',
			'en'			=> 'zao qiang xian',
		],
		'133024'	=> [
			'zh-cn'			=> '武邑县',
			'en'			=> 'wu yi xian',
		],
		'133026'	=> [
			'zh-cn'			=> '武强县',
			'en'			=> 'wu qiang xian',
		],
		'133027'	=> [
			'zh-cn'			=> '饶阳县',
			'en'			=> 'rao yang xian',
		],
		'133028'	=> [
			'zh-cn'			=> '安平县',
			'en'			=> 'an ping xian',
		],
		'133029'	=> [
			'zh-cn'			=> '故城县',
			'en'			=> 'gu cheng xian',
		],
		'133031'	=> [
			'zh-cn'			=> '阜城县',
			'en'			=> 'fu cheng xian',
		],
		'139001'	=> [
			'zh-cn'			=> '武安市',
			'en'			=> 'wu an shi',
		],
		'139002'	=> [
			'zh-cn'			=> '霸州市',
			'en'			=> 'ba zhou shi',
		],
		'139003'	=> [
			'zh-cn'			=> '遵化市',
			'en'			=> 'zun hua shi',
		],
		'139004'	=> [
			'zh-cn'			=> '辛集市',
			'en'			=> 'xin ji shi',
		],
		'139005'	=> [
			'zh-cn'			=> '藁城市',
			'en'			=> 'gao cheng shi',
		],
		'139006'	=> [
			'zh-cn'			=> '晋州市',
			'en'			=> 'jin zhou shi',
		],
		'139007'	=> [
			'zh-cn'			=> '新乐市',
			'en'			=> 'xin le shi',
		],
		'139008'	=> [
			'zh-cn'			=> '泊头市',
			'en'			=> 'bo tou shi',
		],
		'139009'	=> [
			'zh-cn'			=> '任丘市',
			'en'			=> 'ren qiu shi',
		],
		'139010'	=> [
			'zh-cn'			=> '黄骅市',
			'en'			=> 'huang hua shi',
		],
		'139011'	=> [
			'zh-cn'			=> '河间市',
			'en'			=> 'he jian shi',
		],
		'139012'	=> [
			'zh-cn'			=> '三河市',
			'en'			=> 'san he shi',
		],
		'139013'	=> [
			'zh-cn'			=> '南宫市',
			'en'			=> 'nan gong shi',
		],
		'139014'	=> [
			'zh-cn'			=> '沙河市',
			'en'			=> 'sha he shi',
		],
		'139015'	=> [
			'zh-cn'			=> '定州市',
			'en'			=> 'ding zhou shi',
		],
		'139016'	=> [
			'zh-cn'			=> '涿州市',
			'en'			=> 'zhuo zhou shi',
		],
		'139017'	=> [
			'zh-cn'			=> '安国市',
			'en'			=> 'an guo shi',
		],
		'139018'	=> [
			'zh-cn'			=> '高碑店市',
			'en'			=> 'gao bei dian shi',
		],
		'139019'	=> [
			'zh-cn'			=> '鹿泉市',
			'en'			=> 'lu quan shi',
		],
		'139020'	=> [
			'zh-cn'			=> '丰南市',
			'en'			=> 'feng nan shi',
		],
		'140000'	=> [
			'zh-cn'			=> '山西省',
			'en'			=> 'shan xi sheng',
		],
		'140100'	=> [
			'zh-cn'			=> '太原市',
			'en'			=> 'tai yuan shi',
		],
		'140102'	=> [
			'zh-cn'			=> '南城区',
			'en'			=> 'nan cheng qu',
		],
		'140103'	=> [
			'zh-cn'			=> '北城区',
			'en'			=> 'bei cheng qu',
		],
		'140104'	=> [
			'zh-cn'			=> '河西区',
			'en'			=> 'he xi qu',
		],
		'140105'	=> [
			'zh-cn'			=> '小店区',
			'en'			=> 'xiao dian qu',
		],
		'140106'	=> [
			'zh-cn'			=> '迎泽区',
			'en'			=> 'ying ze qu',
		],
		'140107'	=> [
			'zh-cn'			=> '杏花岭区',
			'en'			=> 'xing hua ling qu',
		],
		'140108'	=> [
			'zh-cn'			=> '尖草坪区',
			'en'			=> 'jian cao ping qu',
		],
		'140109'	=> [
			'zh-cn'			=> '万柏林区',
			'en'			=> 'wan bo lin qu',
		],
		'140110'	=> [
			'zh-cn'			=> '晋源区',
			'en'			=> 'jin yuan qu',
		],
		'140111'	=> [
			'zh-cn'			=> '古交工矿区',
			'en'			=> 'gu jiao gong kuang qu',
		],
		'140112'	=> [
			'zh-cn'			=> '南郊区',
			'en'			=> 'nan jiao qu',
		],
		'140113'	=> [
			'zh-cn'			=> '北郊区',
			'en'			=> 'bei jiao qu',
		],
		'140121'	=> [
			'zh-cn'			=> '清徐县',
			'en'			=> 'qing xu xian',
		],
		'140122'	=> [
			'zh-cn'			=> '阳曲县',
			'en'			=> 'yang qu xian',
		],
		'140123'	=> [
			'zh-cn'			=> '娄烦县',
			'en'			=> 'lou fan xian',
		],
		'140181'	=> [
			'zh-cn'			=> '古交市',
			'en'			=> 'gu jiao shi',
		],
		'140200'	=> [
			'zh-cn'			=> '大同市',
			'en'			=> 'da tong shi',
		],
		'140211'	=> [
			'zh-cn'			=> '南郊区',
			'en'			=> 'nan jiao qu',
		],
		'140212'	=> [
			'zh-cn'			=> '新荣区',
			'en'			=> 'xin rong qu',
		],
		'140221'	=> [
			'zh-cn'			=> '阳高县',
			'en'			=> 'yang gao xian',
		],
		'140222'	=> [
			'zh-cn'			=> '天镇县',
			'en'			=> 'tian zhen xian',
		],
		'140223'	=> [
			'zh-cn'			=> '广灵县',
			'en'			=> 'guang ling xian',
		],
		'140224'	=> [
			'zh-cn'			=> '灵丘县',
			'en'			=> 'ling qiu xian',
		],
		'140225'	=> [
			'zh-cn'			=> '浑源县',
			'en'			=> 'hun yuan xian',
		],
		'140226'	=> [
			'zh-cn'			=> '左云县',
			'en'			=> 'zuo yun xian',
		],
		'140227'	=> [
			'zh-cn'			=> '大同县',
			'en'			=> 'da tong xian',
		],
		'140300'	=> [
			'zh-cn'			=> '阳泉市',
			'en'			=> 'yang quan shi',
		],
		'140321'	=> [
			'zh-cn'			=> '平定县',
			'en'			=> 'ping ding xian',
		],
		'140400'	=> [
			'zh-cn'			=> '长治市',
			'en'			=> 'chang zhi shi',
		],
		'140421'	=> [
			'zh-cn'			=> '长治县',
			'en'			=> 'chang zhi xian',
		],
		'140422'	=> [
			'zh-cn'			=> '潞城县',
			'en'			=> 'lu cheng xian',
		],
		'140423'	=> [
			'zh-cn'			=> '襄垣县',
			'en'			=> 'xiang yuan xian',
		],
		'140424'	=> [
			'zh-cn'			=> '屯留县',
			'en'			=> 'tun liu xian',
		],
		'140425'	=> [
			'zh-cn'			=> '平顺县',
			'en'			=> 'ping shun xian',
		],
		'140426'	=> [
			'zh-cn'			=> '黎城县',
			'en'			=> 'li cheng xian',
		],
		'140427'	=> [
			'zh-cn'			=> '壶关县',
			'en'			=> 'hu guan xian',
		],
		'140428'	=> [
			'zh-cn'			=> '长子县',
			'en'			=> 'chang zi xian',
		],
		'140429'	=> [
			'zh-cn'			=> '武乡县',
			'en'			=> 'wu xiang xian',
		],
		'140431'	=> [
			'zh-cn'			=> '沁源县',
			'en'			=> 'qin yuan xian',
		],
		'140481'	=> [
			'zh-cn'			=> '潞城市',
			'en'			=> 'lu cheng shi',
		],
		'140500'	=> [
			'zh-cn'			=> '晋城市',
			'en'			=> 'jin cheng shi',
		],
		'140521'	=> [
			'zh-cn'			=> '沁水县',
			'en'			=> 'qin shui xian',
		],
		'140522'	=> [
			'zh-cn'			=> '阳城县',
			'en'			=> 'yang cheng xian',
		],
		'140523'	=> [
			'zh-cn'			=> '高平县',
			'en'			=> 'gao ping xian',
		],
		'140524'	=> [
			'zh-cn'			=> '陵川县',
			'en'			=> 'ling chuan xian',
		],
		'140525'	=> [
			'zh-cn'			=> '泽州县',
			'en'			=> 'ze zhou xian',
		],
		'140581'	=> [
			'zh-cn'			=> '高平市',
			'en'			=> 'gao ping shi',
		],
		'140600'	=> [
			'zh-cn'			=> '朔州市',
			'en'			=> 'shuo zhou shi',
		],
		'140602'	=> [
			'zh-cn'			=> '朔城区',
			'en'			=> 'shuo cheng qu',
		],
		'140603'	=> [
			'zh-cn'			=> '平鲁区',
			'en'			=> 'ping lu qu',
		],
		'140621'	=> [
			'zh-cn'			=> '山阴县',
			'en'			=> 'shan yin xian',
		],
		'140623'	=> [
			'zh-cn'			=> '右玉县',
			'en'			=> 'you yu xian',
		],
		'140624'	=> [
			'zh-cn'			=> '怀仁县',
			'en'			=> 'huai ren xian',
		],
		'140700'	=> [
			'zh-cn'			=> '晋中市',
			'en'			=> 'jin zhong shi',
		],
		'140702'	=> [
			'zh-cn'			=> '榆次区',
			'en'			=> 'yu ci qu',
		],
		'140721'	=> [
			'zh-cn'			=> '榆社县',
			'en'			=> 'yu she xian',
		],
		'140722'	=> [
			'zh-cn'			=> '左权县',
			'en'			=> 'zuo quan xian',
		],
		'140723'	=> [
			'zh-cn'			=> '和顺县',
			'en'			=> 'he shun xian',
		],
		'140724'	=> [
			'zh-cn'			=> '昔阳县',
			'en'			=> 'xi yang xian',
		],
		'140725'	=> [
			'zh-cn'			=> '寿阳县',
			'en'			=> 'shou yang xian',
		],
		'140726'	=> [
			'zh-cn'			=> '太谷县',
			'en'			=> 'tai gu xian',
		],
		'140728'	=> [
			'zh-cn'			=> '平遥县',
			'en'			=> 'ping yao xian',
		],
		'140729'	=> [
			'zh-cn'			=> '灵石县',
			'en'			=> 'ling shi xian',
		],
		'140781'	=> [
			'zh-cn'			=> '介休市',
			'en'			=> 'jie xiu shi',
		],
		'140800'	=> [
			'zh-cn'			=> '运城市',
			'en'			=> 'yun cheng shi',
		],
		'140802'	=> [
			'zh-cn'			=> '盐湖区',
			'en'			=> 'yan hu qu',
		],
		'140821'	=> [
			'zh-cn'			=> '临猗县',
			'en'			=> 'lin yi xian',
		],
		'140822'	=> [
			'zh-cn'			=> '万荣县',
			'en'			=> 'wan rong xian',
		],
		'140823'	=> [
			'zh-cn'			=> '闻喜县',
			'en'			=> 'wen xi xian',
		],
		'140824'	=> [
			'zh-cn'			=> '稷山县',
			'en'			=> 'ji shan xian',
		],
		'140825'	=> [
			'zh-cn'			=> '新绛县',
			'en'			=> 'xin jiang xian',
		],
		'140827'	=> [
			'zh-cn'			=> '垣曲县',
			'en'			=> 'yuan qu xian',
		],
		'140829'	=> [
			'zh-cn'			=> '平陆县',
			'en'			=> 'ping lu xian',
		],
		'140830'	=> [
			'zh-cn'			=> '芮城县',
			'en'			=> 'rui cheng xian',
		],
		'140881'	=> [
			'zh-cn'			=> '永济市',
			'en'			=> 'yong ji shi',
		],
		'140882'	=> [
			'zh-cn'			=> '河津市',
			'en'			=> 'he jin shi',
		],
		'140900'	=> [
			'zh-cn'			=> '忻州市',
			'en'			=> 'xin zhou shi',
		],
		'140902'	=> [
			'zh-cn'			=> '忻府区',
			'en'			=> 'xin fu qu',
		],
		'140921'	=> [
			'zh-cn'			=> '定襄县',
			'en'			=> 'ding xiang xian',
		],
		'140922'	=> [
			'zh-cn'			=> '五台县',
			'en'			=> 'wu tai xian',
		],
		'140924'	=> [
			'zh-cn'			=> '繁峙县',
			'en'			=> 'fan shi xian',
		],
		'140925'	=> [
			'zh-cn'			=> '宁武县',
			'en'			=> 'ning wu xian',
		],
		'140926'	=> [
			'zh-cn'			=> '静乐县',
			'en'			=> 'jing le xian',
		],
		'140927'	=> [
			'zh-cn'			=> '神池县',
			'en'			=> 'shen chi xian',
		],
		'140928'	=> [
			'zh-cn'			=> '五寨县',
			'en'			=> 'wu zhai xian',
		],
		'140929'	=> [
			'zh-cn'			=> '岢岚县',
			'en'			=> 'ke lan xian',
		],
		'140930'	=> [
			'zh-cn'			=> '河曲县',
			'en'			=> 'he qu xian',
		],
		'140931'	=> [
			'zh-cn'			=> '保德县',
			'en'			=> 'bao de xian',
		],
		'140932'	=> [
			'zh-cn'			=> '偏关县',
			'en'			=> 'pian guan xian',
		],
		'140981'	=> [
			'zh-cn'			=> '原平市',
			'en'			=> 'yuan ping shi',
		],
		'141000'	=> [
			'zh-cn'			=> '临汾市',
			'en'			=> 'lin fen shi',
		],
		'141002'	=> [
			'zh-cn'			=> '尧都区',
			'en'			=> 'yao du qu',
		],
		'141021'	=> [
			'zh-cn'			=> '曲沃县',
			'en'			=> 'qu wo xian',
		],
		'141022'	=> [
			'zh-cn'			=> '翼城县',
			'en'			=> 'yi cheng xian',
		],
		'141023'	=> [
			'zh-cn'			=> '襄汾县',
			'en'			=> 'xiang fen xian',
		],
		'141024'	=> [
			'zh-cn'			=> '洪洞县',
			'en'			=> 'hong tong xian',
		],
		'141026'	=> [
			'zh-cn'			=> '安泽县',
			'en'			=> 'an ze xian',
		],
		'141027'	=> [
			'zh-cn'			=> '浮山县',
			'en'			=> 'fu shan xian',
		],
		'141029'	=> [
			'zh-cn'			=> '乡宁县',
			'en'			=> 'xiang ning xian',
		],
		'141030'	=> [
			'zh-cn'			=> '大宁县',
			'en'			=> 'da ning xian',
		],
		'141032'	=> [
			'zh-cn'			=> '永和县',
			'en'			=> 'yong he xian',
		],
		'141034'	=> [
			'zh-cn'			=> '汾西县',
			'en'			=> 'fen xi xian',
		],
		'141081'	=> [
			'zh-cn'			=> '侯马市',
			'en'			=> 'hou ma shi',
		],
		'141082'	=> [
			'zh-cn'			=> '霍州市',
			'en'			=> 'huo zhou shi',
		],
		'141100'	=> [
			'zh-cn'			=> '吕梁市',
			'en'			=> 'lv liang shi',
		],
		'141102'	=> [
			'zh-cn'			=> '离石区',
			'en'			=> 'li shi qu',
		],
		'141121'	=> [
			'zh-cn'			=> '文水县',
			'en'			=> 'wen shui xian',
		],
		'141122'	=> [
			'zh-cn'			=> '交城县',
			'en'			=> 'jiao cheng xian',
		],
		'141125'	=> [
			'zh-cn'			=> '柳林县',
			'en'			=> 'liu lin xian',
		],
		'141126'	=> [
			'zh-cn'			=> '石楼县',
			'en'			=> 'shi lou xian',
		],
		'141128'	=> [
			'zh-cn'			=> '方山县',
			'en'			=> 'fang shan xian',
		],
		'141129'	=> [
			'zh-cn'			=> '中阳县',
			'en'			=> 'zhong yang xian',
		],
		'141130'	=> [
			'zh-cn'			=> '交口县',
			'en'			=> 'jiao kou xian',
		],
		'141181'	=> [
			'zh-cn'			=> '孝义市',
			'en'			=> 'xiao yi shi',
		],
		'141182'	=> [
			'zh-cn'			=> '汾阳市',
			'en'			=> 'fen yang shi',
		],
		'142100'	=> [
			'zh-cn'			=> '雁北地区',
			'en'			=> 'yan bei di qu',
		],
		'142121'	=> [
			'zh-cn'			=> '阳高县',
			'en'			=> 'yang gao xian',
		],
		'142122'	=> [
			'zh-cn'			=> '天镇县',
			'en'			=> 'tian zhen xian',
		],
		'142123'	=> [
			'zh-cn'			=> '广灵县',
			'en'			=> 'guang ling xian',
		],
		'142124'	=> [
			'zh-cn'			=> '灵丘县',
			'en'			=> 'ling qiu xian',
		],
		'142125'	=> [
			'zh-cn'			=> '浑源县',
			'en'			=> 'hun yuan xian',
		],
		'142127'	=> [
			'zh-cn'			=> '山阴县',
			'en'			=> 'shan yin xian',
		],
		'142129'	=> [
			'zh-cn'			=> '平鲁县',
			'en'			=> 'ping lu xian',
		],
		'142130'	=> [
			'zh-cn'			=> '左云县',
			'en'			=> 'zuo yun xian',
		],
		'142131'	=> [
			'zh-cn'			=> '右玉县',
			'en'			=> 'you yu xian',
		],
		'142132'	=> [
			'zh-cn'			=> '大同县',
			'en'			=> 'da tong xian',
		],
		'142133'	=> [
			'zh-cn'			=> '怀仁县',
			'en'			=> 'huai ren xian',
		],
		'142200'	=> [
			'zh-cn'			=> '忻州地区',
			'en'			=> 'xin zhou di qu',
		],
		'142201'	=> [
			'zh-cn'			=> '忻州市',
			'en'			=> 'xin zhou shi',
		],
		'142202'	=> [
			'zh-cn'			=> '原平市',
			'en'			=> 'yuan ping shi',
		],
		'142222'	=> [
			'zh-cn'			=> '定襄县',
			'en'			=> 'ding xiang xian',
		],
		'142223'	=> [
			'zh-cn'			=> '五台县',
			'en'			=> 'wu tai xian',
		],
		'142224'	=> [
			'zh-cn'			=> '原平县',
			'en'			=> 'yuan ping xian',
		],
		'142226'	=> [
			'zh-cn'			=> '繁峙县',
			'en'			=> 'fan shi xian',
		],
		'142227'	=> [
			'zh-cn'			=> '宁武县',
			'en'			=> 'ning wu xian',
		],
		'142228'	=> [
			'zh-cn'			=> '静乐县',
			'en'			=> 'jing le xian',
		],
		'142229'	=> [
			'zh-cn'			=> '神池县',
			'en'			=> 'shen chi xian',
		],
		'142230'	=> [
			'zh-cn'			=> '五寨县',
			'en'			=> 'wu zhai xian',
		],
		'142231'	=> [
			'zh-cn'			=> '岢岚县',
			'en'			=> 'ke lan xian',
		],
		'142232'	=> [
			'zh-cn'			=> '河曲县',
			'en'			=> 'he qu xian',
		],
		'142233'	=> [
			'zh-cn'			=> '保德县',
			'en'			=> 'bao de xian',
		],
		'142234'	=> [
			'zh-cn'			=> '偏关县',
			'en'			=> 'pian guan xian',
		],
		'142300'	=> [
			'zh-cn'			=> '吕梁地区',
			'en'			=> 'lv liang di qu',
		],
		'142301'	=> [
			'zh-cn'			=> '孝义市',
			'en'			=> 'xiao yi shi',
		],
		'142302'	=> [
			'zh-cn'			=> '离石市',
			'en'			=> 'li shi shi',
		],
		'142303'	=> [
			'zh-cn'			=> '汾阳市',
			'en'			=> 'fen yang shi',
		],
		'142321'	=> [
			'zh-cn'			=> '汾阳县',
			'en'			=> 'fen yang xian',
		],
		'142322'	=> [
			'zh-cn'			=> '文水县',
			'en'			=> 'wen shui xian',
		],
		'142323'	=> [
			'zh-cn'			=> '交城县',
			'en'			=> 'jiao cheng xian',
		],
		'142324'	=> [
			'zh-cn'			=> '孝义县',
			'en'			=> 'xiao yi xian',
		],
		'142327'	=> [
			'zh-cn'			=> '柳林县',
			'en'			=> 'liu lin xian',
		],
		'142328'	=> [
			'zh-cn'			=> '石楼县',
			'en'			=> 'shi lou xian',
		],
		'142330'	=> [
			'zh-cn'			=> '方山县',
			'en'			=> 'fang shan xian',
		],
		'142331'	=> [
			'zh-cn'			=> '离石县',
			'en'			=> 'li shi xian',
		],
		'142332'	=> [
			'zh-cn'			=> '中阳县',
			'en'			=> 'zhong yang xian',
		],
		'142333'	=> [
			'zh-cn'			=> '交口县',
			'en'			=> 'jiao kou xian',
		],
		'142400'	=> [
			'zh-cn'			=> '晋中地区',
			'en'			=> 'jin zhong di qu',
		],
		'142401'	=> [
			'zh-cn'			=> '榆次市',
			'en'			=> 'yu ci shi',
		],
		'142402'	=> [
			'zh-cn'			=> '介休市',
			'en'			=> 'jie xiu shi',
		],
		'142421'	=> [
			'zh-cn'			=> '榆社县',
			'en'			=> 'yu she xian',
		],
		'142422'	=> [
			'zh-cn'			=> '左权县',
			'en'			=> 'zuo quan xian',
		],
		'142423'	=> [
			'zh-cn'			=> '和顺县',
			'en'			=> 'he shun xian',
		],
		'142424'	=> [
			'zh-cn'			=> '昔阳县',
			'en'			=> 'xi yang xian',
		],
		'142425'	=> [
			'zh-cn'			=> '平定县',
			'en'			=> 'ping ding xian',
		],
		'142427'	=> [
			'zh-cn'			=> '寿阳县',
			'en'			=> 'shou yang xian',
		],
		'142428'	=> [
			'zh-cn'			=> '榆次县',
			'en'			=> 'yu ci xian',
		],
		'142429'	=> [
			'zh-cn'			=> '太谷县',
			'en'			=> 'tai gu xian',
		],
		'142431'	=> [
			'zh-cn'			=> '平遥县',
			'en'			=> 'ping yao xian',
		],
		'142432'	=> [
			'zh-cn'			=> '介休县',
			'en'			=> 'jie xiu xian',
		],
		'142433'	=> [
			'zh-cn'			=> '灵石县',
			'en'			=> 'ling shi xian',
		],
		'142500'	=> [
			'zh-cn'			=> '晋东南地区',
			'en'			=> 'jin dong nan di qu',
		],
		'142501'	=> [
			'zh-cn'			=> '晋城市',
			'en'			=> 'jin cheng shi',
		],
		'142521'	=> [
			'zh-cn'			=> '长治县',
			'en'			=> 'chang zhi xian',
		],
		'142522'	=> [
			'zh-cn'			=> '潞城县',
			'en'			=> 'lu cheng xian',
		],
		'142523'	=> [
			'zh-cn'			=> '屯留县',
			'en'			=> 'tun liu xian',
		],
		'142524'	=> [
			'zh-cn'			=> '长子县',
			'en'			=> 'chang zi xian',
		],
		'142525'	=> [
			'zh-cn'			=> '沁水县',
			'en'			=> 'qin shui xian',
		],
		'142526'	=> [
			'zh-cn'			=> '阳城县',
			'en'			=> 'yang cheng xian',
		],
		'142527'	=> [
			'zh-cn'			=> '晋城县',
			'en'			=> 'jin cheng xian',
		],
		'142528'	=> [
			'zh-cn'			=> '高平县',
			'en'			=> 'gao ping xian',
		],
		'142529'	=> [
			'zh-cn'			=> '陵川县',
			'en'			=> 'ling chuan xian',
		],
		'142530'	=> [
			'zh-cn'			=> '壶关县',
			'en'			=> 'hu guan xian',
		],
		'142531'	=> [
			'zh-cn'			=> '平顺县',
			'en'			=> 'ping shun xian',
		],
		'142532'	=> [
			'zh-cn'			=> '黎城县',
			'en'			=> 'li cheng xian',
		],
		'142533'	=> [
			'zh-cn'			=> '武乡县',
			'en'			=> 'wu xiang xian',
		],
		'142534'	=> [
			'zh-cn'			=> '襄垣县',
			'en'			=> 'xiang yuan xian',
		],
		'142536'	=> [
			'zh-cn'			=> '沁源县',
			'en'			=> 'qin yuan xian',
		],
		'142600'	=> [
			'zh-cn'			=> '临汾地区',
			'en'			=> 'lin fen di qu',
		],
		'142601'	=> [
			'zh-cn'			=> '临汾市',
			'en'			=> 'lin fen shi',
		],
		'142602'	=> [
			'zh-cn'			=> '侯马市',
			'en'			=> 'hou ma shi',
		],
		'142603'	=> [
			'zh-cn'			=> '霍州市',
			'en'			=> 'huo zhou shi',
		],
		'142621'	=> [
			'zh-cn'			=> '曲沃县',
			'en'			=> 'qu wo xian',
		],
		'142622'	=> [
			'zh-cn'			=> '翼城县',
			'en'			=> 'yi cheng xian',
		],
		'142623'	=> [
			'zh-cn'			=> '襄汾县',
			'en'			=> 'xiang fen xian',
		],
		'142624'	=> [
			'zh-cn'			=> '临汾县',
			'en'			=> 'lin fen xian',
		],
		'142625'	=> [
			'zh-cn'			=> '洪洞县',
			'en'			=> 'hong tong xian',
		],
		'142628'	=> [
			'zh-cn'			=> '安泽县',
			'en'			=> 'an ze xian',
		],
		'142629'	=> [
			'zh-cn'			=> '浮山县',
			'en'			=> 'fu shan xian',
		],
		'142631'	=> [
			'zh-cn'			=> '乡宁县',
			'en'			=> 'xiang ning xian',
		],
		'142633'	=> [
			'zh-cn'			=> '大宁县',
			'en'			=> 'da ning xian',
		],
		'142634'	=> [
			'zh-cn'			=> '永和县',
			'en'			=> 'yong he xian',
		],
		'142636'	=> [
			'zh-cn'			=> '汾西县',
			'en'			=> 'fen xi xian',
		],
		'142700'	=> [
			'zh-cn'			=> '运城地区',
			'en'			=> 'yun cheng di qu',
		],
		'142701'	=> [
			'zh-cn'			=> '运城市',
			'en'			=> 'yun cheng shi',
		],
		'142702'	=> [
			'zh-cn'			=> '河津市',
			'en'			=> 'he jin shi',
		],
		'142703'	=> [
			'zh-cn'			=> '永济市',
			'en'			=> 'yong ji shi',
		],
		'142721'	=> [
			'zh-cn'			=> '运城县',
			'en'			=> 'yun cheng xian',
		],
		'142722'	=> [
			'zh-cn'			=> '永济县',
			'en'			=> 'yong ji xian',
		],
		'142723'	=> [
			'zh-cn'			=> '芮城县',
			'en'			=> 'rui cheng xian',
		],
		'142724'	=> [
			'zh-cn'			=> '临猗县',
			'en'			=> 'lin yi xian',
		],
		'142725'	=> [
			'zh-cn'			=> '万荣县',
			'en'			=> 'wan rong xian',
		],
		'142726'	=> [
			'zh-cn'			=> '新绛县',
			'en'			=> 'xin jiang xian',
		],
		'142727'	=> [
			'zh-cn'			=> '稷山县',
			'en'			=> 'ji shan xian',
		],
		'142728'	=> [
			'zh-cn'			=> '河津县',
			'en'			=> 'he jin xian',
		],
		'142729'	=> [
			'zh-cn'			=> '闻喜县',
			'en'			=> 'wen xi xian',
		],
		'142732'	=> [
			'zh-cn'			=> '平陆县',
			'en'			=> 'ping lu xian',
		],
		'142733'	=> [
			'zh-cn'			=> '垣曲县',
			'en'			=> 'yuan qu xian',
		],
		'149001'	=> [
			'zh-cn'			=> '古交市',
			'en'			=> 'gu jiao shi',
		],
		'149002'	=> [
			'zh-cn'			=> '高平市',
			'en'			=> 'gao ping shi',
		],
		'149003'	=> [
			'zh-cn'			=> '潞城市',
			'en'			=> 'lu cheng shi',
		],
		'150000'	=> [
			'zh-cn'			=> '内蒙古自治区',
			'en'			=> 'nei meng gu zi zhi qu',
		],
		'150100'	=> [
			'zh-cn'			=> '呼和浩特市',
			'en'			=> 'hu he hao te shi',
		],
		'150102'	=> [
			'zh-cn'			=> '新城区',
			'en'			=> 'xin cheng qu',
		],
		'150103'	=> [
			'zh-cn'			=> '回民区',
			'en'			=> 'hui min qu',
		],
		'150104'	=> [
			'zh-cn'			=> '玉泉区',
			'en'			=> 'yu quan qu',
		],
		'150105'	=> [
			'zh-cn'			=> '赛罕区',
			'en'			=> 'sai han qu',
		],
		'150121'	=> [
			'zh-cn'			=> '土默特左旗',
			'en'			=> 'tu mo te zuo qi',
		],
		'150122'	=> [
			'zh-cn'			=> '托克托县',
			'en'			=> 'tuo ke tuo xian',
		],
		'150123'	=> [
			'zh-cn'			=> '和林格尔县',
			'en'			=> 'he lin ge er xian',
		],
		'150124'	=> [
			'zh-cn'			=> '清水河县',
			'en'			=> 'qing shui he xian',
		],
		'150125'	=> [
			'zh-cn'			=> '武川县',
			'en'			=> 'wu chuan xian',
		],
		'150200'	=> [
			'zh-cn'			=> '包头市',
			'en'			=> 'bao tou shi',
		],
		'150202'	=> [
			'zh-cn'			=> '东河区',
			'en'			=> 'dong he qu',
		],
		'150203'	=> [
			'zh-cn'			=> '昆都仑区',
			'en'			=> 'kun du lun qu',
		],
		'150204'	=> [
			'zh-cn'			=> '青山区',
			'en'			=> 'qing shan qu',
		],
		'150205'	=> [
			'zh-cn'			=> '石拐区',
			'en'			=> 'shi guai qu',
		],
		'150206'	=> [
			'zh-cn'			=> '白云鄂博矿区',
			'en'			=> 'bai yun e bo kuang qu',
		],
		'150207'	=> [
			'zh-cn'			=> '九原区',
			'en'			=> 'jiu yuan qu',
		],
		'150221'	=> [
			'zh-cn'			=> '土默特右旗',
			'en'			=> 'tu mo te you qi',
		],
		'150222'	=> [
			'zh-cn'			=> '固阳县',
			'en'			=> 'gu yang xian',
		],
		'150223'	=> [
			'zh-cn'			=> '达尔罕茂明安联合旗',
			'en'			=> 'da er han mao ming an lian he qi',
		],
		'150300'	=> [
			'zh-cn'			=> '乌海市',
			'en'			=> 'wu hai shi',
		],
		'150302'	=> [
			'zh-cn'			=> '海勃湾区',
			'en'			=> 'hai bo wan qu',
		],
		'150303'	=> [
			'zh-cn'			=> '海南区',
			'en'			=> 'hai nan qu',
		],
		'150304'	=> [
			'zh-cn'			=> '乌达区',
			'en'			=> 'wu da qu',
		],
		'150400'	=> [
			'zh-cn'			=> '赤峰市',
			'en'			=> 'chi feng shi',
		],
		'150402'	=> [
			'zh-cn'			=> '红山区',
			'en'			=> 'hong shan qu',
		],
		'150403'	=> [
			'zh-cn'			=> '元宝山区',
			'en'			=> 'yuan bao shan qu',
		],
		'150404'	=> [
			'zh-cn'			=> '松山区',
			'en'			=> 'song shan qu',
		],
		'150421'	=> [
			'zh-cn'			=> '阿鲁科尔沁旗',
			'en'			=> 'a lu ke er qin qi',
		],
		'150422'	=> [
			'zh-cn'			=> '巴林左旗',
			'en'			=> 'ba lin zuo qi',
		],
		'150423'	=> [
			'zh-cn'			=> '巴林右旗',
			'en'			=> 'ba lin you qi',
		],
		'150424'	=> [
			'zh-cn'			=> '林西县',
			'en'			=> 'lin xi xian',
		],
		'150425'	=> [
			'zh-cn'			=> '克什克腾旗',
			'en'			=> 'ke shi ke teng qi',
		],
		'150426'	=> [
			'zh-cn'			=> '翁牛特旗',
			'en'			=> 'weng niu te qi',
		],
		'150428'	=> [
			'zh-cn'			=> '喀喇沁旗',
			'en'			=> 'ka la qin qi',
		],
		'150429'	=> [
			'zh-cn'			=> '宁城县',
			'en'			=> 'ning cheng xian',
		],
		'150430'	=> [
			'zh-cn'			=> '敖汉旗',
			'en'			=> 'ao han qi',
		],
		'150500'	=> [
			'zh-cn'			=> '通辽市',
			'en'			=> 'tong liao shi',
		],
		'150502'	=> [
			'zh-cn'			=> '科尔沁区',
			'en'			=> 'ke er qin qu',
		],
		'150521'	=> [
			'zh-cn'			=> '科尔沁左翼中旗',
			'en'			=> 'ke er qin zuo yi zhong qi',
		],
		'150522'	=> [
			'zh-cn'			=> '科尔沁左翼后旗',
			'en'			=> 'ke er qin zuo yi hou qi',
		],
		'150523'	=> [
			'zh-cn'			=> '开鲁县',
			'en'			=> 'kai lu xian',
		],
		'150524'	=> [
			'zh-cn'			=> '库伦旗',
			'en'			=> 'ku lun qi',
		],
		'150525'	=> [
			'zh-cn'			=> '奈曼旗',
			'en'			=> 'nai man qi',
		],
		'150526'	=> [
			'zh-cn'			=> '扎鲁特旗',
			'en'			=> 'za lu te qi',
		],
		'150581'	=> [
			'zh-cn'			=> '霍林郭勒市',
			'en'			=> 'huo lin guo le shi',
		],
		'150600'	=> [
			'zh-cn'			=> '鄂尔多斯市',
			'en'			=> 'e er duo si shi',
		],
		'150602'	=> [
			'zh-cn'			=> '东胜区',
			'en'			=> 'dong sheng qu',
		],
		'150603'	=> [
			'zh-cn'			=> '康巴什区',
			'en'			=> 'kang ba shen qu',
		],
		'150621'	=> [
			'zh-cn'			=> '达拉特旗',
			'en'			=> 'da la te qi',
		],
		'150622'	=> [
			'zh-cn'			=> '准格尔旗',
			'en'			=> 'zhun ge er qi',
		],
		'150623'	=> [
			'zh-cn'			=> '鄂托克前旗',
			'en'			=> 'e tuo ke qian qi',
		],
		'150624'	=> [
			'zh-cn'			=> '鄂托克旗',
			'en'			=> 'e tuo ke qi',
		],
		'150625'	=> [
			'zh-cn'			=> '杭锦旗',
			'en'			=> 'hang jin qi',
		],
		'150626'	=> [
			'zh-cn'			=> '乌审旗',
			'en'			=> 'wu shen qi',
		],
		'150627'	=> [
			'zh-cn'			=> '伊金霍洛旗',
			'en'			=> 'yi jin huo luo qi',
		],
		'150700'	=> [
			'zh-cn'			=> '呼伦贝尔市',
			'en'			=> 'hu lun bei er shi',
		],
		'150702'	=> [
			'zh-cn'			=> '海拉尔区',
			'en'			=> 'hai la er qu',
		],
		'150703'	=> [
			'zh-cn'			=> '扎赉诺尔区',
			'en'			=> 'zha lai nuo er qu',
		],
		'150721'	=> [
			'zh-cn'			=> '阿荣旗',
			'en'			=> 'a rong qi',
		],
		'150722'	=> [
			'zh-cn'			=> '莫力达瓦达斡尔族自治旗',
			'en'			=> 'mo li da wa da wo er zu zi zhi qi',
		],
		'150723'	=> [
			'zh-cn'			=> '鄂伦春自治旗',
			'en'			=> 'e lun chun zi zhi qi',
		],
		'150724'	=> [
			'zh-cn'			=> '鄂温克族自治旗',
			'en'			=> 'e wen ke zu zi zhi qi',
		],
		'150725'	=> [
			'zh-cn'			=> '陈巴尔虎旗',
			'en'			=> 'chen ba er hu qi',
		],
		'150726'	=> [
			'zh-cn'			=> '新巴尔虎左旗',
			'en'			=> 'xin ba er hu zuo qi',
		],
		'150727'	=> [
			'zh-cn'			=> '新巴尔虎右旗',
			'en'			=> 'xin ba er hu you qi',
		],
		'150781'	=> [
			'zh-cn'			=> '满洲里市',
			'en'			=> 'man zhou li shi',
		],
		'150782'	=> [
			'zh-cn'			=> '牙克石市',
			'en'			=> 'ya ke shi shi',
		],
		'150783'	=> [
			'zh-cn'			=> '扎兰屯市',
			'en'			=> 'zha lan tun shi',
		],
		'150784'	=> [
			'zh-cn'			=> '额尔古纳市',
			'en'			=> 'e er gu na shi',
		],
		'150785'	=> [
			'zh-cn'			=> '根河市',
			'en'			=> 'gen he shi',
		],
		'150800'	=> [
			'zh-cn'			=> '巴彦淖尔市',
			'en'			=> 'ba yan nao er shi',
		],
		'150802'	=> [
			'zh-cn'			=> '临河区',
			'en'			=> 'lin he qu',
		],
		'150821'	=> [
			'zh-cn'			=> '五原县',
			'en'			=> 'wu yuan xian',
		],
		'150822'	=> [
			'zh-cn'			=> '磴口县',
			'en'			=> 'deng kou xian',
		],
		'150823'	=> [
			'zh-cn'			=> '乌拉特前旗',
			'en'			=> 'wu l te qian qi',
		],
		'150824'	=> [
			'zh-cn'			=> '乌拉特中旗',
			'en'			=> 'wu l te zhong qi',
		],
		'150825'	=> [
			'zh-cn'			=> '乌拉特后旗',
			'en'			=> 'wu l te hou qi',
		],
		'150826'	=> [
			'zh-cn'			=> '杭锦后旗',
			'en'			=> 'hang jin hou qi',
		],
		'150900'	=> [
			'zh-cn'			=> '乌兰察布市',
			'en'			=> 'wu lan cha bu shi',
		],
		'150902'	=> [
			'zh-cn'			=> '集宁区',
			'en'			=> 'ji ning qu',
		],
		'150921'	=> [
			'zh-cn'			=> '卓资县',
			'en'			=> 'zhuo zi xian',
		],
		'150922'	=> [
			'zh-cn'			=> '化德县',
			'en'			=> 'hua de xian',
		],
		'150923'	=> [
			'zh-cn'			=> '商都县',
			'en'			=> 'shang du xian',
		],
		'150924'	=> [
			'zh-cn'			=> '兴和县',
			'en'			=> 'xing he xian',
		],
		'150925'	=> [
			'zh-cn'			=> '凉城县',
			'en'			=> 'liang cheng xian',
		],
		'150926'	=> [
			'zh-cn'			=> '察哈尔右翼前旗',
			'en'			=> 'cha ha er you yi qian qi',
		],
		'150927'	=> [
			'zh-cn'			=> '察哈尔右翼中旗',
			'en'			=> 'cha ha er you yi zhong qi',
		],
		'150928'	=> [
			'zh-cn'			=> '察哈尔右翼后旗',
			'en'			=> 'cha ha er you yi hou qi',
		],
		'150929'	=> [
			'zh-cn'			=> '四子王旗',
			'en'			=> 'si zi wang qi',
		],
		'150981'	=> [
			'zh-cn'			=> '丰镇市',
			'en'			=> 'feng zhen shi',
		],
		'152100'	=> [
			'zh-cn'			=> '呼伦贝尔盟',
			'en'			=> 'hu lun bei er meng',
		],
		'152101'	=> [
			'zh-cn'			=> '海拉尔市',
			'en'			=> 'hai la er shi',
		],
		'152102'	=> [
			'zh-cn'			=> '满洲里市',
			'en'			=> 'man zhou li shi',
		],
		'152103'	=> [
			'zh-cn'			=> '扎兰屯市',
			'en'			=> 'zha lan tun shi',
		],
		'152104'	=> [
			'zh-cn'			=> '牙克石市',
			'en'			=> 'ya ke shi shi',
		],
		'152105'	=> [
			'zh-cn'			=> '根河市',
			'en'			=> 'gen he shi',
		],
		'152106'	=> [
			'zh-cn'			=> '额尔古纳市',
			'en'			=> 'e er gu na shi',
		],
		'152121'	=> [
			'zh-cn'			=> '喜桂图旗',
			'en'			=> 'xi gui tu qi',
		],
		'152122'	=> [
			'zh-cn'			=> '阿荣旗',
			'en'			=> 'a rong qi',
		],
		'152123'	=> [
			'zh-cn'			=> '莫力达瓦达斡尔族自治旗',
			'en'			=> 'mo li da wa da wo er zu zi zhi qi',
		],
		'152124'	=> [
			'zh-cn'			=> '布特哈旗',
			'en'			=> 'bu te ha qi',
		],
		'152125'	=> [
			'zh-cn'			=> '额尔古纳右旗',
			'en'			=> 'e er gu na you qi',
		],
		'152126'	=> [
			'zh-cn'			=> '额尔古纳左旗',
			'en'			=> 'e er gu na zuo qi',
		],
		'152127'	=> [
			'zh-cn'			=> '鄂伦春自治旗',
			'en'			=> 'e lun chun zi zhi qi',
		],
		'152128'	=> [
			'zh-cn'			=> '鄂温克族自治旗',
			'en'			=> 'e wen ke zu zi zhi qi',
		],
		'152129'	=> [
			'zh-cn'			=> '新巴尔虎右旗',
			'en'			=> 'xin ba er hu you qi',
		],
		'152130'	=> [
			'zh-cn'			=> '新巴尔虎左旗',
			'en'			=> 'xin ba er hu zuo qi',
		],
		'152131'	=> [
			'zh-cn'			=> '陈巴尔虎旗',
			'en'			=> 'chen ba er hu qi',
		],
		'152200'	=> [
			'zh-cn'			=> '兴安盟',
			'en'			=> 'xing an meng',
		],
		'152201'	=> [
			'zh-cn'			=> '乌兰浩特市',
			'en'			=> 'wu lan hao te shi',
		],
		'152202'	=> [
			'zh-cn'			=> '阿尔山市',
			'en'			=> 'a er shan shi',
		],
		'152221'	=> [
			'zh-cn'			=> '科尔沁右翼前旗',
			'en'			=> 'ke er qin you yi qian qi',
		],
		'152222'	=> [
			'zh-cn'			=> '科尔沁右翼中旗',
			'en'			=> 'ke er qin you yi zhong qi',
		],
		'152223'	=> [
			'zh-cn'			=> '扎赉特旗',
			'en'			=> 'zha lai te qi',
		],
		'152224'	=> [
			'zh-cn'			=> '突泉县',
			'en'			=> 'tu quan xian',
		],
		'152300'	=> [
			'zh-cn'			=> '哲里木盟',
			'en'			=> 'zhe li mu meng',
		],
		'152301'	=> [
			'zh-cn'			=> '通辽市',
			'en'			=> 'tong liao shi',
		],
		'152302'	=> [
			'zh-cn'			=> '霍林郭勒市',
			'en'			=> 'huo lin guo le shi',
		],
		'152321'	=> [
			'zh-cn'			=> '通辽县',
			'en'			=> 'tong liao xian',
		],
		'152322'	=> [
			'zh-cn'			=> '科尔沁左翼中旗',
			'en'			=> 'ke er qin zuo yi zhong qi',
		],
		'152323'	=> [
			'zh-cn'			=> '科尔沁左翼后旗',
			'en'			=> 'ke er qin zuo yi hou qi',
		],
		'152324'	=> [
			'zh-cn'			=> '开鲁县',
			'en'			=> 'kai lu xian',
		],
		'152325'	=> [
			'zh-cn'			=> '库伦旗',
			'en'			=> 'ku lun qi',
		],
		'152326'	=> [
			'zh-cn'			=> '奈曼旗',
			'en'			=> 'nai man qi',
		],
		'152327'	=> [
			'zh-cn'			=> '扎鲁特旗',
			'en'			=> 'za lu te qi',
		],
		'152400'	=> [
			'zh-cn'			=> '昭乌达盟',
			'en'			=> 'zhao wu da meng',
		],
		'152401'	=> [
			'zh-cn'			=> '赤峰市',
			'en'			=> 'chi feng shi',
		],
		'152421'	=> [
			'zh-cn'			=> '阿鲁科尔沁旗',
			'en'			=> 'a lu ke er qin qi',
		],
		'152422'	=> [
			'zh-cn'			=> '巴林左旗',
			'en'			=> 'ba lin zuo qi',
		],
		'152423'	=> [
			'zh-cn'			=> '巴林右旗',
			'en'			=> 'ba lin you qi',
		],
		'152424'	=> [
			'zh-cn'			=> '林西县',
			'en'			=> 'lin xi xian',
		],
		'152425'	=> [
			'zh-cn'			=> '克什克腾旗',
			'en'			=> 'ke shi ke teng qi',
		],
		'152426'	=> [
			'zh-cn'			=> '翁牛特旗',
			'en'			=> 'weng niu te qi',
		],
		'152427'	=> [
			'zh-cn'			=> '赤峰县',
			'en'			=> 'chi feng xian',
		],
		'152428'	=> [
			'zh-cn'			=> '喀喇沁旗',
			'en'			=> 'ka la qin qi',
		],
		'152429'	=> [
			'zh-cn'			=> '宁城县',
			'en'			=> 'ning cheng xian',
		],
		'152430'	=> [
			'zh-cn'			=> '敖汉旗',
			'en'			=> 'ao han qi',
		],
		'152500'	=> [
			'zh-cn'			=> '锡林郭勒盟',
			'en'			=> 'xi lin guo le meng',
		],
		'152501'	=> [
			'zh-cn'			=> '二连浩特市',
			'en'			=> 'er lian hao te shi',
		],
		'152502'	=> [
			'zh-cn'			=> '锡林浩特市',
			'en'			=> 'xi lin hao te shi',
		],
		'152521'	=> [
			'zh-cn'			=> '阿巴哈纳尔旗',
			'en'			=> 'a ba ha na er qi',
		],
		'152522'	=> [
			'zh-cn'			=> '阿巴嘎旗',
			'en'			=> 'a ba ga qi',
		],
		'152523'	=> [
			'zh-cn'			=> '苏尼特左旗',
			'en'			=> 'su ni te zuo qi',
		],
		'152524'	=> [
			'zh-cn'			=> '苏尼特右旗',
			'en'			=> 'su ni te you qi',
		],
		'152525'	=> [
			'zh-cn'			=> '东乌珠穆沁旗',
			'en'			=> 'dong wu zhu mu qin qi',
		],
		'152526'	=> [
			'zh-cn'			=> '西乌珠穆沁旗',
			'en'			=> 'xi wu zhu mu qin qi',
		],
		'152527'	=> [
			'zh-cn'			=> '太仆寺旗',
			'en'			=> 'tai pu si qi',
		],
		'152528'	=> [
			'zh-cn'			=> '镶黄旗',
			'en'			=> 'xiang huang qi',
		],
		'152529'	=> [
			'zh-cn'			=> '正镶白旗',
			'en'			=> 'zheng xiang bai qi',
		],
		'152530'	=> [
			'zh-cn'			=> '正蓝旗',
			'en'			=> 'zheng lan qi',
		],
		'152531'	=> [
			'zh-cn'			=> '多伦县',
			'en'			=> 'duo lun xian',
		],
		'152600'	=> [
			'zh-cn'			=> '乌兰察布盟',
			'en'			=> 'wu lan cha bu meng',
		],
		'152601'	=> [
			'zh-cn'			=> '集宁市',
			'en'			=> 'ji ning shi',
		],
		'152602'	=> [
			'zh-cn'			=> '丰镇市',
			'en'			=> 'feng zhen shi',
		],
		'152621'	=> [
			'zh-cn'			=> '武川县',
			'en'			=> 'wu chuan xian',
		],
		'152622'	=> [
			'zh-cn'			=> '和林格尔县',
			'en'			=> 'he lin ge er xian',
		],
		'152623'	=> [
			'zh-cn'			=> '清水河县',
			'en'			=> 'qing shui he xian',
		],
		'152624'	=> [
			'zh-cn'			=> '卓资县',
			'en'			=> 'zhuo zi xian',
		],
		'152625'	=> [
			'zh-cn'			=> '化德县',
			'en'			=> 'hua de xian',
		],
		'152626'	=> [
			'zh-cn'			=> '商都县',
			'en'			=> 'shang du xian',
		],
		'152627'	=> [
			'zh-cn'			=> '兴和县',
			'en'			=> 'xing he xian',
		],
		'152628'	=> [
			'zh-cn'			=> '丰镇县',
			'en'			=> 'feng zhen xian',
		],
		'152629'	=> [
			'zh-cn'			=> '凉城县',
			'en'			=> 'liang cheng xian',
		],
		'152630'	=> [
			'zh-cn'			=> '察哈尔右翼前旗',
			'en'			=> 'cha ha er you yi qian qi',
		],
		'152631'	=> [
			'zh-cn'			=> '察哈尔右翼中旗',
			'en'			=> 'cha ha er you yi zhong qi',
		],
		'152632'	=> [
			'zh-cn'			=> '察哈尔右翼后旗',
			'en'			=> 'cha ha er you yi hou qi',
		],
		'152633'	=> [
			'zh-cn'			=> '达尔罕茂明安联合旗',
			'en'			=> 'da er han mao ming an lian he qi',
		],
		'152634'	=> [
			'zh-cn'			=> '四子王旗',
			'en'			=> 'si zi wang qi',
		],
		'152700'	=> [
			'zh-cn'			=> '伊克昭盟',
			'en'			=> 'yi ke zhao meng',
		],
		'152701'	=> [
			'zh-cn'			=> '东胜市',
			'en'			=> 'dong sheng shi',
		],
		'152721'	=> [
			'zh-cn'			=> '东胜县',
			'en'			=> 'dong sheng xian',
		],
		'152722'	=> [
			'zh-cn'			=> '达拉特旗',
			'en'			=> 'da la te qi',
		],
		'152723'	=> [
			'zh-cn'			=> '准格尔旗',
			'en'			=> 'zhun ge er qi',
		],
		'152724'	=> [
			'zh-cn'			=> '鄂托克前旗',
			'en'			=> 'e tuo ke qian qi',
		],
		'152725'	=> [
			'zh-cn'			=> '鄂托克旗',
			'en'			=> 'e tuo ke qi',
		],
		'152726'	=> [
			'zh-cn'			=> '杭锦旗',
			'en'			=> 'hang jin qi',
		],
		'152727'	=> [
			'zh-cn'			=> '乌审旗',
			'en'			=> 'wu shen qi',
		],
		'152728'	=> [
			'zh-cn'			=> '伊金霍洛旗',
			'en'			=> 'yi jin huo luo qi',
		],
		'152800'	=> [
			'zh-cn'			=> '巴彦淖尔盟',
			'en'			=> 'ba yan nao er meng',
		],
		'152801'	=> [
			'zh-cn'			=> '临河市',
			'en'			=> 'lin he shi',
		],
		'152821'	=> [
			'zh-cn'			=> '临河县',
			'en'			=> 'lin he xian',
		],
		'152822'	=> [
			'zh-cn'			=> '五原县',
			'en'			=> 'wu yuan xian',
		],
		'152823'	=> [
			'zh-cn'			=> '磴口县',
			'en'			=> 'deng kou xian',
		],
		'152824'	=> [
			'zh-cn'			=> '乌拉特前旗',
			'en'			=> 'wu l te qian qi',
		],
		'152825'	=> [
			'zh-cn'			=> '乌拉特中旗',
			'en'			=> 'wu l te zhong qi',
		],
		'152826'	=> [
			'zh-cn'			=> '乌拉特后旗',
			'en'			=> 'wu l te hou qi',
		],
		'152827'	=> [
			'zh-cn'			=> '杭锦后旗',
			'en'			=> 'hang jin hou qi',
		],
		'152900'	=> [
			'zh-cn'			=> '阿拉善盟',
			'en'			=> 'a la shan meng',
		],
		'152921'	=> [
			'zh-cn'			=> '阿拉善左旗',
			'en'			=> 'a la shan zuo qi',
		],
		'152922'	=> [
			'zh-cn'			=> '阿拉善右旗',
			'en'			=> 'a la shan you qi',
		],
		'152923'	=> [
			'zh-cn'			=> '额济纳旗',
			'en'			=> 'e ji na qi',
		],
		'210000'	=> [
			'zh-cn'			=> '辽宁省',
			'en'			=> 'liao ning sheng',
		],
		'210100'	=> [
			'zh-cn'			=> '沈阳市',
			'en'			=> 'shen yang shi',
		],
		'210102'	=> [
			'zh-cn'			=> '和平区',
			'en'			=> 'he ping qu',
		],
		'210103'	=> [
			'zh-cn'			=> '沈河区',
			'en'			=> 'shen he qu',
		],
		'210104'	=> [
			'zh-cn'			=> '大东区',
			'en'			=> 'da dong qu',
		],
		'210105'	=> [
			'zh-cn'			=> '皇姑区',
			'en'			=> 'huang gu qu',
		],
		'210106'	=> [
			'zh-cn'			=> '铁西区',
			'en'			=> 'tie xi qu',
		],
		'210111'	=> [
			'zh-cn'			=> '苏家屯区',
			'en'			=> 'su jia tun qu',
		],
		'210112'	=> [
			'zh-cn'			=> '浑南区',
			'en'			=> 'hun nan qu',
		],
		'210113'	=> [
			'zh-cn'			=> '沈北新区',
			'en'			=> 'shen bei xin qu',
		],
		'210114'	=> [
			'zh-cn'			=> '于洪区',
			'en'			=> 'yu hong qu',
		],
		'210115'	=> [
			'zh-cn'			=> '辽中区',
			'en'			=> 'liao zhong qu',
		],
		'210121'	=> [
			'zh-cn'			=> '新民县',
			'en'			=> 'xin min xian',
		],
		'210122'	=> [
			'zh-cn'			=> '辽中县',
			'en'			=> 'liao zhong xian',
		],
		'210123'	=> [
			'zh-cn'			=> '康平县',
			'en'			=> 'kang ping xian',
		],
		'210124'	=> [
			'zh-cn'			=> '法库县',
			'en'			=> 'fa ku xian',
		],
		'210181'	=> [
			'zh-cn'			=> '新民市',
			'en'			=> 'xin min shi',
		],
		'210200'	=> [
			'zh-cn'			=> '大连市',
			'en'			=> 'da lian shi',
		],
		'210202'	=> [
			'zh-cn'			=> '中山区',
			'en'			=> 'zhong shan qu',
		],
		'210203'	=> [
			'zh-cn'			=> '西岗区',
			'en'			=> 'xi gang qu',
		],
		'210204'	=> [
			'zh-cn'			=> '沙河口区',
			'en'			=> 'sha he kou qu',
		],
		'210211'	=> [
			'zh-cn'			=> '甘井子区',
			'en'			=> 'gan jing zi qu',
		],
		'210212'	=> [
			'zh-cn'			=> '旅顺口区',
			'en'			=> 'lv shun kou qu',
		],
		'210213'	=> [
			'zh-cn'			=> '金州区',
			'en'			=> 'jin zhou qu',
		],
		'210214'	=> [
			'zh-cn'			=> '普兰店区',
			'en'			=> 'pu lan dian qu',
		],
		'210219'	=> [
			'zh-cn'			=> '瓦房店市',
			'en'			=> 'wa fang dian shi',
		],
		'210222'	=> [
			'zh-cn'			=> '新金县',
			'en'			=> 'xin jin xian',
		],
		'210224'	=> [
			'zh-cn'			=> '长海县',
			'en'			=> 'chang hai xian',
		],
		'210225'	=> [
			'zh-cn'			=> '庄河县',
			'en'			=> 'zhuang he xian',
		],
		'210281'	=> [
			'zh-cn'			=> '瓦房店市',
			'en'			=> 'wa fang dian shi',
		],
		'210282'	=> [
			'zh-cn'			=> '普兰店市',
			'en'			=> 'pu lan dian shi',
		],
		'210283'	=> [
			'zh-cn'			=> '庄河市',
			'en'			=> 'zhuang he shi',
		],
		'210300'	=> [
			'zh-cn'			=> '鞍山市',
			'en'			=> 'an shan shi',
		],
		'210302'	=> [
			'zh-cn'			=> '铁东区',
			'en'			=> 'tie dong qu',
		],
		'210303'	=> [
			'zh-cn'			=> '铁西区',
			'en'			=> 'tie xi qu',
		],
		'210304'	=> [
			'zh-cn'			=> '立山区',
			'en'			=> 'li shan qu',
		],
		'210311'	=> [
			'zh-cn'			=> '千山区',
			'en'			=> 'qian shan qu',
		],
		'210319'	=> [
			'zh-cn'			=> '海城市',
			'en'			=> 'hai cheng shi',
		],
		'210321'	=> [
			'zh-cn'			=> '台安县',
			'en'			=> 'tai an xian',
		],
		'210322'	=> [
			'zh-cn'			=> '海城县',
			'en'			=> 'hai cheng xian',
		],
		'210323'	=> [
			'zh-cn'			=> '岫岩满族自治县',
			'en'			=> 'xiu yan man zu zi zhi xian',
		],
		'210381'	=> [
			'zh-cn'			=> '海城市',
			'en'			=> 'hai cheng shi',
		],
		'210400'	=> [
			'zh-cn'			=> '抚顺市',
			'en'			=> 'fu shun shi',
		],
		'210402'	=> [
			'zh-cn'			=> '新抚区',
			'en'			=> 'xin fu qu',
		],
		'210403'	=> [
			'zh-cn'			=> '东洲区',
			'en'			=> 'dong zhou qu',
		],
		'210404'	=> [
			'zh-cn'			=> '望花区',
			'en'			=> 'wang hua qu',
		],
		'210411'	=> [
			'zh-cn'			=> '顺城区',
			'en'			=> 'shun cheng qu',
		],
		'210421'	=> [
			'zh-cn'			=> '抚顺县',
			'en'			=> 'fu shun xian',
		],
		'210422'	=> [
			'zh-cn'			=> '新宾满族自治县',
			'en'			=> 'xin bin man zu zi zhi xian',
		],
		'210423'	=> [
			'zh-cn'			=> '清原满族自治县',
			'en'			=> 'qing yuan man zu zi zhi xian',
		],
		'210500'	=> [
			'zh-cn'			=> '本溪市',
			'en'			=> 'ben xi shi',
		],
		'210502'	=> [
			'zh-cn'			=> '平山区',
			'en'			=> 'ping shan qu',
		],
		'210503'	=> [
			'zh-cn'			=> '溪湖区',
			'en'			=> 'xi hu qu',
		],
		'210504'	=> [
			'zh-cn'			=> '明山区',
			'en'			=> 'ming shan qu',
		],
		'210505'	=> [
			'zh-cn'			=> '南芬区',
			'en'			=> 'nan fen qu',
		],
		'210511'	=> [
			'zh-cn'			=> '南芬区',
			'en'			=> 'nan fen qu',
		],
		'210521'	=> [
			'zh-cn'			=> '本溪满族自治县',
			'en'			=> 'ben xi man zu zi zhi xian',
		],
		'210522'	=> [
			'zh-cn'			=> '桓仁满族自治县',
			'en'			=> 'huan ren man zu zi zhi xian',
		],
		'210600'	=> [
			'zh-cn'			=> '丹东市',
			'en'			=> 'dan dong shi',
		],
		'210602'	=> [
			'zh-cn'			=> '元宝区',
			'en'			=> 'yuan bao qu',
		],
		'210603'	=> [
			'zh-cn'			=> '振兴区',
			'en'			=> 'zhen xing qu',
		],
		'210604'	=> [
			'zh-cn'			=> '振安区',
			'en'			=> 'zhen an qu',
		],
		'210621'	=> [
			'zh-cn'			=> '凤城满族自治县',
			'en'			=> 'feng cheng man zu zi zhi xian',
		],
		'210622'	=> [
			'zh-cn'			=> '岫岩满族自治县',
			'en'			=> 'xiu yan man zu zi zhi xian',
		],
		'210623'	=> [
			'zh-cn'			=> '东沟县',
			'en'			=> 'dong gou xian',
		],
		'210624'	=> [
			'zh-cn'			=> '宽甸满族自治县',
			'en'			=> 'kuan dian man zu zi zhi xian',
		],
		'210681'	=> [
			'zh-cn'			=> '东港市',
			'en'			=> 'dong gang shi',
		],
		'210682'	=> [
			'zh-cn'			=> '凤城市',
			'en'			=> 'feng cheng shi',
		],
		'210700'	=> [
			'zh-cn'			=> '锦州市',
			'en'			=> 'jin zhou shi',
		],
		'210702'	=> [
			'zh-cn'			=> '古塔区',
			'en'			=> 'gu ta qu',
		],
		'210703'	=> [
			'zh-cn'			=> '凌河区',
			'en'			=> 'ling he qu',
		],
		'210704'	=> [
			'zh-cn'			=> '南票区',
			'en'			=> 'nan piao qu',
		],
		'210705'	=> [
			'zh-cn'			=> '葫芦岛区',
			'en'			=> 'hu lu dao qu',
		],
		'210706'	=> [
			'zh-cn'			=> '太和区',
			'en'			=> 'tai he qu',
		],
		'210711'	=> [
			'zh-cn'			=> '太和区',
			'en'			=> 'tai he qu',
		],
		'210719'	=> [
			'zh-cn'			=> '锦西市',
			'en'			=> 'jin xi shi',
		],
		'210721'	=> [
			'zh-cn'			=> '锦西县',
			'en'			=> 'jin xi xian',
		],
		'210722'	=> [
			'zh-cn'			=> '兴城县',
			'en'			=> 'xing cheng xian',
		],
		'210723'	=> [
			'zh-cn'			=> '绥中县',
			'en'			=> 'sui zhong xian',
		],
		'210725'	=> [
			'zh-cn'			=> '北镇满族自治县',
			'en'			=> 'bei zhen man zu zi zhi xian',
		],
		'210726'	=> [
			'zh-cn'			=> '黑山县',
			'en'			=> 'hei shan xian',
		],
		'210781'	=> [
			'zh-cn'			=> '凌海市',
			'en'			=> 'ling hai shi',
		],
		'210782'	=> [
			'zh-cn'			=> '北镇市',
			'en'			=> 'bei zhen shi',
		],
		'210800'	=> [
			'zh-cn'			=> '营口市',
			'en'			=> 'ying kou shi',
		],
		'210802'	=> [
			'zh-cn'			=> '站前区',
			'en'			=> 'zhan qian qu',
		],
		'210803'	=> [
			'zh-cn'			=> '西市区',
			'en'			=> 'xi shi qu',
		],
		'210804'	=> [
			'zh-cn'			=> '鲅鱼圈区',
			'en'			=> 'ba yu quan qu',
		],
		'210811'	=> [
			'zh-cn'			=> '老边区',
			'en'			=> 'lao bian qu',
		],
		'210821'	=> [
			'zh-cn'			=> '营口县',
			'en'			=> 'ying kou xian',
		],
		'210825'	=> [
			'zh-cn'			=> '盘山县',
			'en'			=> 'pan shan xian',
		],
		'210826'	=> [
			'zh-cn'			=> '大洼县',
			'en'			=> 'da wa xian',
		],
		'210881'	=> [
			'zh-cn'			=> '盖州市',
			'en'			=> 'gai zhou shi',
		],
		'210882'	=> [
			'zh-cn'			=> '大石桥市',
			'en'			=> 'da shi qiao shi',
		],
		'210900'	=> [
			'zh-cn'			=> '阜新市',
			'en'			=> 'fu xin shi',
		],
		'210902'	=> [
			'zh-cn'			=> '海州区',
			'en'			=> 'hai zhou qu',
		],
		'210903'	=> [
			'zh-cn'			=> '新邱区',
			'en'			=> 'xin qiu qu',
		],
		'210904'	=> [
			'zh-cn'			=> '太平区',
			'en'			=> 'tai ping qu',
		],
		'210905'	=> [
			'zh-cn'			=> '清河门区',
			'en'			=> 'qing he men qu',
		],
		'210911'	=> [
			'zh-cn'			=> '细河区',
			'en'			=> 'xi he qu',
		],
		'210921'	=> [
			'zh-cn'			=> '阜新蒙古族自治县',
			'en'			=> 'fu xin meng gu zu zi zhi xian',
		],
		'210922'	=> [
			'zh-cn'			=> '彰武县',
			'en'			=> 'zhang wu xian',
		],
		'211000'	=> [
			'zh-cn'			=> '辽阳市',
			'en'			=> 'liao yang shi',
		],
		'211002'	=> [
			'zh-cn'			=> '白塔区',
			'en'			=> 'bai ta qu',
		],
		'211003'	=> [
			'zh-cn'			=> '文圣区',
			'en'			=> 'wen sheng qu',
		],
		'211004'	=> [
			'zh-cn'			=> '宏伟区',
			'en'			=> 'hong wei qu',
		],
		'211005'	=> [
			'zh-cn'			=> '弓长岭区',
			'en'			=> 'gong chang ling qu',
		],
		'211011'	=> [
			'zh-cn'			=> '太子河区',
			'en'			=> 'tai zi he qu',
		],
		'211021'	=> [
			'zh-cn'			=> '辽阳县',
			'en'			=> 'liao yang xian',
		],
		'211022'	=> [
			'zh-cn'			=> '灯塔县',
			'en'			=> 'deng ta xian',
		],
		'211081'	=> [
			'zh-cn'			=> '灯塔市',
			'en'			=> 'deng ta shi',
		],
		'211100'	=> [
			'zh-cn'			=> '盘锦市',
			'en'			=> 'pan jin shi',
		],
		'211102'	=> [
			'zh-cn'			=> '双台子区',
			'en'			=> 'shuang tai zi qu',
		],
		'211103'	=> [
			'zh-cn'			=> '兴隆台区',
			'en'			=> 'xing long tai qu',
		],
		'211104'	=> [
			'zh-cn'			=> '大洼区',
			'en'			=> 'da wa qu',
		],
		'211121'	=> [
			'zh-cn'			=> '大洼县',
			'en'			=> 'da wa xian',
		],
		'211122'	=> [
			'zh-cn'			=> '盘山县',
			'en'			=> 'pan shan xian',
		],
		'211200'	=> [
			'zh-cn'			=> '铁岭市',
			'en'			=> 'tie ling shi',
		],
		'211202'	=> [
			'zh-cn'			=> '银州区',
			'en'			=> 'yin zhou qu',
		],
		'211203'	=> [
			'zh-cn'			=> '铁法区',
			'en'			=> 'tie fa qu',
		],
		'211204'	=> [
			'zh-cn'			=> '清河区',
			'en'			=> 'qing he qu',
		],
		'211221'	=> [
			'zh-cn'			=> '铁岭县',
			'en'			=> 'tie ling xian',
		],
		'211222'	=> [
			'zh-cn'			=> '开原县',
			'en'			=> 'kai yuan xian',
		],
		'211223'	=> [
			'zh-cn'			=> '西丰县',
			'en'			=> 'xi feng xian',
		],
		'211224'	=> [
			'zh-cn'			=> '昌图县',
			'en'			=> 'chang tu xian',
		],
		'211225'	=> [
			'zh-cn'			=> '康平县',
			'en'			=> 'kang ping xian',
		],
		'211226'	=> [
			'zh-cn'			=> '法库县',
			'en'			=> 'fa ku xian',
		],
		'211281'	=> [
			'zh-cn'			=> '调兵山市',
			'en'			=> 'diao bing shan shi',
		],
		'211282'	=> [
			'zh-cn'			=> '开原市',
			'en'			=> 'kai yuan shi',
		],
		'211300'	=> [
			'zh-cn'			=> '朝阳市',
			'en'			=> 'zhao yang shi',
		],
		'211302'	=> [
			'zh-cn'			=> '双塔区',
			'en'			=> 'shuang ta qu',
		],
		'211303'	=> [
			'zh-cn'			=> '龙城区',
			'en'			=> 'long cheng qu',
		],
		'211319'	=> [
			'zh-cn'			=> '北票市',
			'en'			=> 'bei piao shi',
		],
		'211321'	=> [
			'zh-cn'			=> '朝阳县',
			'en'			=> 'zhao yang xian',
		],
		'211322'	=> [
			'zh-cn'			=> '建平县',
			'en'			=> 'jian ping xian',
		],
		'211323'	=> [
			'zh-cn'			=> '凌源县',
			'en'			=> 'ling yuan xian',
		],
		'211324'	=> [
			'zh-cn'			=> '喀喇沁左翼蒙古族自治县',
			'en'			=> 'ka la qin zuo yi meng gu zu zi zhi xian',
		],
		'211325'	=> [
			'zh-cn'			=> '建昌县',
			'en'			=> 'jian chang xian',
		],
		'211326'	=> [
			'zh-cn'			=> '北票县',
			'en'			=> 'bei piao xian',
		],
		'211381'	=> [
			'zh-cn'			=> '北票市',
			'en'			=> 'bei piao shi',
		],
		'211382'	=> [
			'zh-cn'			=> '凌源市',
			'en'			=> 'ling yuan shi',
		],
		'211400'	=> [
			'zh-cn'			=> '葫芦岛市',
			'en'			=> 'hu lu dao shi',
		],
		'211402'	=> [
			'zh-cn'			=> '连山区',
			'en'			=> 'lian shan qu',
		],
		'211403'	=> [
			'zh-cn'			=> '龙港区',
			'en'			=> 'long gang qu',
		],
		'211404'	=> [
			'zh-cn'			=> '南票区',
			'en'			=> 'nan piao qu',
		],
		'211405'	=> [
			'zh-cn'			=> '葫芦岛区',
			'en'			=> 'hu lu dao qu',
		],
		'211421'	=> [
			'zh-cn'			=> '绥中县',
			'en'			=> 'sui zhong xian',
		],
		'211422'	=> [
			'zh-cn'			=> '建昌县',
			'en'			=> 'jian chang xian',
		],
		'211481'	=> [
			'zh-cn'			=> '兴城市',
			'en'			=> 'xing cheng shi',
		],
		'212100'	=> [
			'zh-cn'			=> '铁岭地区',
			'en'			=> 'tie ling di qu',
		],
		'212101'	=> [
			'zh-cn'			=> '铁岭市',
			'en'			=> 'tie ling shi',
		],
		'212102'	=> [
			'zh-cn'			=> '铁法市',
			'en'			=> 'tie fa shi',
		],
		'212121'	=> [
			'zh-cn'			=> '铁岭县',
			'en'			=> 'tie ling xian',
		],
		'212122'	=> [
			'zh-cn'			=> '开原县',
			'en'			=> 'kai yuan xian',
		],
		'212123'	=> [
			'zh-cn'			=> '西丰县',
			'en'			=> 'xi feng xian',
		],
		'212124'	=> [
			'zh-cn'			=> '昌图县',
			'en'			=> 'chang tu xian',
		],
		'212125'	=> [
			'zh-cn'			=> '康平县',
			'en'			=> 'kang ping xian',
		],
		'212126'	=> [
			'zh-cn'			=> '法库县',
			'en'			=> 'fa ku xian',
		],
		'212200'	=> [
			'zh-cn'			=> '朝阳地区',
			'en'			=> 'zhao yang di qu',
		],
		'212201'	=> [
			'zh-cn'			=> '朝阳市',
			'en'			=> 'zhao yang shi',
		],
		'212221'	=> [
			'zh-cn'			=> '朝阳县',
			'en'			=> 'zhao yang xian',
		],
		'212222'	=> [
			'zh-cn'			=> '建平县',
			'en'			=> 'jian ping xian',
		],
		'212223'	=> [
			'zh-cn'			=> '凌源县',
			'en'			=> 'ling yuan xian',
		],
		'212224'	=> [
			'zh-cn'			=> '喀喇沁左翼蒙古族自治县',
			'en'			=> 'ka la qin zuo yi meng gu zu zi zhi xian',
		],
		'212225'	=> [
			'zh-cn'			=> '建昌县',
			'en'			=> 'jian chang xian',
		],
		'212226'	=> [
			'zh-cn'			=> '北票县',
			'en'			=> 'bei piao xian',
		],
		'219001'	=> [
			'zh-cn'			=> '瓦房店市',
			'en'			=> 'wa fang dian shi',
		],
		'219002'	=> [
			'zh-cn'			=> '海城市',
			'en'			=> 'hai cheng shi',
		],
		'219003'	=> [
			'zh-cn'			=> '锦西市',
			'en'			=> 'jin xi shi',
		],
		'219004'	=> [
			'zh-cn'			=> '兴城市',
			'en'			=> 'xing cheng shi',
		],
		'219005'	=> [
			'zh-cn'			=> '铁法市',
			'en'			=> 'tie fa shi',
		],
		'219006'	=> [
			'zh-cn'			=> '北票市',
			'en'			=> 'bei piao shi',
		],
		'219007'	=> [
			'zh-cn'			=> '开原市',
			'en'			=> 'kai yuan shi',
		],
		'219008'	=> [
			'zh-cn'			=> '普兰店市',
			'en'			=> 'pu lan dian shi',
		],
		'219009'	=> [
			'zh-cn'			=> '凌源市',
			'en'			=> 'ling yuan shi',
		],
		'219010'	=> [
			'zh-cn'			=> '庄河市',
			'en'			=> 'zhuang he shi',
		],
		'219011'	=> [
			'zh-cn'			=> '大石桥市',
			'en'			=> 'da shi qiao shi',
		],
		'219012'	=> [
			'zh-cn'			=> '盖州市',
			'en'			=> 'gai zhou shi',
		],
		'219013'	=> [
			'zh-cn'			=> '新民市',
			'en'			=> 'xin min shi',
		],
		'219014'	=> [
			'zh-cn'			=> '东港市',
			'en'			=> 'dong gang shi',
		],
		'219015'	=> [
			'zh-cn'			=> '凌海市',
			'en'			=> 'ling hai shi',
		],
		'219016'	=> [
			'zh-cn'			=> '凤城市',
			'en'			=> 'feng cheng shi',
		],
		'220000'	=> [
			'zh-cn'			=> '吉林省',
			'en'			=> 'ji lin sheng',
		],
		'220100'	=> [
			'zh-cn'			=> '长春市',
			'en'			=> 'chang chun shi',
		],
		'220102'	=> [
			'zh-cn'			=> '南关区',
			'en'			=> 'nan guan qu',
		],
		'220103'	=> [
			'zh-cn'			=> '宽城区',
			'en'			=> 'kuan cheng qu',
		],
		'220104'	=> [
			'zh-cn'			=> '朝阳区',
			'en'			=> 'zhao yang qu',
		],
		'220105'	=> [
			'zh-cn'			=> '二道区',
			'en'			=> 'er dao qu',
		],
		'220106'	=> [
			'zh-cn'			=> '绿园区',
			'en'			=> 'lv yuan qu',
		],
		'220112'	=> [
			'zh-cn'			=> '双阳区',
			'en'			=> 'shuang yang qu',
		],
		'220113'	=> [
			'zh-cn'			=> '九台区',
			'en'			=> 'jiu tai qu',
		],
		'220121'	=> [
			'zh-cn'			=> '榆树县',
			'en'			=> 'yu shu xian',
		],
		'220122'	=> [
			'zh-cn'			=> '农安县',
			'en'			=> 'nong an xian',
		],
		'220123'	=> [
			'zh-cn'			=> '九台县',
			'en'			=> 'jiu tai xian',
		],
		'220124'	=> [
			'zh-cn'			=> '德惠县',
			'en'			=> 'de hui xian',
		],
		'220125'	=> [
			'zh-cn'			=> '双阳县',
			'en'			=> 'shuang yang xian',
		],
		'220181'	=> [
			'zh-cn'			=> '九台市',
			'en'			=> 'jiu tai shi',
		],
		'220182'	=> [
			'zh-cn'			=> '榆树市',
			'en'			=> 'yu shu shi',
		],
		'220183'	=> [
			'zh-cn'			=> '德惠市',
			'en'			=> 'de hui shi',
		],
		'220200'	=> [
			'zh-cn'			=> '吉林市',
			'en'			=> 'ji lin shi',
		],
		'220202'	=> [
			'zh-cn'			=> '昌邑区',
			'en'			=> 'chang yi qu',
		],
		'220203'	=> [
			'zh-cn'			=> '龙潭区',
			'en'			=> 'long tan qu',
		],
		'220204'	=> [
			'zh-cn'			=> '船营区',
			'en'			=> 'chuan ying qu',
		],
		'220211'	=> [
			'zh-cn'			=> '丰满区',
			'en'			=> 'feng man qu',
		],
		'220221'	=> [
			'zh-cn'			=> '永吉县',
			'en'			=> 'yong ji xian',
		],
		'220222'	=> [
			'zh-cn'			=> '舒兰县',
			'en'			=> 'shu lan xian',
		],
		'220223'	=> [
			'zh-cn'			=> '磐石县',
			'en'			=> 'pan shi xian',
		],
		'220224'	=> [
			'zh-cn'			=> '蛟河县',
			'en'			=> 'jiao he xian',
		],
		'220225'	=> [
			'zh-cn'			=> '桦甸县',
			'en'			=> 'hua dian xian',
		],
		'220281'	=> [
			'zh-cn'			=> '蛟河市',
			'en'			=> 'jiao he shi',
		],
		'220282'	=> [
			'zh-cn'			=> '桦甸市',
			'en'			=> 'hua dian shi',
		],
		'220283'	=> [
			'zh-cn'			=> '舒兰市',
			'en'			=> 'shu lan shi',
		],
		'220284'	=> [
			'zh-cn'			=> '磐石市',
			'en'			=> 'pan shi shi',
		],
		'220300'	=> [
			'zh-cn'			=> '四平市',
			'en'			=> 'si ping shi',
		],
		'220302'	=> [
			'zh-cn'			=> '铁西区',
			'en'			=> 'tie xi qu',
		],
		'220303'	=> [
			'zh-cn'			=> '铁东区',
			'en'			=> 'tie dong qu',
		],
		'220319'	=> [
			'zh-cn'			=> '公主岭市',
			'en'			=> 'gong zhu ling shi',
		],
		'220321'	=> [
			'zh-cn'			=> '怀德县',
			'en'			=> 'huai de xian',
		],
		'220322'	=> [
			'zh-cn'			=> '梨树县',
			'en'			=> 'li shu xian',
		],
		'220323'	=> [
			'zh-cn'			=> '伊通满族自治县',
			'en'			=> 'yi tong man zu zi zhi xian',
		],
		'220324'	=> [
			'zh-cn'			=> '双辽县',
			'en'			=> 'shuang liao xian',
		],
		'220381'	=> [
			'zh-cn'			=> '公主岭市',
			'en'			=> 'gong zhu ling shi',
		],
		'220382'	=> [
			'zh-cn'			=> '双辽市',
			'en'			=> 'shuang liao shi',
		],
		'220400'	=> [
			'zh-cn'			=> '辽源市',
			'en'			=> 'liao yuan shi',
		],
		'220402'	=> [
			'zh-cn'			=> '龙山区',
			'en'			=> 'long shan qu',
		],
		'220403'	=> [
			'zh-cn'			=> '西安区',
			'en'			=> 'xi an qu',
		],
		'220421'	=> [
			'zh-cn'			=> '东丰县',
			'en'			=> 'dong feng xian',
		],
		'220422'	=> [
			'zh-cn'			=> '东辽县',
			'en'			=> 'dong liao xian',
		],
		'220500'	=> [
			'zh-cn'			=> '通化市',
			'en'			=> 'tong hua shi',
		],
		'220502'	=> [
			'zh-cn'			=> '东昌区',
			'en'			=> 'dong chang qu',
		],
		'220503'	=> [
			'zh-cn'			=> '二道江区',
			'en'			=> 'er dao jiang qu',
		],
		'220519'	=> [
			'zh-cn'			=> '梅河口市',
			'en'			=> 'mei he kou shi',
		],
		'220521'	=> [
			'zh-cn'			=> '通化县',
			'en'			=> 'tong hua xian',
		],
		'220522'	=> [
			'zh-cn'			=> '集安县',
			'en'			=> 'ji an xian',
		],
		'220523'	=> [
			'zh-cn'			=> '辉南县',
			'en'			=> 'hui nan xian',
		],
		'220524'	=> [
			'zh-cn'			=> '柳河县',
			'en'			=> 'liu he xian',
		],
		'220581'	=> [
			'zh-cn'			=> '梅河口市',
			'en'			=> 'mei he kou shi',
		],
		'220582'	=> [
			'zh-cn'			=> '集安市',
			'en'			=> 'ji an shi',
		],
		'220600'	=> [
			'zh-cn'			=> '白山市',
			'en'			=> 'bai shan shi',
		],
		'220602'	=> [
			'zh-cn'			=> '浑江区',
			'en'			=> 'hun jiang qu',
		],
		'220603'	=> [
			'zh-cn'			=> '三岔子区',
			'en'			=> 'san cha zi qu',
		],
		'220604'	=> [
			'zh-cn'			=> '临江区',
			'en'			=> 'lin jiang qu',
		],
		'220605'	=> [
			'zh-cn'			=> '江源区',
			'en'			=> 'jiang yuan qu',
		],
		'220621'	=> [
			'zh-cn'			=> '抚松县',
			'en'			=> 'fu song xian',
		],
		'220622'	=> [
			'zh-cn'			=> '靖宇县',
			'en'			=> 'jing yu xian',
		],
		'220623'	=> [
			'zh-cn'			=> '长白朝鲜族自治县',
			'en'			=> 'chang bai chao xian zu zi zhi xian',
		],
		'220625'	=> [
			'zh-cn'			=> '江源县',
			'en'			=> 'jiang yuan xian',
		],
		'220681'	=> [
			'zh-cn'			=> '临江市',
			'en'			=> 'lin jiang shi',
		],
		'220700'	=> [
			'zh-cn'			=> '松原市',
			'en'			=> 'song yuan shi',
		],
		'220702'	=> [
			'zh-cn'			=> '宁江区',
			'en'			=> 'ning jiang qu',
		],
		'220721'	=> [
			'zh-cn'			=> '前郭尔罗斯蒙古族自治县',
			'en'			=> 'qian guo er luo si meng gu zu zi zhi xian',
		],
		'220722'	=> [
			'zh-cn'			=> '长岭县',
			'en'			=> 'chang ling xian',
		],
		'220723'	=> [
			'zh-cn'			=> '乾安县',
			'en'			=> 'qian an xian',
		],
		'220724'	=> [
			'zh-cn'			=> '扶余县',
			'en'			=> 'fu yu xian',
		],
		'220781'	=> [
			'zh-cn'			=> '扶余市',
			'en'			=> 'fu yu shi',
		],
		'220800'	=> [
			'zh-cn'			=> '白城市',
			'en'			=> 'bai cheng shi',
		],
		'220802'	=> [
			'zh-cn'			=> '洮北区',
			'en'			=> 'tao bei qu',
		],
		'220821'	=> [
			'zh-cn'			=> '镇赉县',
			'en'			=> 'zhen lai xian',
		],
		'220822'	=> [
			'zh-cn'			=> '通榆县',
			'en'			=> 'tong yu xian',
		],
		'220881'	=> [
			'zh-cn'			=> '洮南市',
			'en'			=> 'tao nan shi',
		],
		'220882'	=> [
			'zh-cn'			=> '大安市',
			'en'			=> 'da an shi',
		],
		'222100'	=> [
			'zh-cn'			=> '德惠地区',
			'en'			=> 'de hui di qu',
		],
		'222121'	=> [
			'zh-cn'			=> '榆树县',
			'en'			=> 'yu shu xian',
		],
		'222122'	=> [
			'zh-cn'			=> '农安县',
			'en'			=> 'nong an xian',
		],
		'222123'	=> [
			'zh-cn'			=> '九台县',
			'en'			=> 'jiu tai xian',
		],
		'222124'	=> [
			'zh-cn'			=> '德惠县',
			'en'			=> 'de hui xian',
		],
		'222125'	=> [
			'zh-cn'			=> '双阳县',
			'en'			=> 'shuang yang xian',
		],
		'222200'	=> [
			'zh-cn'			=> '通化地区',
			'en'			=> 'tong hua di qu',
		],
		'222201'	=> [
			'zh-cn'			=> '通化市',
			'en'			=> 'tong hua shi',
		],
		'222202'	=> [
			'zh-cn'			=> '浑江市',
			'en'			=> 'hun jiang shi',
		],
		'222221'	=> [
			'zh-cn'			=> '海龙县',
			'en'			=> 'hai long xian',
		],
		'222222'	=> [
			'zh-cn'			=> '通化县',
			'en'			=> 'tong hua xian',
		],
		'222223'	=> [
			'zh-cn'			=> '柳河县',
			'en'			=> 'liu he xian',
		],
		'222224'	=> [
			'zh-cn'			=> '辉南县',
			'en'			=> 'hui nan xian',
		],
		'222225'	=> [
			'zh-cn'			=> '集安县',
			'en'			=> 'ji an xian',
		],
		'222226'	=> [
			'zh-cn'			=> '抚松县',
			'en'			=> 'fu song xian',
		],
		'222227'	=> [
			'zh-cn'			=> '靖宇县',
			'en'			=> 'jing yu xian',
		],
		'222228'	=> [
			'zh-cn'			=> '长白朝鲜族自治县',
			'en'			=> 'chang bai chao xian zu zi zhi xian',
		],
		'222300'	=> [
			'zh-cn'			=> '白城地区',
			'en'			=> 'bai cheng di qu',
		],
		'222301'	=> [
			'zh-cn'			=> '白城市',
			'en'			=> 'bai cheng shi',
		],
		'222302'	=> [
			'zh-cn'			=> '洮南市',
			'en'			=> 'tao nan shi',
		],
		'222303'	=> [
			'zh-cn'			=> '扶余市',
			'en'			=> 'fu yu shi',
		],
		'222304'	=> [
			'zh-cn'			=> '大安市',
			'en'			=> 'da an shi',
		],
		'222321'	=> [
			'zh-cn'			=> '扶余县',
			'en'			=> 'fu yu xian',
		],
		'222322'	=> [
			'zh-cn'			=> '洮安县',
			'en'			=> 'tao an xian',
		],
		'222323'	=> [
			'zh-cn'			=> '长岭县',
			'en'			=> 'chang ling xian',
		],
		'222324'	=> [
			'zh-cn'			=> '前郭尔罗斯蒙古族自治县',
			'en'			=> 'qian guo er luo si meng gu zu zi zhi xian',
		],
		'222325'	=> [
			'zh-cn'			=> '大安县',
			'en'			=> 'da an xian',
		],
		'222326'	=> [
			'zh-cn'			=> '镇赉县',
			'en'			=> 'zhen lai xian',
		],
		'222327'	=> [
			'zh-cn'			=> '通榆县',
			'en'			=> 'tong yu xian',
		],
		'222328'	=> [
			'zh-cn'			=> '乾安县',
			'en'			=> 'qian an xian',
		],
		'222400'	=> [
			'zh-cn'			=> '延边朝鲜族自治州',
			'en'			=> 'yan bian chao xian zu zi zhi zhou',
		],
		'222401'	=> [
			'zh-cn'			=> '延吉市',
			'en'			=> 'yan ji shi',
		],
		'222402'	=> [
			'zh-cn'			=> '图们市',
			'en'			=> 'tu men shi',
		],
		'222403'	=> [
			'zh-cn'			=> '敦化市',
			'en'			=> 'dun hua shi',
		],
		'222404'	=> [
			'zh-cn'			=> '珲春市',
			'en'			=> 'hun chun shi',
		],
		'222405'	=> [
			'zh-cn'			=> '龙井市',
			'en'			=> 'long jing shi',
		],
		'222406'	=> [
			'zh-cn'			=> '和龙市',
			'en'			=> 'he long shi',
		],
		'222421'	=> [
			'zh-cn'			=> '龙井县',
			'en'			=> 'long jing xian',
		],
		'222422'	=> [
			'zh-cn'			=> '敦化县',
			'en'			=> 'dun hua xian',
		],
		'222423'	=> [
			'zh-cn'			=> '和龙县',
			'en'			=> 'he long xian',
		],
		'222424'	=> [
			'zh-cn'			=> '汪清县',
			'en'			=> 'wang qing xian',
		],
		'222425'	=> [
			'zh-cn'			=> '珲春县',
			'en'			=> 'hui chun xian',
		],
		'222426'	=> [
			'zh-cn'			=> '安图县',
			'en'			=> 'an tu xian',
		],
		'222500'	=> [
			'zh-cn'			=> '永吉地区',
			'en'			=> 'yong ji di qu',
		],
		'222521'	=> [
			'zh-cn'			=> '永吉县',
			'en'			=> 'yong ji xian',
		],
		'222522'	=> [
			'zh-cn'			=> '舒兰县',
			'en'			=> 'shu lan xian',
		],
		'222523'	=> [
			'zh-cn'			=> '磐石县',
			'en'			=> 'pan shi xian',
		],
		'222524'	=> [
			'zh-cn'			=> '蛟河县',
			'en'			=> 'jiao he xian',
		],
		'222525'	=> [
			'zh-cn'			=> '桦甸县',
			'en'			=> 'hua dian xian',
		],
		'222600'	=> [
			'zh-cn'			=> '四平地区',
			'en'			=> 'si ping di qu',
		],
		'222601'	=> [
			'zh-cn'			=> '四平市',
			'en'			=> 'si ping shi',
		],
		'222602'	=> [
			'zh-cn'			=> '辽源市',
			'en'			=> 'liao yuan shi',
		],
		'222621'	=> [
			'zh-cn'			=> '怀德县',
			'en'			=> 'huai de xian',
		],
		'222622'	=> [
			'zh-cn'			=> '梨树县',
			'en'			=> 'li shu xian',
		],
		'222623'	=> [
			'zh-cn'			=> '伊通县',
			'en'			=> 'yi tong xian',
		],
		'222624'	=> [
			'zh-cn'			=> '双辽县',
			'en'			=> 'shuang liao xian',
		],
		'222625'	=> [
			'zh-cn'			=> '东丰县',
			'en'			=> 'dong feng xian',
		],
		'229001'	=> [
			'zh-cn'			=> '公主岭市',
			'en'			=> 'gong zhu ling shi',
		],
		'229002'	=> [
			'zh-cn'			=> '梅河口市',
			'en'			=> 'mei he kou shi',
		],
		'229003'	=> [
			'zh-cn'			=> '集安市',
			'en'			=> 'ji an shi',
		],
		'229004'	=> [
			'zh-cn'			=> '桦甸市',
			'en'			=> 'hua dian shi',
		],
		'229005'	=> [
			'zh-cn'			=> '九台市',
			'en'			=> 'jiu tai shi',
		],
		'229006'	=> [
			'zh-cn'			=> '蛟河市',
			'en'			=> 'jiao he shi',
		],
		'229007'	=> [
			'zh-cn'			=> '榆树市',
			'en'			=> 'yu shu shi',
		],
		'229008'	=> [
			'zh-cn'			=> '舒兰市',
			'en'			=> 'shu lan shi',
		],
		'229009'	=> [
			'zh-cn'			=> '大安市',
			'en'			=> 'da an shi',
		],
		'229010'	=> [
			'zh-cn'			=> '洮南市',
			'en'			=> 'tao nan shi',
		],
		'229011'	=> [
			'zh-cn'			=> '临江市',
			'en'			=> 'lin jiang shi',
		],
		'229012'	=> [
			'zh-cn'			=> '德惠市',
			'en'			=> 'de hui shi',
		],
		'230000'	=> [
			'zh-cn'			=> '黑龙江省',
			'en'			=> 'hei long jiang sheng',
		],
		'230100'	=> [
			'zh-cn'			=> '哈尔滨市',
			'en'			=> 'ha er bin shi',
		],
		'230102'	=> [
			'zh-cn'			=> '道里区',
			'en'			=> 'dao li qu',
		],
		'230103'	=> [
			'zh-cn'			=> '南岗区',
			'en'			=> 'nan gang qu',
		],
		'230104'	=> [
			'zh-cn'			=> '道外区',
			'en'			=> 'dao wai qu',
		],
		'230105'	=> [
			'zh-cn'			=> '太平区',
			'en'			=> 'tai ping qu',
		],
		'230106'	=> [
			'zh-cn'			=> '香坊区',
			'en'			=> 'xiang fang qu',
		],
		'230107'	=> [
			'zh-cn'			=> '动力区',
			'en'			=> 'dong li qu',
		],
		'230108'	=> [
			'zh-cn'			=> '平房区',
			'en'			=> 'ping fang qu',
		],
		'230109'	=> [
			'zh-cn'			=> '松北区',
			'en'			=> 'song bei qu',
		],
		'230110'	=> [
			'zh-cn'			=> '香坊区',
			'en'			=> 'xiang fang qu',
		],
		'230111'	=> [
			'zh-cn'			=> '呼兰区',
			'en'			=> 'hu lan qu',
		],
		'230112'	=> [
			'zh-cn'			=> '阿城区',
			'en'			=> 'a cheng qu',
		],
		'230113'	=> [
			'zh-cn'			=> '双城区',
			'en'			=> 'shuang cheng qu',
		],
		'230121'	=> [
			'zh-cn'			=> '呼兰县',
			'en'			=> 'hu lan xian',
		],
		'230122'	=> [
			'zh-cn'			=> '阿城县',
			'en'			=> 'a cheng xian',
		],
		'230123'	=> [
			'zh-cn'			=> '依兰县',
			'en'			=> 'yi lan xian',
		],
		'230124'	=> [
			'zh-cn'			=> '方正县',
			'en'			=> 'fang zheng xian',
		],
		'230125'	=> [
			'zh-cn'			=> '巴彦县',
			'en'			=> 'ba yan xian',
		],
		'230126'	=> [
			'zh-cn'			=> '巴彦县',
			'en'			=> 'ba yan xian',
		],
		'230127'	=> [
			'zh-cn'			=> '木兰县',
			'en'			=> 'mu lan xian',
		],
		'230128'	=> [
			'zh-cn'			=> '通河县',
			'en'			=> 'tong he xian',
		],
		'230129'	=> [
			'zh-cn'			=> '延寿县',
			'en'			=> 'yan shou xian',
		],
		'230181'	=> [
			'zh-cn'			=> '阿城市',
			'en'			=> 'a cheng shi',
		],
		'230182'	=> [
			'zh-cn'			=> '双城市',
			'en'			=> 'shuang cheng shi',
		],
		'230183'	=> [
			'zh-cn'			=> '尚志市',
			'en'			=> 'shang zhi shi',
		],
		'230184'	=> [
			'zh-cn'			=> '五常市',
			'en'			=> 'wu chang shi',
		],
		'230200'	=> [
			'zh-cn'			=> '齐齐哈尔市',
			'en'			=> 'qi qi ha er shi',
		],
		'230202'	=> [
			'zh-cn'			=> '龙沙区',
			'en'			=> 'long sha qu',
		],
		'230203'	=> [
			'zh-cn'			=> '建华区',
			'en'			=> 'jian hua qu',
		],
		'230204'	=> [
			'zh-cn'			=> '铁锋区',
			'en'			=> 'tie feng qu',
		],
		'230205'	=> [
			'zh-cn'			=> '昂昂溪区',
			'en'			=> 'ang ang xi qu',
		],
		'230206'	=> [
			'zh-cn'			=> '富拉尔基区',
			'en'			=> 'fu la er ji qu',
		],
		'230207'	=> [
			'zh-cn'			=> '碾子山区',
			'en'			=> 'nian zi shan qu',
		],
		'230208'	=> [
			'zh-cn'			=> '梅里斯达斡尔族区',
			'en'			=> 'mei li si da wo er zu qu',
		],
		'230221'	=> [
			'zh-cn'			=> '龙江县',
			'en'			=> 'long jiang xian',
		],
		'230222'	=> [
			'zh-cn'			=> '讷河县',
			'en'			=> 'ne he xian',
		],
		'230223'	=> [
			'zh-cn'			=> '依安县',
			'en'			=> 'yi an xian',
		],
		'230224'	=> [
			'zh-cn'			=> '泰来县',
			'en'			=> 'tai lai xian',
		],
		'230225'	=> [
			'zh-cn'			=> '甘南县',
			'en'			=> 'gan nan xian',
		],
		'230226'	=> [
			'zh-cn'			=> '杜尔伯特蒙古族自治县',
			'en'			=> 'du er bo te meng gu zu zi zhi xian',
		],
		'230227'	=> [
			'zh-cn'			=> '富裕县',
			'en'			=> 'fu yu xian',
		],
		'230228'	=> [
			'zh-cn'			=> '林甸县',
			'en'			=> 'lin dian xian',
		],
		'230229'	=> [
			'zh-cn'			=> '克山县',
			'en'			=> 'ke shan xian',
		],
		'230230'	=> [
			'zh-cn'			=> '克东县',
			'en'			=> 'ke dong xian',
		],
		'230231'	=> [
			'zh-cn'			=> '拜泉县',
			'en'			=> 'bai quan xian',
		],
		'230281'	=> [
			'zh-cn'			=> '讷河市',
			'en'			=> 'ne he shi',
		],
		'230300'	=> [
			'zh-cn'			=> '鸡西市',
			'en'			=> 'ji xi shi',
		],
		'230302'	=> [
			'zh-cn'			=> '鸡冠区',
			'en'			=> 'ji guan qu',
		],
		'230303'	=> [
			'zh-cn'			=> '恒山区',
			'en'			=> 'heng shan qu',
		],
		'230304'	=> [
			'zh-cn'			=> '滴道区',
			'en'			=> 'di dao qu',
		],
		'230305'	=> [
			'zh-cn'			=> '梨树区',
			'en'			=> 'li shu qu',
		],
		'230306'	=> [
			'zh-cn'			=> '城子河区',
			'en'			=> 'cheng zi he qu',
		],
		'230307'	=> [
			'zh-cn'			=> '麻山区',
			'en'			=> 'ma shan qu',
		],
		'230321'	=> [
			'zh-cn'			=> '鸡东县',
			'en'			=> 'ji dong xian',
		],
		'230322'	=> [
			'zh-cn'			=> '虎林县',
			'en'			=> 'hu lin xian',
		],
		'230381'	=> [
			'zh-cn'			=> '虎林市',
			'en'			=> 'hu lin shi',
		],
		'230382'	=> [
			'zh-cn'			=> '密山市',
			'en'			=> 'mi shan shi',
		],
		'230400'	=> [
			'zh-cn'			=> '鹤岗市',
			'en'			=> 'he gang shi',
		],
		'230402'	=> [
			'zh-cn'			=> '向阳区',
			'en'			=> 'xiang yang qu',
		],
		'230403'	=> [
			'zh-cn'			=> '工农区',
			'en'			=> 'gong nong qu',
		],
		'230404'	=> [
			'zh-cn'			=> '南山区',
			'en'			=> 'nan shan qu',
		],
		'230405'	=> [
			'zh-cn'			=> '兴安区',
			'en'			=> 'xing an qu',
		],
		'230406'	=> [
			'zh-cn'			=> '东山区',
			'en'			=> 'dong shan qu',
		],
		'230407'	=> [
			'zh-cn'			=> '兴山区',
			'en'			=> 'xing shan qu',
		],
		'230421'	=> [
			'zh-cn'			=> '萝北县',
			'en'			=> 'luo bei xian',
		],
		'230422'	=> [
			'zh-cn'			=> '绥滨县',
			'en'			=> 'sui bin xian',
		],
		'230500'	=> [
			'zh-cn'			=> '双鸭山市',
			'en'			=> 'shuang ya shan shi',
		],
		'230502'	=> [
			'zh-cn'			=> '尖山区',
			'en'			=> 'jian shan qu',
		],
		'230503'	=> [
			'zh-cn'			=> '岭东区',
			'en'			=> 'ling dong qu',
		],
		'230504'	=> [
			'zh-cn'			=> '岭西区',
			'en'			=> 'ling xi qu',
		],
		'230505'	=> [
			'zh-cn'			=> '四方台区',
			'en'			=> 'si fang tai qu',
		],
		'230506'	=> [
			'zh-cn'			=> '宝山区',
			'en'			=> 'bao shan qu',
		],
		'230521'	=> [
			'zh-cn'			=> '集贤县',
			'en'			=> 'ji xian xian',
		],
		'230522'	=> [
			'zh-cn'			=> '友谊县',
			'en'			=> 'you yi xian',
		],
		'230523'	=> [
			'zh-cn'			=> '宝清县',
			'en'			=> 'bao qing xian',
		],
		'230524'	=> [
			'zh-cn'			=> '饶河县',
			'en'			=> 'rao he xian',
		],
		'230600'	=> [
			'zh-cn'			=> '大庆市',
			'en'			=> 'da qing shi',
		],
		'230602'	=> [
			'zh-cn'			=> '萨尔图区',
			'en'			=> 'sa er tu qu',
		],
		'230603'	=> [
			'zh-cn'			=> '龙凤区',
			'en'			=> 'long feng qu',
		],
		'230604'	=> [
			'zh-cn'			=> '让胡路区',
			'en'			=> 'rang hu lu qu',
		],
		'230605'	=> [
			'zh-cn'			=> '红岗区',
			'en'			=> 'hong gang qu',
		],
		'230606'	=> [
			'zh-cn'			=> '大同区',
			'en'			=> 'da tong qu',
		],
		'230621'	=> [
			'zh-cn'			=> '肇州县',
			'en'			=> 'zhao zhou xian',
		],
		'230622'	=> [
			'zh-cn'			=> '肇源县',
			'en'			=> 'zhao yuan xian',
		],
		'230623'	=> [
			'zh-cn'			=> '林甸县',
			'en'			=> 'lin dian xian',
		],
		'230624'	=> [
			'zh-cn'			=> '杜尔伯特蒙古族自治县',
			'en'			=> 'du er bo te meng gu zu zi zhi xian',
		],
		'230700'	=> [
			'zh-cn'			=> '伊春市',
			'en'			=> 'yi chun shi',
		],
		'230702'	=> [
			'zh-cn'			=> '伊春区',
			'en'			=> 'yi chun qu',
		],
		'230703'	=> [
			'zh-cn'			=> '南岔区',
			'en'			=> 'nan cha qu',
		],
		'230704'	=> [
			'zh-cn'			=> '友好区',
			'en'			=> 'you hao qu',
		],
		'230705'	=> [
			'zh-cn'			=> '西林区',
			'en'			=> 'xi lin qu',
		],
		'230706'	=> [
			'zh-cn'			=> '翠峦区',
			'en'			=> 'cui luan qu',
		],
		'230707'	=> [
			'zh-cn'			=> '新青区',
			'en'			=> 'xin qing qu',
		],
		'230708'	=> [
			'zh-cn'			=> '美溪区',
			'en'			=> 'mei xi qu',
		],
		'230709'	=> [
			'zh-cn'			=> '金山屯区',
			'en'			=> 'jin shan zhun qu',
		],
		'230710'	=> [
			'zh-cn'			=> '五营区',
			'en'			=> 'wu ying qu',
		],
		'230711'	=> [
			'zh-cn'			=> '乌马河区',
			'en'			=> 'wu ma he qu',
		],
		'230712'	=> [
			'zh-cn'			=> '汤旺河区',
			'en'			=> 'tang wang he qu',
		],
		'230713'	=> [
			'zh-cn'			=> '带岭区',
			'en'			=> 'dai ling qu',
		],
		'230714'	=> [
			'zh-cn'			=> '乌伊岭区',
			'en'			=> 'wu yi ling qu',
		],
		'230715'	=> [
			'zh-cn'			=> '红星区',
			'en'			=> 'hong xing qu',
		],
		'230716'	=> [
			'zh-cn'			=> '上甘岭区',
			'en'			=> 'shang gan ling qu',
		],
		'230721'	=> [
			'zh-cn'			=> '铁力县',
			'en'			=> 'tie li xian',
		],
		'230722'	=> [
			'zh-cn'			=> '嘉荫县',
			'en'			=> 'jia yin xian',
		],
		'230781'	=> [
			'zh-cn'			=> '铁力市',
			'en'			=> 'tie li shi',
		],
		'230800'	=> [
			'zh-cn'			=> '佳木斯市',
			'en'			=> 'jia mu si shi',
		],
		'230802'	=> [
			'zh-cn'			=> '永红区',
			'en'			=> 'yong hong qu',
		],
		'230803'	=> [
			'zh-cn'			=> '向阳区',
			'en'			=> 'xiang yang qu',
		],
		'230804'	=> [
			'zh-cn'			=> '前进区',
			'en'			=> 'qian jin qu',
		],
		'230805'	=> [
			'zh-cn'			=> '东风区',
			'en'			=> 'dong feng qu',
		],
		'230821'	=> [
			'zh-cn'			=> '富锦县',
			'en'			=> 'fu jin xian',
		],
		'230822'	=> [
			'zh-cn'			=> '桦南县',
			'en'			=> 'hua nan xian',
		],
		'230823'	=> [
			'zh-cn'			=> '依兰县',
			'en'			=> 'yi lan xian',
		],
		'230824'	=> [
			'zh-cn'			=> '友谊县',
			'en'			=> 'you yi xian',
		],
		'230825'	=> [
			'zh-cn'			=> '集贤县',
			'en'			=> 'ji xian xian',
		],
		'230826'	=> [
			'zh-cn'			=> '桦川县',
			'en'			=> 'hua chuan xian',
		],
		'230827'	=> [
			'zh-cn'			=> '宝清县',
			'en'			=> 'bao qing xian',
		],
		'230828'	=> [
			'zh-cn'			=> '汤原县',
			'en'			=> 'tang yuan xian',
		],
		'230829'	=> [
			'zh-cn'			=> '绥滨县',
			'en'			=> 'sui bin xian',
		],
		'230830'	=> [
			'zh-cn'			=> '萝北县',
			'en'			=> 'luo bei xian',
		],
		'230831'	=> [
			'zh-cn'			=> '同江县',
			'en'			=> 'tong jiang xian',
		],
		'230832'	=> [
			'zh-cn'			=> '饶河县',
			'en'			=> 'rao he xian',
		],
		'230833'	=> [
			'zh-cn'			=> '抚远县',
			'en'			=> 'fu yuan xian',
		],
		'230881'	=> [
			'zh-cn'			=> '同江市',
			'en'			=> 'tong jiang shi',
		],
		'230882'	=> [
			'zh-cn'			=> '富锦市',
			'en'			=> 'fu jin shi',
		],
		'230883'	=> [
			'zh-cn'			=> '抚远市',
			'en'			=> 'fu yuan shi',
		],
		'230900'	=> [
			'zh-cn'			=> '七台河市',
			'en'			=> 'qi tai he shi',
		],
		'230902'	=> [
			'zh-cn'			=> '新兴区',
			'en'			=> 'xin xing qu',
		],
		'230903'	=> [
			'zh-cn'			=> '桃山区',
			'en'			=> 'tao shan qu',
		],
		'230904'	=> [
			'zh-cn'			=> '茄子河区',
			'en'			=> 'qie zi he qu',
		],
		'230921'	=> [
			'zh-cn'			=> '勃利县',
			'en'			=> 'bo li xian',
		],
		'231000'	=> [
			'zh-cn'			=> '牡丹江市',
			'en'			=> 'mu dan jiang shi',
		],
		'231002'	=> [
			'zh-cn'			=> '东安区',
			'en'			=> 'dong an qu',
		],
		'231003'	=> [
			'zh-cn'			=> '阳明区',
			'en'			=> 'yang ming qu',
		],
		'231004'	=> [
			'zh-cn'			=> '爱民区',
			'en'			=> 'ai min qu',
		],
		'231005'	=> [
			'zh-cn'			=> '西安区',
			'en'			=> 'xi an qu',
		],
		'231020'	=> [
			'zh-cn'			=> '绥芬河市',
			'en'			=> 'sui fen he shi',
		],
		'231021'	=> [
			'zh-cn'			=> '宁安县',
			'en'			=> 'ning an xian',
		],
		'231022'	=> [
			'zh-cn'			=> '海林县',
			'en'			=> 'hai lin xian',
		],
		'231023'	=> [
			'zh-cn'			=> '穆棱县',
			'en'			=> 'mu leng xian',
		],
		'231024'	=> [
			'zh-cn'			=> '东宁县',
			'en'			=> 'dong ning xian',
		],
		'231025'	=> [
			'zh-cn'			=> '林口县',
			'en'			=> 'lin kou xian',
		],
		'231026'	=> [
			'zh-cn'			=> '密山县',
			'en'			=> 'mi shan xian',
		],
		'231027'	=> [
			'zh-cn'			=> '虎林县',
			'en'			=> 'hu lin xian',
		],
		'231081'	=> [
			'zh-cn'			=> '绥芬河市',
			'en'			=> 'sui fen he shi',
		],
		'231083'	=> [
			'zh-cn'			=> '海林市',
			'en'			=> 'hai lin shi',
		],
		'231084'	=> [
			'zh-cn'			=> '宁安市',
			'en'			=> 'ning an shi',
		],
		'231085'	=> [
			'zh-cn'			=> '穆棱市',
			'en'			=> 'mu ling shi',
		],
		'231086'	=> [
			'zh-cn'			=> '东宁市',
			'en'			=> 'dong ning shi',
		],
		'231100'	=> [
			'zh-cn'			=> '黑河市',
			'en'			=> 'hei he shi',
		],
		'231102'	=> [
			'zh-cn'			=> '爱辉区',
			'en'			=> 'ai hui qu',
		],
		'231120'	=> [
			'zh-cn'			=> '绥芬河市',
			'en'			=> 'sui fen he shi',
		],
		'231121'	=> [
			'zh-cn'			=> '嫩江县',
			'en'			=> 'nen jiang xian',
		],
		'231122'	=> [
			'zh-cn'			=> '德都县',
			'en'			=> 'de du xian',
		],
		'231123'	=> [
			'zh-cn'			=> '逊克县',
			'en'			=> 'xun ke xian',
		],
		'231124'	=> [
			'zh-cn'			=> '孙吴县',
			'en'			=> 'sun wu xian',
		],
		'231181'	=> [
			'zh-cn'			=> '北安市',
			'en'			=> 'bei an shi',
		],
		'231182'	=> [
			'zh-cn'			=> '五大连池市',
			'en'			=> 'wu da lian chi shi',
		],
		'231200'	=> [
			'zh-cn'			=> '绥化市',
			'en'			=> 'sui hua shi',
		],
		'231202'	=> [
			'zh-cn'			=> '北林区',
			'en'			=> 'bei lin qu',
		],
		'231221'	=> [
			'zh-cn'			=> '望奎县',
			'en'			=> 'wang kui xian',
		],
		'231222'	=> [
			'zh-cn'			=> '兰西县',
			'en'			=> 'lan xi xian',
		],
		'231223'	=> [
			'zh-cn'			=> '青冈县',
			'en'			=> 'qing gang xian',
		],
		'231224'	=> [
			'zh-cn'			=> '庆安县',
			'en'			=> 'qing an xian',
		],
		'231225'	=> [
			'zh-cn'			=> '明水县',
			'en'			=> 'ming shui xian',
		],
		'231226'	=> [
			'zh-cn'			=> '绥棱县',
			'en'			=> 'sui leng xian',
		],
		'231281'	=> [
			'zh-cn'			=> '安达市',
			'en'			=> 'an da shi',
		],
		'231282'	=> [
			'zh-cn'			=> '肇东市',
			'en'			=> 'zhao dong shi',
		],
		'231283'	=> [
			'zh-cn'			=> '海伦市',
			'en'			=> 'hai lun shi',
		],
		'232100'	=> [
			'zh-cn'			=> '松花江地区',
			'en'			=> 'song hua jiang di qu',
		],
		'232101'	=> [
			'zh-cn'			=> '双城市',
			'en'			=> 'shuang cheng shi',
		],
		'232102'	=> [
			'zh-cn'			=> '尚志市',
			'en'			=> 'shang zhi shi',
		],
		'232103'	=> [
			'zh-cn'			=> '五常市',
			'en'			=> 'wu chang shi',
		],
		'232121'	=> [
			'zh-cn'			=> '呼兰县',
			'en'			=> 'hu lan xian',
		],
		'232123'	=> [
			'zh-cn'			=> '阿城县',
			'en'			=> 'a cheng xian',
		],
		'232124'	=> [
			'zh-cn'			=> '双城县',
			'en'			=> 'shuang cheng xian',
		],
		'232125'	=> [
			'zh-cn'			=> '五常县',
			'en'			=> 'wu chang xian',
		],
		'232126'	=> [
			'zh-cn'			=> '巴彦县',
			'en'			=> 'ba yan xian',
		],
		'232127'	=> [
			'zh-cn'			=> '木兰县',
			'en'			=> 'mu lan xian',
		],
		'232128'	=> [
			'zh-cn'			=> '通河县',
			'en'			=> 'tong he xian',
		],
		'232129'	=> [
			'zh-cn'			=> '尚志县',
			'en'			=> 'shang zhi xian',
		],
		'232130'	=> [
			'zh-cn'			=> '方正县',
			'en'			=> 'fang zheng xian',
		],
		'232131'	=> [
			'zh-cn'			=> '延寿县',
			'en'			=> 'yan shou xian',
		],
		'232200'	=> [
			'zh-cn'			=> '嫩江地区',
			'en'			=> 'nen jiang di qu',
		],
		'232221'	=> [
			'zh-cn'			=> '龙江县',
			'en'			=> 'long jiang xian',
		],
		'232222'	=> [
			'zh-cn'			=> '讷河县',
			'en'			=> 'ne he xian',
		],
		'232223'	=> [
			'zh-cn'			=> '依安县',
			'en'			=> 'yi an xian',
		],
		'232224'	=> [
			'zh-cn'			=> '泰来县',
			'en'			=> 'tai lai xian',
		],
		'232225'	=> [
			'zh-cn'			=> '甘南县',
			'en'			=> 'gan nan xian',
		],
		'232226'	=> [
			'zh-cn'			=> '杜尔伯特蒙古族自治县',
			'en'			=> 'du er bo te meng gu zu zi zhi xian',
		],
		'232227'	=> [
			'zh-cn'			=> '富裕县',
			'en'			=> 'fu yu xian',
		],
		'232228'	=> [
			'zh-cn'			=> '林甸县',
			'en'			=> 'lin dian xian',
		],
		'232229'	=> [
			'zh-cn'			=> '克山县',
			'en'			=> 'ke shan xian',
		],
		'232230'	=> [
			'zh-cn'			=> '克东县',
			'en'			=> 'ke dong xian',
		],
		'232231'	=> [
			'zh-cn'			=> '拜泉县',
			'en'			=> 'bai quan xian',
		],
		'232300'	=> [
			'zh-cn'			=> '绥化地区',
			'en'			=> 'sui hua di qu',
		],
		'232301'	=> [
			'zh-cn'			=> '绥化市',
			'en'			=> 'sui hua shi',
		],
		'232302'	=> [
			'zh-cn'			=> '安达市',
			'en'			=> 'an da shi',
		],
		'232303'	=> [
			'zh-cn'			=> '肇东市',
			'en'			=> 'zhao dong shi',
		],
		'232304'	=> [
			'zh-cn'			=> '海伦市',
			'en'			=> 'hai lun shi',
		],
		'232321'	=> [
			'zh-cn'			=> '海伦县',
			'en'			=> 'hai lun xian',
		],
		'232322'	=> [
			'zh-cn'			=> '肇东县',
			'en'			=> 'zhao dong xian',
		],
		'232323'	=> [
			'zh-cn'			=> '绥化县',
			'en'			=> 'sui hua xian',
		],
		'232324'	=> [
			'zh-cn'			=> '望奎县',
			'en'			=> 'wang kui xian',
		],
		'232325'	=> [
			'zh-cn'			=> '兰西县',
			'en'			=> 'lan xi xian',
		],
		'232326'	=> [
			'zh-cn'			=> '青冈县',
			'en'			=> 'qing gang xian',
		],
		'232327'	=> [
			'zh-cn'			=> '安达县',
			'en'			=> 'an da xian',
		],
		'232328'	=> [
			'zh-cn'			=> '肇源县',
			'en'			=> 'zhao yuan xian',
		],
		'232329'	=> [
			'zh-cn'			=> '肇州县',
			'en'			=> 'zhao zhou xian',
		],
		'232330'	=> [
			'zh-cn'			=> '庆安县',
			'en'			=> 'qing an xian',
		],
		'232331'	=> [
			'zh-cn'			=> '明水县',
			'en'			=> 'ming shui xian',
		],
		'232332'	=> [
			'zh-cn'			=> '绥棱县',
			'en'			=> 'sui leng xian',
		],
		'232400'	=> [
			'zh-cn'			=> '合江地区',
			'en'			=> 'he jiang di qu',
		],
		'232401'	=> [
			'zh-cn'			=> '佳木斯市',
			'en'			=> 'jia mu si shi',
		],
		'232402'	=> [
			'zh-cn'			=> '永红区',
			'en'			=> 'yong hong qu',
		],
		'232403'	=> [
			'zh-cn'			=> '向阳区',
			'en'			=> 'xiang yang qu',
		],
		'232404'	=> [
			'zh-cn'			=> '前进区',
			'en'			=> 'qian jin qu',
		],
		'232405'	=> [
			'zh-cn'			=> '东风区',
			'en'			=> 'dong feng qu',
		],
		'232421'	=> [
			'zh-cn'			=> '富锦县',
			'en'			=> 'fu jin xian',
		],
		'232422'	=> [
			'zh-cn'			=> '桦南县',
			'en'			=> 'hua nan xian',
		],
		'232423'	=> [
			'zh-cn'			=> '依兰县',
			'en'			=> 'yi lan xian',
		],
		'232424'	=> [
			'zh-cn'			=> '勃利县',
			'en'			=> 'bo li xian',
		],
		'232425'	=> [
			'zh-cn'			=> '集贤县',
			'en'			=> 'ji xian xian',
		],
		'232426'	=> [
			'zh-cn'			=> '桦川县',
			'en'			=> 'hua chuan xian',
		],
		'232427'	=> [
			'zh-cn'			=> '宝清县',
			'en'			=> 'bao qing xian',
		],
		'232428'	=> [
			'zh-cn'			=> '汤原县',
			'en'			=> 'tang yuan xian',
		],
		'232429'	=> [
			'zh-cn'			=> '绥滨县',
			'en'			=> 'sui bin xian',
		],
		'232430'	=> [
			'zh-cn'			=> '萝北县',
			'en'			=> 'luo bei xian',
		],
		'232431'	=> [
			'zh-cn'			=> '同江县',
			'en'			=> 'tong jiang xian',
		],
		'232432'	=> [
			'zh-cn'			=> '饶河县',
			'en'			=> 'rao he xian',
		],
		'232433'	=> [
			'zh-cn'			=> '抚远县',
			'en'			=> 'fu yuan xian',
		],
		'232481'	=> [
			'zh-cn'			=> '七台河市',
			'en'			=> 'qi tai he shi',
		],
		'232500'	=> [
			'zh-cn'			=> '牡丹江地区',
			'en'			=> 'mu dan jiang di qu',
		],
		'232501'	=> [
			'zh-cn'			=> '牡丹江市',
			'en'			=> 'mu dan jiang shi',
		],
		'232502'	=> [
			'zh-cn'			=> '东凤区',
			'en'			=> 'dong feng qu',
		],
		'232503'	=> [
			'zh-cn'			=> '先锋区',
			'en'			=> 'xian feng qu',
		],
		'232504'	=> [
			'zh-cn'			=> '爱民区',
			'en'			=> 'ai min qu',
		],
		'232505'	=> [
			'zh-cn'			=> '阳明区',
			'en'			=> 'yang ming qu',
		],
		'232521'	=> [
			'zh-cn'			=> '宁安县',
			'en'			=> 'ning an xian',
		],
		'232522'	=> [
			'zh-cn'			=> '海林县',
			'en'			=> 'hai lin xian',
		],
		'232523'	=> [
			'zh-cn'			=> '穆棱县',
			'en'			=> 'mu leng xian',
		],
		'232524'	=> [
			'zh-cn'			=> '东宁县',
			'en'			=> 'dong ning xian',
		],
		'232525'	=> [
			'zh-cn'			=> '林口县',
			'en'			=> 'lin kou xian',
		],
		'232526'	=> [
			'zh-cn'			=> '密山县',
			'en'			=> 'mi shan xian',
		],
		'232527'	=> [
			'zh-cn'			=> '虎林县',
			'en'			=> 'hu lin xian',
		],
		'232528'	=> [
			'zh-cn'			=> '鸡东县',
			'en'			=> 'ji dong xian',
		],
		'232581'	=> [
			'zh-cn'			=> '绥芬河市',
			'en'			=> 'sui fen he shi',
		],
		'232600'	=> [
			'zh-cn'			=> '黑河地区',
			'en'			=> 'hei he di qu',
		],
		'232601'	=> [
			'zh-cn'			=> '黑河市',
			'en'			=> 'hei he shi',
		],
		'232602'	=> [
			'zh-cn'			=> '北安市',
			'en'			=> 'bei an shi',
		],
		'232603'	=> [
			'zh-cn'			=> '五大连池市',
			'en'			=> 'wu da lian chi shi',
		],
		'232621'	=> [
			'zh-cn'			=> '爱辉县',
			'en'			=> 'ai hui xian',
		],
		'232622'	=> [
			'zh-cn'			=> '嫩江县',
			'en'			=> 'nen jiang xian',
		],
		'232623'	=> [
			'zh-cn'			=> '德都县',
			'en'			=> 'de du xian',
		],
		'232624'	=> [
			'zh-cn'			=> '通北县',
			'en'			=> 'tong bei xian',
		],
		'232625'	=> [
			'zh-cn'			=> '逊克县',
			'en'			=> 'xun ke xian',
		],
		'232626'	=> [
			'zh-cn'			=> '孙吴县',
			'en'			=> 'sun wu xian',
		],
		'232700'	=> [
			'zh-cn'			=> '大兴安岭地区',
			'en'			=> 'da xing an ling di qu',
		],
		'232721'	=> [
			'zh-cn'			=> '呼玛县',
			'en'			=> 'hu ma xian',
		],
		'232722'	=> [
			'zh-cn'			=> '塔河县',
			'en'			=> 'ta he xian',
		],
		'232723'	=> [
			'zh-cn'			=> '漠河县',
			'en'			=> 'mo he xian',
		],
		'239001'	=> [
			'zh-cn'			=> '绥芬河市',
			'en'			=> 'sui fen he shi',
		],
		'239002'	=> [
			'zh-cn'			=> '阿城市',
			'en'			=> 'a cheng shi',
		],
		'239003'	=> [
			'zh-cn'			=> '同江市',
			'en'			=> 'tong jiang shi',
		],
		'239004'	=> [
			'zh-cn'			=> '富锦市',
			'en'			=> 'fu jin shi',
		],
		'239005'	=> [
			'zh-cn'			=> '铁力市',
			'en'			=> 'tie li shi',
		],
		'239006'	=> [
			'zh-cn'			=> '密山市',
			'en'			=> 'mi shan shi',
		],
		'239007'	=> [
			'zh-cn'			=> '海林市',
			'en'			=> 'hai lin shi',
		],
		'239008'	=> [
			'zh-cn'			=> '讷河市',
			'en'			=> 'ne he shi',
		],
		'239009'	=> [
			'zh-cn'			=> '北安市',
			'en'			=> 'bei an shi',
		],
		'239010'	=> [
			'zh-cn'			=> '五大连池市',
			'en'			=> 'wu da lian chi shi',
		],
		'239011'	=> [
			'zh-cn'			=> '宁安市',
			'en'			=> 'ning an shi',
		],
		'310000'	=> [
			'zh-cn'			=> '上海市',
			'en'			=> 'shang hai shi',
		],
		'310101'	=> [
			'zh-cn'			=> '黄浦区',
			'en'			=> 'huang pu qu',
		],
		'310102'	=> [
			'zh-cn'			=> '南市区',
			'en'			=> 'nan shi qu',
		],
		'310103'	=> [
			'zh-cn'			=> '卢湾区',
			'en'			=> 'lu wan qu',
		],
		'310104'	=> [
			'zh-cn'			=> '徐汇区',
			'en'			=> 'xu hui qu',
		],
		'310105'	=> [
			'zh-cn'			=> '长宁区',
			'en'			=> 'chang ning qu',
		],
		'310106'	=> [
			'zh-cn'			=> '静安区',
			'en'			=> 'jing an qu',
		],
		'310107'	=> [
			'zh-cn'			=> '普陀区',
			'en'			=> 'pu tuo qu',
		],
		'310108'	=> [
			'zh-cn'			=> '闸北区',
			'en'			=> 'zha bei qu',
		],
		'310109'	=> [
			'zh-cn'			=> '虹口区',
			'en'			=> 'hong kou qu',
		],
		'310110'	=> [
			'zh-cn'			=> '杨浦区',
			'en'			=> 'yang pu qu',
		],
		'310111'	=> [
			'zh-cn'			=> '吴淞区',
			'en'			=> 'wu song qu',
		],
		'310112'	=> [
			'zh-cn'			=> '闵行区',
			'en'			=> 'min hang qu',
		],
		'310113'	=> [
			'zh-cn'			=> '宝山区',
			'en'			=> 'bao shan qu',
		],
		'310114'	=> [
			'zh-cn'			=> '嘉定区',
			'en'			=> 'jia ding qu',
		],
		'310115'	=> [
			'zh-cn'			=> '浦东新区',
			'en'			=> 'pu dong xin qu',
		],
		'310116'	=> [
			'zh-cn'			=> '金山区',
			'en'			=> 'jin shan qu',
		],
		'310117'	=> [
			'zh-cn'			=> '松江区',
			'en'			=> 'song jiang qu',
		],
		'310118'	=> [
			'zh-cn'			=> '青浦区',
			'en'			=> 'qing pu qu',
		],
		'310119'	=> [
			'zh-cn'			=> '南汇区',
			'en'			=> 'nan hui qu',
		],
		'310120'	=> [
			'zh-cn'			=> '奉贤区',
			'en'			=> 'feng xian qu',
		],
		'310151'	=> [
			'zh-cn'			=> '崇明区',
			'en'			=> 'chong ming qu',
		],
		'310221'	=> [
			'zh-cn'			=> '上海县',
			'en'			=> 'shang hai xian',
		],
		'310222'	=> [
			'zh-cn'			=> '嘉定县',
			'en'			=> 'jia ding xian',
		],
		'310223'	=> [
			'zh-cn'			=> '宝山县',
			'en'			=> 'bao shan xian',
		],
		'310224'	=> [
			'zh-cn'			=> '川沙县',
			'en'			=> 'chuan sha xian',
		],
		'310225'	=> [
			'zh-cn'			=> '南汇县',
			'en'			=> 'nan hui xian',
		],
		'310226'	=> [
			'zh-cn'			=> '奉贤县',
			'en'			=> 'feng xian xian',
		],
		'310227'	=> [
			'zh-cn'			=> '松江县',
			'en'			=> 'song jiang xian',
		],
		'310228'	=> [
			'zh-cn'			=> '金山县',
			'en'			=> 'jin shan xian',
		],
		'310229'	=> [
			'zh-cn'			=> '青浦县',
			'en'			=> 'qing pu xian',
		],
		'310230'	=> [
			'zh-cn'			=> '崇明县',
			'en'			=> 'chong ming xian',
		],
		'320000'	=> [
			'zh-cn'			=> '江苏省',
			'en'			=> 'jiang su sheng',
		],
		'320100'	=> [
			'zh-cn'			=> '南京市',
			'en'			=> 'nan jing shi',
		],
		'320102'	=> [
			'zh-cn'			=> '玄武区',
			'en'			=> 'xuan wu qu',
		],
		'320103'	=> [
			'zh-cn'			=> '白下区',
			'en'			=> 'bai xia qu',
		],
		'320104'	=> [
			'zh-cn'			=> '秦淮区',
			'en'			=> 'qin huai qu',
		],
		'320105'	=> [
			'zh-cn'			=> '建邺区',
			'en'			=> 'jian ye qu',
		],
		'320106'	=> [
			'zh-cn'			=> '鼓楼区',
			'en'			=> 'gu lou qu',
		],
		'320107'	=> [
			'zh-cn'			=> '下关区',
			'en'			=> 'xia guan qu',
		],
		'320111'	=> [
			'zh-cn'			=> '浦口区',
			'en'			=> 'pu kou qu',
		],
		'320112'	=> [
			'zh-cn'			=> '大厂区',
			'en'			=> 'da chang qu',
		],
		'320113'	=> [
			'zh-cn'			=> '栖霞区',
			'en'			=> 'qi xia qu',
		],
		'320114'	=> [
			'zh-cn'			=> '雨花台区',
			'en'			=> 'yu hua tai qu',
		],
		'320115'	=> [
			'zh-cn'			=> '江宁区',
			'en'			=> 'jiang ning qu',
		],
		'320116'	=> [
			'zh-cn'			=> '六合区',
			'en'			=> 'lu he qu',
		],
		'320117'	=> [
			'zh-cn'			=> '溧水区',
			'en'			=> 'li shui qu',
		],
		'320118'	=> [
			'zh-cn'			=> '高淳区',
			'en'			=> 'gao chun qu',
		],
		'320121'	=> [
			'zh-cn'			=> '江宁县',
			'en'			=> 'jiang ning xian',
		],
		'320122'	=> [
			'zh-cn'			=> '江浦县',
			'en'			=> 'jiang pu xian',
		],
		'320123'	=> [
			'zh-cn'			=> '六合县',
			'en'			=> 'liu he xian',
		],
		'320124'	=> [
			'zh-cn'			=> '溧水县',
			'en'			=> 'li shui xian',
		],
		'320125'	=> [
			'zh-cn'			=> '高淳县',
			'en'			=> 'gao chun xian',
		],
		'320200'	=> [
			'zh-cn'			=> '无锡市',
			'en'			=> 'wu xi shi',
		],
		'320202'	=> [
			'zh-cn'			=> '崇安区',
			'en'			=> 'chong an qu',
		],
		'320203'	=> [
			'zh-cn'			=> '南长区',
			'en'			=> 'nan chang qu',
		],
		'320204'	=> [
			'zh-cn'			=> '北塘区',
			'en'			=> 'bei tang qu',
		],
		'320205'	=> [
			'zh-cn'			=> '锡山区',
			'en'			=> 'xi shan qu',
		],
		'320206'	=> [
			'zh-cn'			=> '惠山区',
			'en'			=> 'hui shan qu',
		],
		'320211'	=> [
			'zh-cn'			=> '滨湖区',
			'en'			=> 'bin hu qu',
		],
		'320213'	=> [
			'zh-cn'			=> '梁溪区',
			'en'			=> 'liang xi qu',
		],
		'320214'	=> [
			'zh-cn'			=> '新吴区',
			'en'			=> 'xin wu qu',
		],
		'320221'	=> [
			'zh-cn'			=> '江阴县',
			'en'			=> 'jiang yin xian',
		],
		'320222'	=> [
			'zh-cn'			=> '无锡县',
			'en'			=> 'wu xi xian',
		],
		'320223'	=> [
			'zh-cn'			=> '宜兴县',
			'en'			=> 'yi xing xian',
		],
		'320281'	=> [
			'zh-cn'			=> '江阴市',
			'en'			=> 'jiang yin shi',
		],
		'320282'	=> [
			'zh-cn'			=> '宜兴市',
			'en'			=> 'yi xing shi',
		],
		'320283'	=> [
			'zh-cn'			=> '锡山市',
			'en'			=> 'xi shan shi',
		],
		'320300'	=> [
			'zh-cn'			=> '徐州市',
			'en'			=> 'xu zhou shi',
		],
		'320302'	=> [
			'zh-cn'			=> '鼓楼区',
			'en'			=> 'gu lou qu',
		],
		'320303'	=> [
			'zh-cn'			=> '云龙区',
			'en'			=> 'yun long qu',
		],
		'320304'	=> [
			'zh-cn'			=> '九里区',
			'en'			=> 'jiu li qu',
		],
		'320305'	=> [
			'zh-cn'			=> '贾汪区',
			'en'			=> 'jia wang qu',
		],
		'320311'	=> [
			'zh-cn'			=> '泉山区',
			'en'			=> 'quan shan qu',
		],
		'320312'	=> [
			'zh-cn'			=> '铜山区',
			'en'			=> 'tong shan qu',
		],
		'320323'	=> [
			'zh-cn'			=> '铜山县',
			'en'			=> 'tong shan xian',
		],
		'320324'	=> [
			'zh-cn'			=> '睢宁县',
			'en'			=> 'sui ning xian',
		],
		'320326'	=> [
			'zh-cn'			=> '新沂县',
			'en'			=> 'xin yi xian',
		],
		'320381'	=> [
			'zh-cn'			=> '新沂市',
			'en'			=> 'xin yi shi',
		],
		'320382'	=> [
			'zh-cn'			=> '邳州市',
			'en'			=> 'pi zhou shi',
		],
		'320400'	=> [
			'zh-cn'			=> '常州市',
			'en'			=> 'chang zhou shi',
		],
		'320402'	=> [
			'zh-cn'			=> '天宁区',
			'en'			=> 'tian ning qu',
		],
		'320403'	=> [
			'zh-cn'			=> '广化区',
			'en'			=> 'guang hua qu',
		],
		'320404'	=> [
			'zh-cn'			=> '钟楼区',
			'en'			=> 'zhong lou qu',
		],
		'320405'	=> [
			'zh-cn'			=> '戚墅堰区',
			'en'			=> 'qi shu yan qu',
		],
		'320411'	=> [
			'zh-cn'			=> '新北区',
			'en'			=> 'xin bei qu',
		],
		'320412'	=> [
			'zh-cn'			=> '武进区',
			'en'			=> 'wu jin qu',
		],
		'320413'	=> [
			'zh-cn'			=> '金坛区',
			'en'			=> 'jin tan qu',
		],
		'320421'	=> [
			'zh-cn'			=> '武进县',
			'en'			=> 'wu jin xian',
		],
		'320422'	=> [
			'zh-cn'			=> '金坛县',
			'en'			=> 'jin tan xian',
		],
		'320423'	=> [
			'zh-cn'			=> '溧阳县',
			'en'			=> 'li yang xian',
		],
		'320481'	=> [
			'zh-cn'			=> '溧阳市',
			'en'			=> 'li yang shi',
		],
		'320482'	=> [
			'zh-cn'			=> '金坛市',
			'en'			=> 'jin tan shi',
		],
		'320483'	=> [
			'zh-cn'			=> '武进市',
			'en'			=> 'wu jin shi',
		],
		'320500'	=> [
			'zh-cn'			=> '苏州市',
			'en'			=> 'su zhou shi',
		],
		'320502'	=> [
			'zh-cn'			=> '沧浪区',
			'en'			=> 'cang lang qu',
		],
		'320503'	=> [
			'zh-cn'			=> '平江区',
			'en'			=> 'ping jiang qu',
		],
		'320504'	=> [
			'zh-cn'			=> '金阊区',
			'en'			=> 'jin chang qu',
		],
		'320505'	=> [
			'zh-cn'			=> '虎丘区',
			'en'			=> 'hu qiu qu',
		],
		'320506'	=> [
			'zh-cn'			=> '吴中区',
			'en'			=> 'wu zhong qu',
		],
		'320507'	=> [
			'zh-cn'			=> '相城区',
			'en'			=> 'xiang cheng qu',
		],
		'320508'	=> [
			'zh-cn'			=> '姑苏区',
			'en'			=> 'gu su qu',
		],
		'320509'	=> [
			'zh-cn'			=> '吴江区',
			'en'			=> 'wu jiang qu',
		],
		'320520'	=> [
			'zh-cn'			=> '常熟市',
			'en'			=> 'chang shu shi',
		],
		'320521'	=> [
			'zh-cn'			=> '沙洲县',
			'en'			=> 'sha zhou xian',
		],
		'320522'	=> [
			'zh-cn'			=> '太仓县',
			'en'			=> 'tai cang xian',
		],
		'320523'	=> [
			'zh-cn'			=> '昆山县',
			'en'			=> 'kun shan xian',
		],
		'320525'	=> [
			'zh-cn'			=> '吴江县',
			'en'			=> 'wu jiang xian',
		],
		'320581'	=> [
			'zh-cn'			=> '常熟市',
			'en'			=> 'chang shu shi',
		],
		'320582'	=> [
			'zh-cn'			=> '张家港市',
			'en'			=> 'zhang jia gang shi',
		],
		'320583'	=> [
			'zh-cn'			=> '昆山市',
			'en'			=> 'kun shan shi',
		],
		'320584'	=> [
			'zh-cn'			=> '吴江市',
			'en'			=> 'wu jiang shi',
		],
		'320585'	=> [
			'zh-cn'			=> '太仓市',
			'en'			=> 'tai cang shi',
		],
		'320586'	=> [
			'zh-cn'			=> '吴县市',
			'en'			=> 'wu xian shi',
		],
		'320600'	=> [
			'zh-cn'			=> '南通市',
			'en'			=> 'nan tong shi',
		],
		'320602'	=> [
			'zh-cn'			=> '崇川区',
			'en'			=> 'chong chuan qu',
		],
		'320603'	=> [
			'zh-cn'			=> '港闸区',
			'en'			=> 'gang zha qu',
		],
		'320611'	=> [
			'zh-cn'			=> '港闸区',
			'en'			=> 'gang zha qu',
		],
		'320612'	=> [
			'zh-cn'			=> '通州区',
			'en'			=> 'tong zhou qu',
		],
		'320621'	=> [
			'zh-cn'			=> '海安县',
			'en'			=> 'hai an xian',
		],
		'320622'	=> [
			'zh-cn'			=> '如皋县',
			'en'			=> 'ru gao xian',
		],
		'320623'	=> [
			'zh-cn'			=> '如东县',
			'en'			=> 'ru dong xian',
		],
		'320624'	=> [
			'zh-cn'			=> '南通县',
			'en'			=> 'nan tong xian',
		],
		'320625'	=> [
			'zh-cn'			=> '海门县',
			'en'			=> 'hai men xian',
		],
		'320626'	=> [
			'zh-cn'			=> '启东县',
			'en'			=> 'qi dong xian',
		],
		'320681'	=> [
			'zh-cn'			=> '启东市',
			'en'			=> 'qi dong shi',
		],
		'320682'	=> [
			'zh-cn'			=> '如皋市',
			'en'			=> 'ru gao shi',
		],
		'320683'	=> [
			'zh-cn'			=> '通州市',
			'en'			=> 'tong zhou shi',
		],
		'320684'	=> [
			'zh-cn'			=> '海门市',
			'en'			=> 'hai men shi',
		],
		'320700'	=> [
			'zh-cn'			=> '连云港市',
			'en'			=> 'lian yun gang shi',
		],
		'320702'	=> [
			'zh-cn'			=> '新海区',
			'en'			=> 'xin hai qu',
		],
		'320703'	=> [
			'zh-cn'			=> '连云区',
			'en'			=> 'lian yun qu',
		],
		'320704'	=> [
			'zh-cn'			=> '云台区',
			'en'			=> 'yun tai qu',
		],
		'320705'	=> [
			'zh-cn'			=> '新浦区',
			'en'			=> 'xin pu qu',
		],
		'320706'	=> [
			'zh-cn'			=> '海州区',
			'en'			=> 'hai zhou qu',
		],
		'320707'	=> [
			'zh-cn'			=> '赣榆区',
			'en'			=> 'gan yu qu',
		],
		'320721'	=> [
			'zh-cn'			=> '赣榆县',
			'en'			=> 'gan yu xian',
		],
		'320722'	=> [
			'zh-cn'			=> '东海县',
			'en'			=> 'dong hai xian',
		],
		'320723'	=> [
			'zh-cn'			=> '灌云县',
			'en'			=> 'guan yun xian',
		],
		'320724'	=> [
			'zh-cn'			=> '灌南县',
			'en'			=> 'guan nan xian',
		],
		'320800'	=> [
			'zh-cn'			=> '淮安市',
			'en'			=> 'huai an shi',
		],
		'320802'	=> [
			'zh-cn'			=> '清河区',
			'en'			=> 'qing he qu',
		],
		'320803'	=> [
			'zh-cn'			=> '淮安区',
			'en'			=> 'huai an qu',
		],
		'320804'	=> [
			'zh-cn'			=> '淮阴区',
			'en'			=> 'huai yin qu',
		],
		'320811'	=> [
			'zh-cn'			=> '清浦区',
			'en'			=> 'qing pu qu',
		],
		'320812'	=> [
			'zh-cn'			=> '清江浦区',
			'en'			=> 'qing jiang pu qu',
		],
		'320813'	=> [
			'zh-cn'			=> '洪泽区',
			'en'			=> 'hong ze qu',
		],
		'320821'	=> [
			'zh-cn'			=> '淮阴县',
			'en'			=> 'huai yin xian',
		],
		'320822'	=> [
			'zh-cn'			=> '灌南县',
			'en'			=> 'guan nan xian',
		],
		'320823'	=> [
			'zh-cn'			=> '沭阳县',
			'en'			=> 'shu yang xian',
		],
		'320824'	=> [
			'zh-cn'			=> '宿迁县',
			'en'			=> 'su qian xian',
		],
		'320825'	=> [
			'zh-cn'			=> '泗阳县',
			'en'			=> 'si yang xian',
		],
		'320826'	=> [
			'zh-cn'			=> '涟水县',
			'en'			=> 'lian shui xian',
		],
		'320827'	=> [
			'zh-cn'			=> '泗洪县',
			'en'			=> 'si hong xian',
		],
		'320828'	=> [
			'zh-cn'			=> '淮安县',
			'en'			=> 'huai an xian',
		],
		'320829'	=> [
			'zh-cn'			=> '洪泽县',
			'en'			=> 'hong ze xian',
		],
		'320830'	=> [
			'zh-cn'			=> '盱眙县',
			'en'			=> 'xu yi xian',
		],
		'320831'	=> [
			'zh-cn'			=> '金湖县',
			'en'			=> 'jin hu xian',
		],
		'320881'	=> [
			'zh-cn'			=> '宿迁市',
			'en'			=> 'su qian shi',
		],
		'320882'	=> [
			'zh-cn'			=> '淮安市',
			'en'			=> 'huai an shi',
		],
		'320900'	=> [
			'zh-cn'			=> '盐城市',
			'en'			=> 'yan cheng shi',
		],
		'320902'	=> [
			'zh-cn'			=> '亭湖区',
			'en'			=> 'ting hu qu',
		],
		'320903'	=> [
			'zh-cn'			=> '盐都区',
			'en'			=> 'yan du qu',
		],
		'320904'	=> [
			'zh-cn'			=> '大丰区',
			'en'			=> 'da feng qu',
		],
		'320921'	=> [
			'zh-cn'			=> '响水县',
			'en'			=> 'xiang shui xian',
		],
		'320922'	=> [
			'zh-cn'			=> '滨海县',
			'en'			=> 'bin hai xian',
		],
		'320923'	=> [
			'zh-cn'			=> '阜宁县',
			'en'			=> 'fu ning xian',
		],
		'320924'	=> [
			'zh-cn'			=> '射阳县',
			'en'			=> 'she yang xian',
		],
		'320925'	=> [
			'zh-cn'			=> '建湖县',
			'en'			=> 'jian hu xian',
		],
		'320926'	=> [
			'zh-cn'			=> '大丰县',
			'en'			=> 'da feng xian',
		],
		'320927'	=> [
			'zh-cn'			=> '东台县',
			'en'			=> 'dong tai xian',
		],
		'320928'	=> [
			'zh-cn'			=> '盐都县',
			'en'			=> 'yan du xian',
		],
		'320981'	=> [
			'zh-cn'			=> '东台市',
			'en'			=> 'dong tai shi',
		],
		'320982'	=> [
			'zh-cn'			=> '大丰市',
			'en'			=> 'da feng shi',
		],
		'321000'	=> [
			'zh-cn'			=> '扬州市',
			'en'			=> 'yang zhou shi',
		],
		'321002'	=> [
			'zh-cn'			=> '广陵区',
			'en'			=> 'guang ling qu',
		],
		'321003'	=> [
			'zh-cn'			=> '邗江区',
			'en'			=> 'han jiang qu',
		],
		'321011'	=> [
			'zh-cn'			=> '维扬区',
			'en'			=> 'wei yang qu',
		],
		'321012'	=> [
			'zh-cn'			=> '江都区',
			'en'			=> 'jiang du qu',
		],
		'321020'	=> [
			'zh-cn'			=> '泰州市',
			'en'			=> 'tai zhou shi',
		],
		'321021'	=> [
			'zh-cn'			=> '兴化县',
			'en'			=> 'xing hua xian',
		],
		'321022'	=> [
			'zh-cn'			=> '高邮县',
			'en'			=> 'gao you xian',
		],
		'321023'	=> [
			'zh-cn'			=> '宝应县',
			'en'			=> 'bao ying xian',
		],
		'321024'	=> [
			'zh-cn'			=> '靖江县',
			'en'			=> 'jing jiang xian',
		],
		'321025'	=> [
			'zh-cn'			=> '泰兴县',
			'en'			=> 'tai xing xian',
		],
		'321026'	=> [
			'zh-cn'			=> '江都县',
			'en'			=> 'jiang du xian',
		],
		'321027'	=> [
			'zh-cn'			=> '邗江县',
			'en'			=> 'han jiang xian',
		],
		'321029'	=> [
			'zh-cn'			=> '仪征县',
			'en'			=> 'yi zheng xian',
		],
		'321081'	=> [
			'zh-cn'			=> '仪征市',
			'en'			=> 'yi zheng shi',
		],
		'321082'	=> [
			'zh-cn'			=> '泰州市',
			'en'			=> 'tai zhou shi',
		],
		'321083'	=> [
			'zh-cn'			=> '兴化市',
			'en'			=> 'xing hua shi',
		],
		'321084'	=> [
			'zh-cn'			=> '高邮市',
			'en'			=> 'gao you shi',
		],
		'321085'	=> [
			'zh-cn'			=> '靖江市',
			'en'			=> 'jing jiang shi',
		],
		'321086'	=> [
			'zh-cn'			=> '泰兴市',
			'en'			=> 'tai xing shi',
		],
		'321087'	=> [
			'zh-cn'			=> '姜堰市',
			'en'			=> 'jiang yan shi',
		],
		'321088'	=> [
			'zh-cn'			=> '江都市',
			'en'			=> 'jiang du shi',
		],
		'321100'	=> [
			'zh-cn'			=> '镇江市',
			'en'			=> 'zhen jiang shi',
		],
		'321102'	=> [
			'zh-cn'			=> '京口区',
			'en'			=> 'jing kou qu',
		],
		'321111'	=> [
			'zh-cn'			=> '润州区',
			'en'			=> 'run zhou qu',
		],
		'321112'	=> [
			'zh-cn'			=> '丹徒区',
			'en'			=> 'dan tu qu',
		],
		'321121'	=> [
			'zh-cn'			=> '丹徒县',
			'en'			=> 'dan tu xian',
		],
		'321122'	=> [
			'zh-cn'			=> '丹阳县',
			'en'			=> 'dan yang xian',
		],
		'321123'	=> [
			'zh-cn'			=> '句容县',
			'en'			=> 'ju rong xian',
		],
		'321124'	=> [
			'zh-cn'			=> '扬中县',
			'en'			=> 'yang zhong xian',
		],
		'321181'	=> [
			'zh-cn'			=> '丹阳市',
			'en'			=> 'dan yang shi',
		],
		'321182'	=> [
			'zh-cn'			=> '扬中市',
			'en'			=> 'yang zhong shi',
		],
		'321183'	=> [
			'zh-cn'			=> '句容市',
			'en'			=> 'ju rong shi',
		],
		'321200'	=> [
			'zh-cn'			=> '泰州市',
			'en'			=> 'tai zhou shi',
		],
		'321202'	=> [
			'zh-cn'			=> '海陵区',
			'en'			=> 'hai ling qu',
		],
		'321203'	=> [
			'zh-cn'			=> '高港区',
			'en'			=> 'gao gang qu',
		],
		'321204'	=> [
			'zh-cn'			=> '姜堰区',
			'en'			=> 'jiang yan qu',
		],
		'321281'	=> [
			'zh-cn'			=> '兴化市',
			'en'			=> 'xing hua shi',
		],
		'321282'	=> [
			'zh-cn'			=> '靖江市',
			'en'			=> 'jing jiang shi',
		],
		'321283'	=> [
			'zh-cn'			=> '泰兴市',
			'en'			=> 'tai xing shi',
		],
		'321284'	=> [
			'zh-cn'			=> '姜堰市',
			'en'			=> 'jiang yan shi',
		],
		'321300'	=> [
			'zh-cn'			=> '宿迁市',
			'en'			=> 'su qian shi',
		],
		'321302'	=> [
			'zh-cn'			=> '宿城区',
			'en'			=> 'su cheng qu',
		],
		'321311'	=> [
			'zh-cn'			=> '宿豫区',
			'en'			=> 'su yu qu',
		],
		'321321'	=> [
			'zh-cn'			=> '宿豫县',
			'en'			=> 'su yu xian',
		],
		'321322'	=> [
			'zh-cn'			=> '沭阳县',
			'en'			=> 'shu yang xian',
		],
		'321323'	=> [
			'zh-cn'			=> '泗阳县',
			'en'			=> 'si yang xian',
		],
		'321324'	=> [
			'zh-cn'			=> '泗洪县',
			'en'			=> 'si hong xian',
		],
		'322100'	=> [
			'zh-cn'			=> '徐州地区',
			'en'			=> 'xu zhou di qu',
		],
		'322123'	=> [
			'zh-cn'			=> '铜山县',
			'en'			=> 'tong shan xian',
		],
		'322124'	=> [
			'zh-cn'			=> '睢宁县',
			'en'			=> 'sui ning xian',
		],
		'322126'	=> [
			'zh-cn'			=> '新沂县',
			'en'			=> 'xin yi xian',
		],
		'322127'	=> [
			'zh-cn'			=> '赣榆县',
			'en'			=> 'gan yu xian',
		],
		'322128'	=> [
			'zh-cn'			=> '东海县',
			'en'			=> 'dong hai xian',
		],
		'322200'	=> [
			'zh-cn'			=> '淮阴地区',
			'en'			=> 'huai yin di qu',
		],
		'322201'	=> [
			'zh-cn'			=> '清江市',
			'en'			=> 'qing jiang shi',
		],
		'322221'	=> [
			'zh-cn'			=> '淮阴县',
			'en'			=> 'huai yin xian',
		],
		'322222'	=> [
			'zh-cn'			=> '灌南县',
			'en'			=> 'guan nan xian',
		],
		'322223'	=> [
			'zh-cn'			=> '沭阳县',
			'en'			=> 'shu yang xian',
		],
		'322224'	=> [
			'zh-cn'			=> '宿迁县',
			'en'			=> 'su qian xian',
		],
		'322225'	=> [
			'zh-cn'			=> '泗阳县',
			'en'			=> 'si yang xian',
		],
		'322226'	=> [
			'zh-cn'			=> '涟水县',
			'en'			=> 'lian shui xian',
		],
		'322227'	=> [
			'zh-cn'			=> '泗洪县',
			'en'			=> 'si hong xian',
		],
		'322228'	=> [
			'zh-cn'			=> '淮安县',
			'en'			=> 'huai an xian',
		],
		'322229'	=> [
			'zh-cn'			=> '洪泽县',
			'en'			=> 'hong ze xian',
		],
		'322230'	=> [
			'zh-cn'			=> '盱眙县',
			'en'			=> 'xu yi xian',
		],
		'322231'	=> [
			'zh-cn'			=> '金湖县',
			'en'			=> 'jin hu xian',
		],
		'322232'	=> [
			'zh-cn'			=> '灌云县',
			'en'			=> 'guan yun xian',
		],
		'322300'	=> [
			'zh-cn'			=> '盐城地区',
			'en'			=> 'yan cheng di qu',
		],
		'322321'	=> [
			'zh-cn'			=> '盐城县',
			'en'			=> 'yan cheng xian',
		],
		'322322'	=> [
			'zh-cn'			=> '响水县',
			'en'			=> 'xiang shui xian',
		],
		'322323'	=> [
			'zh-cn'			=> '滨海县',
			'en'			=> 'bin hai xian',
		],
		'322324'	=> [
			'zh-cn'			=> '阜宁县',
			'en'			=> 'fu ning xian',
		],
		'322325'	=> [
			'zh-cn'			=> '射阳县',
			'en'			=> 'she yang xian',
		],
		'322326'	=> [
			'zh-cn'			=> '建湖县',
			'en'			=> 'jian hu xian',
		],
		'322327'	=> [
			'zh-cn'			=> '大丰县',
			'en'			=> 'da feng xian',
		],
		'322328'	=> [
			'zh-cn'			=> '东台县',
			'en'			=> 'dong tai xian',
		],
		'322400'	=> [
			'zh-cn'			=> '南通地区',
			'en'			=> 'nan tong di qu',
		],
		'322421'	=> [
			'zh-cn'			=> '海安县',
			'en'			=> 'hai an xian',
		],
		'322422'	=> [
			'zh-cn'			=> '如皋县',
			'en'			=> 'ru gao xian',
		],
		'322423'	=> [
			'zh-cn'			=> '如东县',
			'en'			=> 'ru dong xian',
		],
		'322424'	=> [
			'zh-cn'			=> '南通县',
			'en'			=> 'nan tong xian',
		],
		'322425'	=> [
			'zh-cn'			=> '海门县',
			'en'			=> 'hai men xian',
		],
		'322426'	=> [
			'zh-cn'			=> '启东县',
			'en'			=> 'qi dong xian',
		],
		'322500'	=> [
			'zh-cn'			=> '扬州地区',
			'en'			=> 'yang zhou di qu',
		],
		'322501'	=> [
			'zh-cn'			=> '扬州市',
			'en'			=> 'yang zhou shi',
		],
		'322502'	=> [
			'zh-cn'			=> '泰州市',
			'en'			=> 'tai zhou shi',
		],
		'322521'	=> [
			'zh-cn'			=> '兴化县',
			'en'			=> 'xing hua xian',
		],
		'322522'	=> [
			'zh-cn'			=> '高邮县',
			'en'			=> 'gao you xian',
		],
		'322523'	=> [
			'zh-cn'			=> '宝应县',
			'en'			=> 'bao ying xian',
		],
		'322524'	=> [
			'zh-cn'			=> '靖江县',
			'en'			=> 'jing jiang xian',
		],
		'322525'	=> [
			'zh-cn'			=> '泰兴县',
			'en'			=> 'tai xing xian',
		],
		'322526'	=> [
			'zh-cn'			=> '江都县',
			'en'			=> 'jiang du xian',
		],
		'322527'	=> [
			'zh-cn'			=> '邗江县',
			'en'			=> 'han jiang xian',
		],
		'322529'	=> [
			'zh-cn'			=> '仪征县',
			'en'			=> 'yi zheng xian',
		],
		'322600'	=> [
			'zh-cn'			=> '镇江地区',
			'en'			=> 'zhen jiang di qu',
		],
		'322601'	=> [
			'zh-cn'			=> '镇江市',
			'en'			=> 'zhen jiang shi',
		],
		'322621'	=> [
			'zh-cn'			=> '金坛县',
			'en'			=> 'jin tan xian',
		],
		'322622'	=> [
			'zh-cn'			=> '溧阳县',
			'en'			=> 'li yang xian',
		],
		'322623'	=> [
			'zh-cn'			=> '丹徒县',
			'en'			=> 'dan tu xian',
		],
		'322624'	=> [
			'zh-cn'			=> '丹阳县',
			'en'			=> 'dan yang xian',
		],
		'322625'	=> [
			'zh-cn'			=> '句容县',
			'en'			=> 'ju rong xian',
		],
		'322626'	=> [
			'zh-cn'			=> '扬中县',
			'en'			=> 'yang zhong xian',
		],
		'322627'	=> [
			'zh-cn'			=> '溧水县',
			'en'			=> 'li shui xian',
		],
		'322628'	=> [
			'zh-cn'			=> '高淳县',
			'en'			=> 'gao chun xian',
		],
		'322629'	=> [
			'zh-cn'			=> '宜兴县',
			'en'			=> 'yi xing xian',
		],
		'322630'	=> [
			'zh-cn'			=> '武进县',
			'en'			=> 'wu jin xian',
		],
		'322700'	=> [
			'zh-cn'			=> '苏州地区',
			'en'			=> 'su zhou di qu',
		],
		'322721'	=> [
			'zh-cn'			=> '江阴县',
			'en'			=> 'jiang yin xian',
		],
		'322722'	=> [
			'zh-cn'			=> '无锡县',
			'en'			=> 'wu xi xian',
		],
		'322723'	=> [
			'zh-cn'			=> '常熟县',
			'en'			=> 'chang shu xian',
		],
		'322724'	=> [
			'zh-cn'			=> '沙洲县',
			'en'			=> 'sha zhou xian',
		],
		'322725'	=> [
			'zh-cn'			=> '太仓县',
			'en'			=> 'tai cang xian',
		],
		'322726'	=> [
			'zh-cn'			=> '昆山县',
			'en'			=> 'kun shan xian',
		],
		'322728'	=> [
			'zh-cn'			=> '吴江县',
			'en'			=> 'wu jiang xian',
		],
		'329001'	=> [
			'zh-cn'			=> '泰州市',
			'en'			=> 'tai zhou shi',
		],
		'329002'	=> [
			'zh-cn'			=> '仪征市',
			'en'			=> 'yi zheng shi',
		],
		'329003'	=> [
			'zh-cn'			=> '常熟市',
			'en'			=> 'chang shu shi',
		],
		'329004'	=> [
			'zh-cn'			=> '张家港市',
			'en'			=> 'zhang jia gang shi',
		],
		'329005'	=> [
			'zh-cn'			=> '江阴市',
			'en'			=> 'jiang yin shi',
		],
		'329006'	=> [
			'zh-cn'			=> '宿迁市',
			'en'			=> 'su qian shi',
		],
		'329007'	=> [
			'zh-cn'			=> '丹阳市',
			'en'			=> 'dan yang shi',
		],
		'329008'	=> [
			'zh-cn'			=> '东台市',
			'en'			=> 'dong tai shi',
		],
		'329009'	=> [
			'zh-cn'			=> '兴化市',
			'en'			=> 'xing hua shi',
		],
		'329010'	=> [
			'zh-cn'			=> '淮安市',
			'en'			=> 'huai an shi',
		],
		'329011'	=> [
			'zh-cn'			=> '宜兴市',
			'en'			=> 'yi xing shi',
		],
		'329012'	=> [
			'zh-cn'			=> '昆山市',
			'en'			=> 'kun shan shi',
		],
		'329013'	=> [
			'zh-cn'			=> '启东市',
			'en'			=> 'qi dong shi',
		],
		'329014'	=> [
			'zh-cn'			=> '新沂市',
			'en'			=> 'xin yi shi',
		],
		'329015'	=> [
			'zh-cn'			=> '溧阳市',
			'en'			=> 'li yang shi',
		],
		'329016'	=> [
			'zh-cn'			=> '如皋市',
			'en'			=> 'ru gao shi',
		],
		'329017'	=> [
			'zh-cn'			=> '高邮市',
			'en'			=> 'gao you shi',
		],
		'329018'	=> [
			'zh-cn'			=> '吴江市',
			'en'			=> 'wu jiang shi',
		],
		'329019'	=> [
			'zh-cn'			=> '邳州市',
			'en'			=> 'pi zhou shi',
		],
		'329020'	=> [
			'zh-cn'			=> '泰兴市',
			'en'			=> 'tai xing shi',
		],
		'329021'	=> [
			'zh-cn'			=> '通州市',
			'en'			=> 'tong zhou shi',
		],
		'329022'	=> [
			'zh-cn'			=> '太仓市',
			'en'			=> 'tai cang shi',
		],
		'329023'	=> [
			'zh-cn'			=> '靖江市',
			'en'			=> 'jing jiang shi',
		],
		'329024'	=> [
			'zh-cn'			=> '金坛市',
			'en'			=> 'jin tan shi',
		],
		'329025'	=> [
			'zh-cn'			=> '江都市',
			'en'			=> 'jiang du shi',
		],
		'329026'	=> [
			'zh-cn'			=> '海门市',
			'en'			=> 'hai men shi',
		],
		'329027'	=> [
			'zh-cn'			=> '扬中市',
			'en'			=> 'yang zhong shi',
		],
		'329028'	=> [
			'zh-cn'			=> '姜堰市',
			'en'			=> 'jiang yan shi',
		],
		'330000'	=> [
			'zh-cn'			=> '浙江省',
			'en'			=> 'zhe jiang sheng',
		],
		'330100'	=> [
			'zh-cn'			=> '杭州市',
			'en'			=> 'hang zhou shi',
		],
		'330102'	=> [
			'zh-cn'			=> '上城区',
			'en'			=> 'shang cheng qu',
		],
		'330103'	=> [
			'zh-cn'			=> '下城区',
			'en'			=> 'xia cheng qu',
		],
		'330104'	=> [
			'zh-cn'			=> '江干区',
			'en'			=> 'jiang gan qu',
		],
		'330105'	=> [
			'zh-cn'			=> '拱墅区',
			'en'			=> 'gong shu qu',
		],
		'330106'	=> [
			'zh-cn'			=> '西湖区',
			'en'			=> 'xi hu qu',
		],
		'330107'	=> [
			'zh-cn'			=> '半山区',
			'en'			=> 'ban shan qu',
		],
		'330108'	=> [
			'zh-cn'			=> '滨江区',
			'en'			=> 'bin jiang qu',
		],
		'330109'	=> [
			'zh-cn'			=> '萧山区',
			'en'			=> 'xiao shan qu',
		],
		'330110'	=> [
			'zh-cn'			=> '余杭区',
			'en'			=> 'yu hang qu',
		],
		'330111'	=> [
			'zh-cn'			=> '富阳区',
			'en'			=> 'fu yang qu',
		],
		'330121'	=> [
			'zh-cn'			=> '萧山县',
			'en'			=> 'xiao shan xian',
		],
		'330122'	=> [
			'zh-cn'			=> '桐庐县',
			'en'			=> 'tong lu xian',
		],
		'330123'	=> [
			'zh-cn'			=> '富阳县',
			'en'			=> 'fu yang xian',
		],
		'330124'	=> [
			'zh-cn'			=> '临安县',
			'en'			=> 'lin an xian',
		],
		'330125'	=> [
			'zh-cn'			=> '余杭县',
			'en'			=> 'yu hang xian',
		],
		'330126'	=> [
			'zh-cn'			=> '建德县',
			'en'			=> 'jian de xian',
		],
		'330127'	=> [
			'zh-cn'			=> '淳安县',
			'en'			=> 'chun an xian',
		],
		'330181'	=> [
			'zh-cn'			=> '萧山市',
			'en'			=> 'xiao shan shi',
		],
		'330182'	=> [
			'zh-cn'			=> '建德市',
			'en'			=> 'jian de shi',
		],
		'330183'	=> [
			'zh-cn'			=> '富阳市',
			'en'			=> 'fu yang shi',
		],
		'330184'	=> [
			'zh-cn'			=> '余杭市',
			'en'			=> 'yu hang shi',
		],
		'330185'	=> [
			'zh-cn'			=> '临安市',
			'en'			=> 'lin an shi',
		],
		'330200'	=> [
			'zh-cn'			=> '宁波市',
			'en'			=> 'ning bo shi',
		],
		'330202'	=> [
			'zh-cn'			=> '镇明区',
			'en'			=> 'zhen ming qu',
		],
		'330203'	=> [
			'zh-cn'			=> '海曙区',
			'en'			=> 'hai shu qu',
		],
		'330204'	=> [
			'zh-cn'			=> '江东区',
			'en'			=> 'jiang dong qu',
		],
		'330205'	=> [
			'zh-cn'			=> '江北区',
			'en'			=> 'jiang bei qu',
		],
		'330206'	=> [
			'zh-cn'			=> '北仑区',
			'en'			=> 'bei lun qu',
		],
		'330211'	=> [
			'zh-cn'			=> '镇海区',
			'en'			=> 'zhen hai qu',
		],
		'330212'	=> [
			'zh-cn'			=> '鄞州区',
			'en'			=> 'yin zhou qu',
		],
		'330213'	=> [
			'zh-cn'			=> '奉化区',
			'en'			=> 'feng hua qu',
		],
		'330219'	=> [
			'zh-cn'			=> '余姚市',
			'en'			=> 'yu yao shi',
		],
		'330221'	=> [
			'zh-cn'			=> '镇海县',
			'en'			=> 'zhen hai xian',
		],
		'330222'	=> [
			'zh-cn'			=> '慈溪县',
			'en'			=> 'ci xi xian',
		],
		'330223'	=> [
			'zh-cn'			=> '余姚县',
			'en'			=> 'yu yao xian',
		],
		'330224'	=> [
			'zh-cn'			=> '奉化县',
			'en'			=> 'feng hua xian',
		],
		'330225'	=> [
			'zh-cn'			=> '象山县',
			'en'			=> 'xiang shan xian',
		],
		'330226'	=> [
			'zh-cn'			=> '宁海县',
			'en'			=> 'ning hai xian',
		],
		'330281'	=> [
			'zh-cn'			=> '余姚市',
			'en'			=> 'yu yao shi',
		],
		'330282'	=> [
			'zh-cn'			=> '慈溪市',
			'en'			=> 'ci xi shi',
		],
		'330283'	=> [
			'zh-cn'			=> '奉化市',
			'en'			=> 'feng hua shi',
		],
		'330300'	=> [
			'zh-cn'			=> '温州市',
			'en'			=> 'wen zhou shi',
		],
		'330301'	=> [
			'zh-cn'			=> '东城区',
			'en'			=> 'dong cheng qu',
		],
		'330302'	=> [
			'zh-cn'			=> '鹿城区',
			'en'			=> 'lu cheng qu',
		],
		'330303'	=> [
			'zh-cn'			=> '龙湾区',
			'en'			=> 'long wan qu',
		],
		'330304'	=> [
			'zh-cn'			=> '瓯海区',
			'en'			=> 'ou hai qu',
		],
		'330305'	=> [
			'zh-cn'			=> '洞头区',
			'en'			=> 'dong tou qu',
		],
		'330321'	=> [
			'zh-cn'			=> '瓯海县',
			'en'			=> 'ou hai xian',
		],
		'330322'	=> [
			'zh-cn'			=> '洞头县',
			'en'			=> 'dong tou xian',
		],
		'330323'	=> [
			'zh-cn'			=> '乐清县',
			'en'			=> 'yue qing xian',
		],
		'330324'	=> [
			'zh-cn'			=> '永嘉县',
			'en'			=> 'yong jia xian',
		],
		'330325'	=> [
			'zh-cn'			=> '瑞安县',
			'en'			=> 'rui an xian',
		],
		'330326'	=> [
			'zh-cn'			=> '平阳县',
			'en'			=> 'ping yang xian',
		],
		'330327'	=> [
			'zh-cn'			=> '苍南县',
			'en'			=> 'cang nan xian',
		],
		'330328'	=> [
			'zh-cn'			=> '文成县',
			'en'			=> 'wen cheng xian',
		],
		'330329'	=> [
			'zh-cn'			=> '泰顺县',
			'en'			=> 'tai shun xian',
		],
		'330381'	=> [
			'zh-cn'			=> '瑞安市',
			'en'			=> 'rui an shi',
		],
		'330382'	=> [
			'zh-cn'			=> '乐清市',
			'en'			=> 'yue qing shi',
		],
		'330400'	=> [
			'zh-cn'			=> '嘉兴市',
			'en'			=> 'jia xing shi',
		],
		'330402'	=> [
			'zh-cn'			=> '南湖区',
			'en'			=> 'nan hu qu',
		],
		'330411'	=> [
			'zh-cn'			=> '秀洲区',
			'en'			=> 'xiu zhou qu',
		],
		'330421'	=> [
			'zh-cn'			=> '嘉善县',
			'en'			=> 'jia shan xian',
		],
		'330422'	=> [
			'zh-cn'			=> '平湖县',
			'en'			=> 'ping hu xian',
		],
		'330423'	=> [
			'zh-cn'			=> '海宁县',
			'en'			=> 'hai ning xian',
		],
		'330424'	=> [
			'zh-cn'			=> '海盐县',
			'en'			=> 'hai yan xian',
		],
		'330425'	=> [
			'zh-cn'			=> '桐乡县',
			'en'			=> 'tong xiang xian',
		],
		'330481'	=> [
			'zh-cn'			=> '海宁市',
			'en'			=> 'hai ning shi',
		],
		'330482'	=> [
			'zh-cn'			=> '平湖市',
			'en'			=> 'ping hu shi',
		],
		'330483'	=> [
			'zh-cn'			=> '桐乡市',
			'en'			=> 'tong xiang shi',
		],
		'330500'	=> [
			'zh-cn'			=> '湖州市',
			'en'			=> 'hu zhou shi',
		],
		'330502'	=> [
			'zh-cn'			=> '吴兴区',
			'en'			=> 'wu xing qu',
		],
		'330503'	=> [
			'zh-cn'			=> '南浔区',
			'en'			=> 'nan xun qu',
		],
		'330521'	=> [
			'zh-cn'			=> '德清县',
			'en'			=> 'de qing xian',
		],
		'330522'	=> [
			'zh-cn'			=> '长兴县',
			'en'			=> 'chang xing xian',
		],
		'330523'	=> [
			'zh-cn'			=> '安吉县',
			'en'			=> 'an ji xian',
		],
		'330600'	=> [
			'zh-cn'			=> '绍兴市',
			'en'			=> 'shao xing shi',
		],
		'330602'	=> [
			'zh-cn'			=> '越城区',
			'en'			=> 'yue cheng qu',
		],
		'330603'	=> [
			'zh-cn'			=> '柯桥区',
			'en'			=> 'ke qiao qu',
		],
		'330604'	=> [
			'zh-cn'			=> '上虞区',
			'en'			=> 'shang yu qu',
		],
		'330621'	=> [
			'zh-cn'			=> '绍兴县',
			'en'			=> 'shao xing xian',
		],
		'330622'	=> [
			'zh-cn'			=> '上虞县',
			'en'			=> 'shang yu xian',
		],
		'330624'	=> [
			'zh-cn'			=> '新昌县',
			'en'			=> 'xin chang xian',
		],
		'330625'	=> [
			'zh-cn'			=> '诸暨县',
			'en'			=> 'zhu ji xian',
		],
		'330681'	=> [
			'zh-cn'			=> '诸暨市',
			'en'			=> 'zhu ji shi',
		],
		'330682'	=> [
			'zh-cn'			=> '上虞市',
			'en'			=> 'shang yu shi',
		],
		'330683'	=> [
			'zh-cn'			=> '嵊州市',
			'en'			=> 'sheng zhou shi',
		],
		'330700'	=> [
			'zh-cn'			=> '金华市',
			'en'			=> 'jin hua shi',
		],
		'330701'	=> [
			'zh-cn'			=> '兰溪市',
			'en'			=> 'lan xi shi',
		],
		'330702'	=> [
			'zh-cn'			=> '婺城区',
			'en'			=> 'wu cheng qu',
		],
		'330703'	=> [
			'zh-cn'			=> '金东区',
			'en'			=> 'jin dong qu',
		],
		'330721'	=> [
			'zh-cn'			=> '金华县',
			'en'			=> 'jin hua xian',
		],
		'330722'	=> [
			'zh-cn'			=> '永康县',
			'en'			=> 'yong kang xian',
		],
		'330723'	=> [
			'zh-cn'			=> '武义县',
			'en'			=> 'wu yi xian',
		],
		'330724'	=> [
			'zh-cn'			=> '东阳县',
			'en'			=> 'dong yang xian',
		],
		'330725'	=> [
			'zh-cn'			=> '义乌县',
			'en'			=> 'yi wu xian',
		],
		'330726'	=> [
			'zh-cn'			=> '浦江县',
			'en'			=> 'pu jiang xian',
		],
		'330727'	=> [
			'zh-cn'			=> '磐安县',
			'en'			=> 'pan an xian',
		],
		'330781'	=> [
			'zh-cn'			=> '兰溪市',
			'en'			=> 'lan xi shi',
		],
		'330782'	=> [
			'zh-cn'			=> '义乌市',
			'en'			=> 'yi wu shi',
		],
		'330783'	=> [
			'zh-cn'			=> '东阳市',
			'en'			=> 'dong yang shi',
		],
		'330784'	=> [
			'zh-cn'			=> '永康市',
			'en'			=> 'yong kang shi',
		],
		'330800'	=> [
			'zh-cn'			=> '衢州市',
			'en'			=> 'qu zhou shi',
		],
		'330802'	=> [
			'zh-cn'			=> '柯城区',
			'en'			=> 'ke cheng qu',
		],
		'330803'	=> [
			'zh-cn'			=> '衢江区',
			'en'			=> 'qu jiang qu',
		],
		'330822'	=> [
			'zh-cn'			=> '常山县',
			'en'			=> 'chang shan xian',
		],
		'330823'	=> [
			'zh-cn'			=> '江山县',
			'en'			=> 'jiang shan xian',
		],
		'330824'	=> [
			'zh-cn'			=> '开化县',
			'en'			=> 'kai hua xian',
		],
		'330825'	=> [
			'zh-cn'			=> '龙游县',
			'en'			=> 'long you xian',
		],
		'330881'	=> [
			'zh-cn'			=> '江山市',
			'en'			=> 'jiang shan shi',
		],
		'330900'	=> [
			'zh-cn'			=> '舟山市',
			'en'			=> 'zhou shan shi',
		],
		'330902'	=> [
			'zh-cn'			=> '定海区',
			'en'			=> 'ding hai qu',
		],
		'330903'	=> [
			'zh-cn'			=> '普陀区',
			'en'			=> 'pu tuo qu',
		],
		'330921'	=> [
			'zh-cn'			=> '岱山县',
			'en'			=> 'dai shan xian',
		],
		'330922'	=> [
			'zh-cn'			=> '嵊泗县',
			'en'			=> 'sheng si xian',
		],
		'331000'	=> [
			'zh-cn'			=> '台州市',
			'en'			=> 'tai zhou shi',
		],
		'331002'	=> [
			'zh-cn'			=> '椒江区',
			'en'			=> 'jiao jiang qu',
		],
		'331003'	=> [
			'zh-cn'			=> '黄岩区',
			'en'			=> 'huang yan qu',
		],
		'331004'	=> [
			'zh-cn'			=> '路桥区',
			'en'			=> 'lu qiao qu',
		],
		'331021'	=> [
			'zh-cn'			=> '玉环县',
			'en'			=> 'yu huan xian',
		],
		'331022'	=> [
			'zh-cn'			=> '三门县',
			'en'			=> 'san men xian',
		],
		'331023'	=> [
			'zh-cn'			=> '天台县',
			'en'			=> 'tian tai xian',
		],
		'331024'	=> [
			'zh-cn'			=> '仙居县',
			'en'			=> 'xian ju xian',
		],
		'331081'	=> [
			'zh-cn'			=> '温岭市',
			'en'			=> 'wen ling shi',
		],
		'331082'	=> [
			'zh-cn'			=> '临海市',
			'en'			=> 'lin hai shi',
		],
		'331100'	=> [
			'zh-cn'			=> '丽水市',
			'en'			=> 'li shui shi',
		],
		'331102'	=> [
			'zh-cn'			=> '莲都区',
			'en'			=> 'lian du qu',
		],
		'331121'	=> [
			'zh-cn'			=> '青田县',
			'en'			=> 'qing tian xian',
		],
		'331122'	=> [
			'zh-cn'			=> '缙云县',
			'en'			=> 'jin yun xian',
		],
		'331123'	=> [
			'zh-cn'			=> '遂昌县',
			'en'			=> 'sui chang xian',
		],
		'331124'	=> [
			'zh-cn'			=> '松阳县',
			'en'			=> 'song yang xian',
		],
		'331125'	=> [
			'zh-cn'			=> '云和县',
			'en'			=> 'yun he xian',
		],
		'331126'	=> [
			'zh-cn'			=> '庆元县',
			'en'			=> 'qing yuan xian',
		],
		'331127'	=> [
			'zh-cn'			=> '景宁畲族自治县',
			'en'			=> 'jing ning she zu zi zhi xian',
		],
		'331181'	=> [
			'zh-cn'			=> '龙泉市',
			'en'			=> 'long quan shi',
		],
		'332100'	=> [
			'zh-cn'			=> '宁波地区',
			'en'			=> 'ning bo di qu',
		],
		'332121'	=> [
			'zh-cn'			=> '慈溪县',
			'en'			=> 'ci xi xian',
		],
		'332122'	=> [
			'zh-cn'			=> '镇海县',
			'en'			=> 'zhen hai xian',
		],
		'332123'	=> [
			'zh-cn'			=> '余姚县',
			'en'			=> 'yu yao xian',
		],
		'332124'	=> [
			'zh-cn'			=> '奉化县',
			'en'			=> 'feng hua xian',
		],
		'332125'	=> [
			'zh-cn'			=> '象山县',
			'en'			=> 'xiang shan xian',
		],
		'332126'	=> [
			'zh-cn'			=> '宁海县',
			'en'			=> 'ning hai xian',
		],
		'332200'	=> [
			'zh-cn'			=> '嘉兴地区',
			'en'			=> 'jia xing di qu',
		],
		'332201'	=> [
			'zh-cn'			=> '湖州市',
			'en'			=> 'hu zhou shi',
		],
		'332202'	=> [
			'zh-cn'			=> '嘉兴市',
			'en'			=> 'jia xing shi',
		],
		'332221'	=> [
			'zh-cn'			=> '嘉善县',
			'en'			=> 'jia shan xian',
		],
		'332222'	=> [
			'zh-cn'			=> '平湖县',
			'en'			=> 'ping hu xian',
		],
		'332223'	=> [
			'zh-cn'			=> '海宁县',
			'en'			=> 'hai ning xian',
		],
		'332224'	=> [
			'zh-cn'			=> '海盐县',
			'en'			=> 'hai yan xian',
		],
		'332225'	=> [
			'zh-cn'			=> '桐乡县',
			'en'			=> 'tong xiang xian',
		],
		'332226'	=> [
			'zh-cn'			=> '德清县',
			'en'			=> 'de qing xian',
		],
		'332227'	=> [
			'zh-cn'			=> '长兴县',
			'en'			=> 'chang xing xian',
		],
		'332228'	=> [
			'zh-cn'			=> '安吉县',
			'en'			=> 'an ji xian',
		],
		'332300'	=> [
			'zh-cn'			=> '绍兴地区',
			'en'			=> 'shao xing di qu',
		],
		'332301'	=> [
			'zh-cn'			=> '绍兴市',
			'en'			=> 'shao xing shi',
		],
		'332322'	=> [
			'zh-cn'			=> '上虞县',
			'en'			=> 'shang yu xian',
		],
		'332324'	=> [
			'zh-cn'			=> '新昌县',
			'en'			=> 'xin chang xian',
		],
		'332325'	=> [
			'zh-cn'			=> '诸暨县',
			'en'			=> 'zhu ji xian',
		],
		'332400'	=> [
			'zh-cn'			=> '金华地区',
			'en'			=> 'jin hua di qu',
		],
		'332401'	=> [
			'zh-cn'			=> '金华市',
			'en'			=> 'jin hua shi',
		],
		'332402'	=> [
			'zh-cn'			=> '衢州市',
			'en'			=> 'qu zhou shi',
		],
		'332421'	=> [
			'zh-cn'			=> '兰溪县',
			'en'			=> 'lan xi xian',
		],
		'332422'	=> [
			'zh-cn'			=> '永康县',
			'en'			=> 'yong kang xian',
		],
		'332423'	=> [
			'zh-cn'			=> '武义县',
			'en'			=> 'wu yi xian',
		],
		'332424'	=> [
			'zh-cn'			=> '东阳县',
			'en'			=> 'dong yang xian',
		],
		'332425'	=> [
			'zh-cn'			=> '义乌县',
			'en'			=> 'yi wu xian',
		],
		'332426'	=> [
			'zh-cn'			=> '浦江县',
			'en'			=> 'pu jiang xian',
		],
		'332427'	=> [
			'zh-cn'			=> '常山县',
			'en'			=> 'chang shan xian',
		],
		'332428'	=> [
			'zh-cn'			=> '江山县',
			'en'			=> 'jiang shan xian',
		],
		'332429'	=> [
			'zh-cn'			=> '开化县',
			'en'			=> 'kai hua xian',
		],
		'332430'	=> [
			'zh-cn'			=> '龙游县',
			'en'			=> 'long you xian',
		],
		'332431'	=> [
			'zh-cn'			=> '磐安县',
			'en'			=> 'pan an xian',
		],
		'332500'	=> [
			'zh-cn'			=> '丽水地区',
			'en'			=> 'li shui di qu',
		],
		'332501'	=> [
			'zh-cn'			=> '丽水市',
			'en'			=> 'li shui shi',
		],
		'332502'	=> [
			'zh-cn'			=> '龙泉市',
			'en'			=> 'long quan shi',
		],
		'332521'	=> [
			'zh-cn'			=> '丽水县',
			'en'			=> 'li shui xian',
		],
		'332522'	=> [
			'zh-cn'			=> '青田县',
			'en'			=> 'qing tian xian',
		],
		'332523'	=> [
			'zh-cn'			=> '云和县',
			'en'			=> 'yun he xian',
		],
		'332524'	=> [
			'zh-cn'			=> '龙泉县',
			'en'			=> 'long quan xian',
		],
		'332525'	=> [
			'zh-cn'			=> '庆元县',
			'en'			=> 'qing yuan xian',
		],
		'332526'	=> [
			'zh-cn'			=> '缙云县',
			'en'			=> 'jin yun xian',
		],
		'332527'	=> [
			'zh-cn'			=> '遂昌县',
			'en'			=> 'sui chang xian',
		],
		'332528'	=> [
			'zh-cn'			=> '松阳县',
			'en'			=> 'song yang xian',
		],
		'332529'	=> [
			'zh-cn'			=> '景宁畲族自治县',
			'en'			=> 'jing ning she zu zi zhi xian',
		],
		'332581'	=> [
			'zh-cn'			=> '龙泉市',
			'en'			=> 'long quan shi',
		],
		'332582'	=> [
			'zh-cn'			=> '丽水市',
			'en'			=> 'li shui shi',
		],
		'332600'	=> [
			'zh-cn'			=> '台州地区',
			'en'			=> 'tai zhou di qu',
		],
		'332601'	=> [
			'zh-cn'			=> '椒江市',
			'en'			=> 'jiao jiang shi',
		],
		'332602'	=> [
			'zh-cn'			=> '临海市',
			'en'			=> 'lin hai shi',
		],
		'332603'	=> [
			'zh-cn'			=> '黄岩市',
			'en'			=> 'huang yan shi',
		],
		'332621'	=> [
			'zh-cn'			=> '临海县',
			'en'			=> 'lin hai xian',
		],
		'332622'	=> [
			'zh-cn'			=> '黄岩县',
			'en'			=> 'huang yan xian',
		],
		'332623'	=> [
			'zh-cn'			=> '温岭县',
			'en'			=> 'wen ling xian',
		],
		'332624'	=> [
			'zh-cn'			=> '仙居县',
			'en'			=> 'xian ju xian',
		],
		'332625'	=> [
			'zh-cn'			=> '天台县',
			'en'			=> 'tian tai xian',
		],
		'332626'	=> [
			'zh-cn'			=> '三门县',
			'en'			=> 'san men xian',
		],
		'332627'	=> [
			'zh-cn'			=> '玉环县',
			'en'			=> 'yu huan xian',
		],
		'332700'	=> [
			'zh-cn'			=> '舟山地区',
			'en'			=> 'zhou shan di qu',
		],
		'332721'	=> [
			'zh-cn'			=> '定海县',
			'en'			=> 'ding hai xian',
		],
		'332722'	=> [
			'zh-cn'			=> '普陀县',
			'en'			=> 'pu tuo xian',
		],
		'332723'	=> [
			'zh-cn'			=> '岱山县',
			'en'			=> 'dai shan xian',
		],
		'332724'	=> [
			'zh-cn'			=> '嵊泗县',
			'en'			=> 'sheng si xian',
		],
		'339001'	=> [
			'zh-cn'			=> '余姚市',
			'en'			=> 'yu yao shi',
		],
		'339002'	=> [
			'zh-cn'			=> '海宁市',
			'en'			=> 'hai ning shi',
		],
		'339003'	=> [
			'zh-cn'			=> '兰溪市',
			'en'			=> 'lan xi shi',
		],
		'339004'	=> [
			'zh-cn'			=> '瑞安市',
			'en'			=> 'rui an shi',
		],
		'339005'	=> [
			'zh-cn'			=> '萧山市',
			'en'			=> 'xiao shan shi',
		],
		'339006'	=> [
			'zh-cn'			=> '江山市',
			'en'			=> 'jiang shan shi',
		],
		'339007'	=> [
			'zh-cn'			=> '义乌市',
			'en'			=> 'yi wu shi',
		],
		'339008'	=> [
			'zh-cn'			=> '东阳市',
			'en'			=> 'dong yang shi',
		],
		'339009'	=> [
			'zh-cn'			=> '慈溪市',
			'en'			=> 'ci xi shi',
		],
		'339010'	=> [
			'zh-cn'			=> '奉化市',
			'en'			=> 'feng hua shi',
		],
		'339011'	=> [
			'zh-cn'			=> '诸暨市',
			'en'			=> 'zhu ji shi',
		],
		'339012'	=> [
			'zh-cn'			=> '平湖市',
			'en'			=> 'ping hu shi',
		],
		'339013'	=> [
			'zh-cn'			=> '建德市',
			'en'			=> 'jian de shi',
		],
		'339014'	=> [
			'zh-cn'			=> '永康市',
			'en'			=> 'yong kang shi',
		],
		'339015'	=> [
			'zh-cn'			=> '上虞市',
			'en'			=> 'shang yu shi',
		],
		'339016'	=> [
			'zh-cn'			=> '桐乡市',
			'en'			=> 'tong xiang shi',
		],
		'339017'	=> [
			'zh-cn'			=> '乐清市',
			'en'			=> 'yue qing shi',
		],
		'339018'	=> [
			'zh-cn'			=> '临海市',
			'en'			=> 'lin hai shi',
		],
		'339019'	=> [
			'zh-cn'			=> '富阳市',
			'en'			=> 'fu yang shi',
		],
		'339020'	=> [
			'zh-cn'			=> '温岭市',
			'en'			=> 'wen ling shi',
		],
		'339021'	=> [
			'zh-cn'			=> '余杭市',
			'en'			=> 'yu hang shi',
		],
		'340000'	=> [
			'zh-cn'			=> '安徽省',
			'en'			=> 'an hui sheng',
		],
		'340100'	=> [
			'zh-cn'			=> '合肥市',
			'en'			=> 'he fei shi',
		],
		'340102'	=> [
			'zh-cn'			=> '瑶海区',
			'en'			=> 'yao hai qu',
		],
		'340103'	=> [
			'zh-cn'			=> '庐阳区',
			'en'			=> 'lu yang qu',
		],
		'340104'	=> [
			'zh-cn'			=> '蜀山区',
			'en'			=> 'shu shan qu',
		],
		'340111'	=> [
			'zh-cn'			=> '包河区',
			'en'			=> 'bao he qu',
		],
		'340121'	=> [
			'zh-cn'			=> '长丰县',
			'en'			=> 'chang feng xian',
		],
		'340122'	=> [
			'zh-cn'			=> '肥东县',
			'en'			=> 'fei dong xian',
		],
		'340123'	=> [
			'zh-cn'			=> '肥西县',
			'en'			=> 'fei xi xian',
		],
		'340124'	=> [
			'zh-cn'			=> '庐江县',
			'en'			=> 'lu jiang xian',
		],
		'340181'	=> [
			'zh-cn'			=> '巢湖市',
			'en'			=> 'chao hu shi',
		],
		'340200'	=> [
			'zh-cn'			=> '芜湖市',
			'en'			=> 'wu hu shi',
		],
		'340202'	=> [
			'zh-cn'			=> '镜湖区',
			'en'			=> 'jing hu qu',
		],
		'340203'	=> [
			'zh-cn'			=> '弋江区',
			'en'			=> 'yi jiang qu',
		],
		'340204'	=> [
			'zh-cn'			=> '新芜区',
			'en'			=> 'xin wu qu',
		],
		'340205'	=> [
			'zh-cn'			=> '鸠江区',
			'en'			=> 'jiu jiang qu',
		],
		'340206'	=> [
			'zh-cn'			=> '四褐山区',
			'en'			=> 'si he shan qu',
		],
		'340207'	=> [
			'zh-cn'			=> '鸠江区',
			'en'			=> 'jiu jiang qu',
		],
		'340208'	=> [
			'zh-cn'			=> '三山区',
			'en'			=> 'san shan qu',
		],
		'340221'	=> [
			'zh-cn'			=> '芜湖县',
			'en'			=> 'wu hu xian',
		],
		'340222'	=> [
			'zh-cn'			=> '繁昌县',
			'en'			=> 'fan chang xian',
		],
		'340223'	=> [
			'zh-cn'			=> '南陵县',
			'en'			=> 'nan ling xian',
		],
		'340224'	=> [
			'zh-cn'			=> '青阳县',
			'en'			=> 'qing yang xian',
		],
		'340225'	=> [
			'zh-cn'			=> '无为县',
			'en'			=> 'wu wei xian',
		],
		'340300'	=> [
			'zh-cn'			=> '蚌埠市',
			'en'			=> 'beng bu shi',
		],
		'340302'	=> [
			'zh-cn'			=> '龙子湖区',
			'en'			=> 'long zi hu qu',
		],
		'340303'	=> [
			'zh-cn'			=> '蚌山区',
			'en'			=> 'beng shan qu',
		],
		'340304'	=> [
			'zh-cn'			=> '禹会区',
			'en'			=> 'yu hui qu',
		],
		'340311'	=> [
			'zh-cn'			=> '淮上区',
			'en'			=> 'huai shang qu',
		],
		'340321'	=> [
			'zh-cn'			=> '怀远县',
			'en'			=> 'huai yuan xian',
		],
		'340322'	=> [
			'zh-cn'			=> '五河县',
			'en'			=> 'wu he xian',
		],
		'340323'	=> [
			'zh-cn'			=> '固镇县',
			'en'			=> 'gu zhen xian',
		],
		'340400'	=> [
			'zh-cn'			=> '淮南市',
			'en'			=> 'huai nan shi',
		],
		'340402'	=> [
			'zh-cn'			=> '大通区',
			'en'			=> 'da tong qu',
		],
		'340403'	=> [
			'zh-cn'			=> '田家庵区',
			'en'			=> 'tian jia an qu',
		],
		'340404'	=> [
			'zh-cn'			=> '谢家集区',
			'en'			=> 'xie jia ji qu',
		],
		'340405'	=> [
			'zh-cn'			=> '八公山区',
			'en'			=> 'ba gong shan qu',
		],
		'340406'	=> [
			'zh-cn'			=> '潘集区',
			'en'			=> 'pan ji qu',
		],
		'340421'	=> [
			'zh-cn'			=> '凤台县',
			'en'			=> 'feng tai xian',
		],
		'340500'	=> [
			'zh-cn'			=> '马鞍山市',
			'en'			=> 'ma an shan shi',
		],
		'340502'	=> [
			'zh-cn'			=> '金家庄区',
			'en'			=> 'jin jia zhuang qu',
		],
		'340503'	=> [
			'zh-cn'			=> '花山区',
			'en'			=> 'hua shan qu',
		],
		'340504'	=> [
			'zh-cn'			=> '雨山区',
			'en'			=> 'yu shan qu',
		],
		'340505'	=> [
			'zh-cn'			=> '向山区',
			'en'			=> 'xiang shan qu',
		],
		'340506'	=> [
			'zh-cn'			=> '博望区',
			'en'			=> 'bo wang qu',
		],
		'340521'	=> [
			'zh-cn'			=> '当涂县',
			'en'			=> 'dang tu xian',
		],
		'340522'	=> [
			'zh-cn'			=> '含山县',
			'en'			=> 'han shan xian',
		],
		'340600'	=> [
			'zh-cn'			=> '淮北市',
			'en'			=> 'huai bei shi',
		],
		'340602'	=> [
			'zh-cn'			=> '杜集区',
			'en'			=> 'du ji qu',
		],
		'340603'	=> [
			'zh-cn'			=> '相山区',
			'en'			=> 'xiang shan qu',
		],
		'340604'	=> [
			'zh-cn'			=> '烈山区',
			'en'			=> 'lie shan qu',
		],
		'340621'	=> [
			'zh-cn'			=> '濉溪县',
			'en'			=> 'sui xi xian',
		],
		'340700'	=> [
			'zh-cn'			=> '铜陵市',
			'en'			=> 'tong ling shi',
		],
		'340702'	=> [
			'zh-cn'			=> '铜官山区',
			'en'			=> 'tong guan shan qu',
		],
		'340703'	=> [
			'zh-cn'			=> '狮子山区',
			'en'			=> 'shi zi shan qu',
		],
		'340704'	=> [
			'zh-cn'			=> '铜山区',
			'en'			=> 'tong shan qu',
		],
		'340705'	=> [
			'zh-cn'			=> '铜官区',
			'en'			=> 'tong guan qu',
		],
		'340706'	=> [
			'zh-cn'			=> '义安区',
			'en'			=> 'yi an qu',
		],
		'340721'	=> [
			'zh-cn'			=> '铜陵县',
			'en'			=> 'tong ling xian',
		],
		'340722'	=> [
			'zh-cn'			=> '枞阳县',
			'en'			=> 'zong yang xian',
		],
		'340800'	=> [
			'zh-cn'			=> '安庆市',
			'en'			=> 'an qing shi',
		],
		'340802'	=> [
			'zh-cn'			=> '迎江区',
			'en'			=> 'ying jiang qu',
		],
		'340803'	=> [
			'zh-cn'			=> '大观区',
			'en'			=> 'da guan qu',
		],
		'340811'	=> [
			'zh-cn'			=> '宜秀区',
			'en'			=> 'yi xiu qu',
		],
		'340821'	=> [
			'zh-cn'			=> '桐城县',
			'en'			=> 'tong cheng xian',
		],
		'340822'	=> [
			'zh-cn'			=> '怀宁县',
			'en'			=> 'huai ning xian',
		],
		'340823'	=> [
			'zh-cn'			=> '枞阳县',
			'en'			=> 'zong yang xian',
		],
		'340824'	=> [
			'zh-cn'			=> '潜山县',
			'en'			=> 'qian shan xian',
		],
		'340825'	=> [
			'zh-cn'			=> '太湖县',
			'en'			=> 'tai hu xian',
		],
		'340826'	=> [
			'zh-cn'			=> '宿松县',
			'en'			=> 'su song xian',
		],
		'340827'	=> [
			'zh-cn'			=> '望江县',
			'en'			=> 'wang jiang xian',
		],
		'340828'	=> [
			'zh-cn'			=> '岳西县',
			'en'			=> 'yue xi xian',
		],
		'340881'	=> [
			'zh-cn'			=> '桐城市',
			'en'			=> 'tong cheng shi',
		],
		'340901'	=> [
			'zh-cn'			=> '黄山市',
			'en'			=> 'huang shan shi',
		],
		'341000'	=> [
			'zh-cn'			=> '黄山市',
			'en'			=> 'huang shan shi',
		],
		'341002'	=> [
			'zh-cn'			=> '屯溪区',
			'en'			=> 'tun xi qu',
		],
		'341003'	=> [
			'zh-cn'			=> '黄山区',
			'en'			=> 'huang shan qu',
		],
		'341004'	=> [
			'zh-cn'			=> '徽州区',
			'en'			=> 'hui zhou qu',
		],
		'341022'	=> [
			'zh-cn'			=> '休宁县',
			'en'			=> 'xiu ning xian',
		],
		'341024'	=> [
			'zh-cn'			=> '祁门县',
			'en'			=> 'qi men xian',
		],
		'341100'	=> [
			'zh-cn'			=> '滁州市',
			'en'			=> 'chu zhou shi',
		],
		'341102'	=> [
			'zh-cn'			=> '琅琊区',
			'en'			=> 'lang ya qu',
		],
		'341103'	=> [
			'zh-cn'			=> '南谯区',
			'en'			=> 'nan qiao qu',
		],
		'341121'	=> [
			'zh-cn'			=> '天长县',
			'en'			=> 'tian chang xian',
		],
		'341122'	=> [
			'zh-cn'			=> '来安县',
			'en'			=> 'lai an xian',
		],
		'341124'	=> [
			'zh-cn'			=> '全椒县',
			'en'			=> 'quan jiao xian',
		],
		'341125'	=> [
			'zh-cn'			=> '定远县',
			'en'			=> 'ding yuan xian',
		],
		'341126'	=> [
			'zh-cn'			=> '凤阳县',
			'en'			=> 'feng yang xian',
		],
		'341127'	=> [
			'zh-cn'			=> '嘉山县',
			'en'			=> 'jia shan xian',
		],
		'341181'	=> [
			'zh-cn'			=> '天长市',
			'en'			=> 'tian chang shi',
		],
		'341182'	=> [
			'zh-cn'			=> '明光市',
			'en'			=> 'ming guang shi',
		],
		'341200'	=> [
			'zh-cn'			=> '阜阳市',
			'en'			=> 'fu yang shi',
		],
		'341202'	=> [
			'zh-cn'			=> '颍州区',
			'en'			=> 'ying zhou qu',
		],
		'341203'	=> [
			'zh-cn'			=> '颍东区',
			'en'			=> 'ying dong qu',
		],
		'341204'	=> [
			'zh-cn'			=> '颍泉区',
			'en'			=> 'ying quan qu',
		],
		'341221'	=> [
			'zh-cn'			=> '临泉县',
			'en'			=> 'lin quan xian',
		],
		'341222'	=> [
			'zh-cn'			=> '太和县',
			'en'			=> 'tai he xian',
		],
		'341223'	=> [
			'zh-cn'			=> '涡阳县',
			'en'			=> 'guo yang xian',
		],
		'341224'	=> [
			'zh-cn'			=> '蒙城县',
			'en'			=> 'meng cheng xian',
		],
		'341225'	=> [
			'zh-cn'			=> '阜南县',
			'en'			=> 'fu nan xian',
		],
		'341226'	=> [
			'zh-cn'			=> '颍上县',
			'en'			=> 'ying shang xian',
		],
		'341227'	=> [
			'zh-cn'			=> '利辛县',
			'en'			=> 'li xin xian',
		],
		'341281'	=> [
			'zh-cn'			=> '亳州市',
			'en'			=> 'bo zhou shi',
		],
		'341282'	=> [
			'zh-cn'			=> '界首市',
			'en'			=> 'jie shou shi',
		],
		'341300'	=> [
			'zh-cn'			=> '宿州市',
			'en'			=> 'su zhou shi',
		],
		'341302'	=> [
			'zh-cn'			=> '埇桥区',
			'en'			=> 'yong qiao qu',
		],
		'341321'	=> [
			'zh-cn'			=> '砀山县',
			'en'			=> 'dang shan xian',
		],
		'341323'	=> [
			'zh-cn'			=> '灵璧县',
			'en'			=> 'ling bi xian',
		],
		'341400'	=> [
			'zh-cn'			=> '巢湖市',
			'en'			=> 'chao hu shi',
		],
		'341402'	=> [
			'zh-cn'			=> '居巢区',
			'en'			=> 'ju chao qu',
		],
		'341421'	=> [
			'zh-cn'			=> '庐江县',
			'en'			=> 'lu jiang xian',
		],
		'341422'	=> [
			'zh-cn'			=> '无为县',
			'en'			=> 'wu wei xian',
		],
		'341423'	=> [
			'zh-cn'			=> '含山县',
			'en'			=> 'han shan xian',
		],
		'341500'	=> [
			'zh-cn'			=> '六安市',
			'en'			=> 'lu an shi',
		],
		'341502'	=> [
			'zh-cn'			=> '金安区',
			'en'			=> 'jin an qu',
		],
		'341503'	=> [
			'zh-cn'			=> '裕安区',
			'en'			=> 'yu an qu',
		],
		'341504'	=> [
			'zh-cn'			=> '叶集区',
			'en'			=> 'ye ji qu',
		],
		'341522'	=> [
			'zh-cn'			=> '霍邱县',
			'en'			=> 'huo qiu xian',
		],
		'341523'	=> [
			'zh-cn'			=> '舒城县',
			'en'			=> 'shu cheng xian',
		],
		'341524'	=> [
			'zh-cn'			=> '金寨县',
			'en'			=> 'jin zhai xian',
		],
		'341525'	=> [
			'zh-cn'			=> '霍山县',
			'en'			=> 'huo shan xian',
		],
		'341600'	=> [
			'zh-cn'			=> '亳州市',
			'en'			=> 'bo zhou shi',
		],
		'341602'	=> [
			'zh-cn'			=> '谯城区',
			'en'			=> 'qiao cheng qu',
		],
		'341621'	=> [
			'zh-cn'			=> '涡阳县',
			'en'			=> 'guo yang xian',
		],
		'341622'	=> [
			'zh-cn'			=> '蒙城县',
			'en'			=> 'meng cheng xian',
		],
		'341623'	=> [
			'zh-cn'			=> '利辛县',
			'en'			=> 'li xin xian',
		],
		'341700'	=> [
			'zh-cn'			=> '池州市',
			'en'			=> 'chi zhou shi',
		],
		'341702'	=> [
			'zh-cn'			=> '贵池区',
			'en'			=> 'gui chi qu',
		],
		'341721'	=> [
			'zh-cn'			=> '东至县',
			'en'			=> 'dong zhi xian',
		],
		'341722'	=> [
			'zh-cn'			=> '石台县',
			'en'			=> 'shi tai xian',
		],
		'341723'	=> [
			'zh-cn'			=> '青阳县',
			'en'			=> 'qing yang xian',
		],
		'341800'	=> [
			'zh-cn'			=> '宣城市',
			'en'			=> 'xuan cheng shi',
		],
		'341802'	=> [
			'zh-cn'			=> '宣州区',
			'en'			=> 'xuan zhou qu',
		],
		'341821'	=> [
			'zh-cn'			=> '郎溪县',
			'en'			=> 'lang xi xian',
		],
		'341822'	=> [
			'zh-cn'			=> '广德县',
			'en'			=> 'guang de xian',
		],
		'341824'	=> [
			'zh-cn'			=> '绩溪县',
			'en'			=> 'ji xi xian',
		],
		'341825'	=> [
			'zh-cn'			=> '旌德县',
			'en'			=> 'jing de xian',
		],
		'341881'	=> [
			'zh-cn'			=> '宁国市',
			'en'			=> 'ning guo shi',
		],
		'342100'	=> [
			'zh-cn'			=> '阜阳地区',
			'en'			=> 'fu yang di qu',
		],
		'342101'	=> [
			'zh-cn'			=> '阜阳市',
			'en'			=> 'fu yang shi',
		],
		'342102'	=> [
			'zh-cn'			=> '亳州市',
			'en'			=> 'bo zhou shi',
		],
		'342103'	=> [
			'zh-cn'			=> '界首市',
			'en'			=> 'jie shou shi',
		],
		'342121'	=> [
			'zh-cn'			=> '阜阳县',
			'en'			=> 'fu yang xian',
		],
		'342122'	=> [
			'zh-cn'			=> '临泉县',
			'en'			=> 'lin quan xian',
		],
		'342123'	=> [
			'zh-cn'			=> '太和县',
			'en'			=> 'tai he xian',
		],
		'342124'	=> [
			'zh-cn'			=> '涡阳县',
			'en'			=> 'guo yang xian',
		],
		'342125'	=> [
			'zh-cn'			=> '蒙城县',
			'en'			=> 'meng cheng xian',
		],
		'342127'	=> [
			'zh-cn'			=> '阜南县',
			'en'			=> 'fu nan xian',
		],
		'342128'	=> [
			'zh-cn'			=> '颍上县',
			'en'			=> 'ying shang xian',
		],
		'342129'	=> [
			'zh-cn'			=> '界首县',
			'en'			=> 'jie shou xian',
		],
		'342130'	=> [
			'zh-cn'			=> '利辛县',
			'en'			=> 'li xin xian',
		],
		'342200'	=> [
			'zh-cn'			=> '宿县地区',
			'en'			=> 'su xian di qu',
		],
		'342201'	=> [
			'zh-cn'			=> '宿州市',
			'en'			=> 'su zhou shi',
		],
		'342221'	=> [
			'zh-cn'			=> '砀山县',
			'en'			=> 'dang shan xian',
		],
		'342224'	=> [
			'zh-cn'			=> '灵璧县',
			'en'			=> 'ling bi xian',
		],
		'342300'	=> [
			'zh-cn'			=> '滁县地区',
			'en'			=> 'chu xian di qu',
		],
		'342301'	=> [
			'zh-cn'			=> '滁州市',
			'en'			=> 'chu zhou shi',
		],
		'342321'	=> [
			'zh-cn'			=> '天长县',
			'en'			=> 'tian chang xian',
		],
		'342322'	=> [
			'zh-cn'			=> '来安县',
			'en'			=> 'lai an xian',
		],
		'342324'	=> [
			'zh-cn'			=> '全椒县',
			'en'			=> 'quan jiao xian',
		],
		'342325'	=> [
			'zh-cn'			=> '定远县',
			'en'			=> 'ding yuan xian',
		],
		'342326'	=> [
			'zh-cn'			=> '凤阳县',
			'en'			=> 'feng yang xian',
		],
		'342327'	=> [
			'zh-cn'			=> '嘉山县',
			'en'			=> 'jia shan xian',
		],
		'342400'	=> [
			'zh-cn'			=> '六安地区',
			'en'			=> 'lu an di qu',
		],
		'342401'	=> [
			'zh-cn'			=> '六安市',
			'en'			=> 'lu an shi',
		],
		'342421'	=> [
			'zh-cn'			=> '六安县',
			'en'			=> 'lu an xian',
		],
		'342423'	=> [
			'zh-cn'			=> '霍邱县',
			'en'			=> 'huo qiu xian',
		],
		'342424'	=> [
			'zh-cn'			=> '肥西县',
			'en'			=> 'fei xi xian',
		],
		'342425'	=> [
			'zh-cn'			=> '舒城县',
			'en'			=> 'shu cheng xian',
		],
		'342426'	=> [
			'zh-cn'			=> '金寨县',
			'en'			=> 'jin zhai xian',
		],
		'342427'	=> [
			'zh-cn'			=> '霍山县',
			'en'			=> 'huo shan xian',
		],
		'342500'	=> [
			'zh-cn'			=> '宣城地区',
			'en'			=> 'xuan cheng di qu',
		],
		'342501'	=> [
			'zh-cn'			=> '宣城市',
			'en'			=> 'xuan cheng shi',
		],
		'342502'	=> [
			'zh-cn'			=> '宁国市',
			'en'			=> 'ning guo shi',
		],
		'342521'	=> [
			'zh-cn'			=> '宣城县',
			'en'			=> 'xuan cheng xian',
		],
		'342522'	=> [
			'zh-cn'			=> '郎溪县',
			'en'			=> 'lang xi xian',
		],
		'342523'	=> [
			'zh-cn'			=> '广德县',
			'en'			=> 'guang de xian',
		],
		'342524'	=> [
			'zh-cn'			=> '宁国县',
			'en'			=> 'ning guo xian',
		],
		'342525'	=> [
			'zh-cn'			=> '当涂县',
			'en'			=> 'dang tu xian',
		],
		'342526'	=> [
			'zh-cn'			=> '繁昌县',
			'en'			=> 'fan chang xian',
		],
		'342527'	=> [
			'zh-cn'			=> '南陵县',
			'en'			=> 'nan ling xian',
		],
		'342528'	=> [
			'zh-cn'			=> '青阳县',
			'en'			=> 'qing yang xian',
		],
		'342530'	=> [
			'zh-cn'			=> '旌德县',
			'en'			=> 'jing de xian',
		],
		'342531'	=> [
			'zh-cn'			=> '绩溪县',
			'en'			=> 'ji xi xian',
		],
		'342600'	=> [
			'zh-cn'			=> '巢湖地区',
			'en'			=> 'chao hu di qu',
		],
		'342601'	=> [
			'zh-cn'			=> '巢湖市',
			'en'			=> 'chao hu shi',
		],
		'342622'	=> [
			'zh-cn'			=> '庐江县',
			'en'			=> 'lu jiang xian',
		],
		'342623'	=> [
			'zh-cn'			=> '无为县',
			'en'			=> 'wu wei xian',
		],
		'342624'	=> [
			'zh-cn'			=> '肥东县',
			'en'			=> 'fei dong xian',
		],
		'342625'	=> [
			'zh-cn'			=> '含山县',
			'en'			=> 'han shan xian',
		],
		'342700'	=> [
			'zh-cn'			=> '徽州地区',
			'en'			=> 'hui zhou di qu',
		],
		'342701'	=> [
			'zh-cn'			=> '屯溪市',
			'en'			=> 'tun xi shi',
		],
		'342721'	=> [
			'zh-cn'			=> '绩溪县',
			'en'			=> 'ji xi xian',
		],
		'342722'	=> [
			'zh-cn'			=> '旌德县',
			'en'			=> 'jing de xian',
		],
		'342724'	=> [
			'zh-cn'			=> '休宁县',
			'en'			=> 'xiu ning xian',
		],
		'342726'	=> [
			'zh-cn'			=> '祁门县',
			'en'			=> 'qi men xian',
		],
		'342727'	=> [
			'zh-cn'			=> '太平县',
			'en'			=> 'tai ping xian',
		],
		'342728'	=> [
			'zh-cn'			=> '石台县',
			'en'			=> 'shi tai xian',
		],
		'342800'	=> [
			'zh-cn'			=> '安庆地区',
			'en'			=> 'an qing di qu',
		],
		'342821'	=> [
			'zh-cn'			=> '怀宁县',
			'en'			=> 'huai ning xian',
		],
		'342822'	=> [
			'zh-cn'			=> '桐城县',
			'en'			=> 'tong cheng xian',
		],
		'342823'	=> [
			'zh-cn'			=> '枞阳县',
			'en'			=> 'zong yang xian',
		],
		'342824'	=> [
			'zh-cn'			=> '潜山县',
			'en'			=> 'qian shan xian',
		],
		'342825'	=> [
			'zh-cn'			=> '太湖县',
			'en'			=> 'tai hu xian',
		],
		'342826'	=> [
			'zh-cn'			=> '宿松县',
			'en'			=> 'su song xian',
		],
		'342827'	=> [
			'zh-cn'			=> '望江县',
			'en'			=> 'wang jiang xian',
		],
		'342828'	=> [
			'zh-cn'			=> '岳西县',
			'en'			=> 'yue xi xian',
		],
		'342829'	=> [
			'zh-cn'			=> '东至县',
			'en'			=> 'dong zhi xian',
		],
		'342830'	=> [
			'zh-cn'			=> '贵池县',
			'en'			=> 'gui chi xian',
		],
		'342831'	=> [
			'zh-cn'			=> '石台县',
			'en'			=> 'shi tai xian',
		],
		'342900'	=> [
			'zh-cn'			=> '池州地区',
			'en'			=> 'chi zhou di qu',
		],
		'342901'	=> [
			'zh-cn'			=> '贵池市',
			'en'			=> 'gui chi shi',
		],
		'342921'	=> [
			'zh-cn'			=> '东至县',
			'en'			=> 'dong zhi xian',
		],
		'342922'	=> [
			'zh-cn'			=> '石台县',
			'en'			=> 'shi tai xian',
		],
		'342923'	=> [
			'zh-cn'			=> '青阳县',
			'en'			=> 'qing yang xian',
		],
		'349001'	=> [
			'zh-cn'			=> '天长市',
			'en'			=> 'tian chang shi',
		],
		'349002'	=> [
			'zh-cn'			=> '明光市',
			'en'			=> 'ming guang shi',
		],
		'350000'	=> [
			'zh-cn'			=> '福建省',
			'en'			=> 'fu jian sheng',
		],
		'350100'	=> [
			'zh-cn'			=> '福州市',
			'en'			=> 'fu zhou shi',
		],
		'350102'	=> [
			'zh-cn'			=> '鼓楼区',
			'en'			=> 'gu lou qu',
		],
		'350103'	=> [
			'zh-cn'			=> '台江区',
			'en'			=> 'tai jiang qu',
		],
		'350104'	=> [
			'zh-cn'			=> '仓山区',
			'en'			=> 'cang shan qu',
		],
		'350105'	=> [
			'zh-cn'			=> '马尾区',
			'en'			=> 'ma yi qu',
		],
		'350111'	=> [
			'zh-cn'			=> '晋安区',
			'en'			=> 'jin an qu',
		],
		'350121'	=> [
			'zh-cn'			=> '闽侯县',
			'en'			=> 'min hou xian',
		],
		'350122'	=> [
			'zh-cn'			=> '连江县',
			'en'			=> 'lian jiang xian',
		],
		'350123'	=> [
			'zh-cn'			=> '罗源县',
			'en'			=> 'luo yuan xian',
		],
		'350124'	=> [
			'zh-cn'			=> '闽清县',
			'en'			=> 'min qing xian',
		],
		'350125'	=> [
			'zh-cn'			=> '永泰县',
			'en'			=> 'yong tai xian',
		],
		'350126'	=> [
			'zh-cn'			=> '长乐县',
			'en'			=> 'chang le xian',
		],
		'350127'	=> [
			'zh-cn'			=> '福清县',
			'en'			=> 'fu qing xian',
		],
		'350128'	=> [
			'zh-cn'			=> '平潭县',
			'en'			=> 'ping tan xian',
		],
		'350181'	=> [
			'zh-cn'			=> '福清市',
			'en'			=> 'fu qing shi',
		],
		'350182'	=> [
			'zh-cn'			=> '长乐市',
			'en'			=> 'chang le shi',
		],
		'350200'	=> [
			'zh-cn'			=> '厦门市',
			'en'			=> 'xia men shi',
		],
		'350202'	=> [
			'zh-cn'			=> '鼓浪屿区',
			'en'			=> 'gu lang yu qu',
		],
		'350203'	=> [
			'zh-cn'			=> '思明区',
			'en'			=> 'si ming qu',
		],
		'350204'	=> [
			'zh-cn'			=> '开元区',
			'en'			=> 'kai yuan qu',
		],
		'350205'	=> [
			'zh-cn'			=> '海沧区',
			'en'			=> 'hai cang qu',
		],
		'350206'	=> [
			'zh-cn'			=> '湖里区',
			'en'			=> 'hu li qu',
		],
		'350211'	=> [
			'zh-cn'			=> '集美区',
			'en'			=> 'ji mei qu',
		],
		'350212'	=> [
			'zh-cn'			=> '同安区',
			'en'			=> 'tong an qu',
		],
		'350213'	=> [
			'zh-cn'			=> '翔安区',
			'en'			=> 'xiang an qu',
		],
		'350221'	=> [
			'zh-cn'			=> '同安县',
			'en'			=> 'tong an xian',
		],
		'350300'	=> [
			'zh-cn'			=> '莆田市',
			'en'			=> 'pu tian shi',
		],
		'350302'	=> [
			'zh-cn'			=> '城厢区',
			'en'			=> 'cheng xiang qu',
		],
		'350303'	=> [
			'zh-cn'			=> '涵江区',
			'en'			=> 'han jiang qu',
		],
		'350304'	=> [
			'zh-cn'			=> '荔城区',
			'en'			=> 'li cheng qu',
		],
		'350305'	=> [
			'zh-cn'			=> '秀屿区',
			'en'			=> 'xiu yu qu',
		],
		'350321'	=> [
			'zh-cn'			=> '莆田县',
			'en'			=> 'pu tian xian',
		],
		'350322'	=> [
			'zh-cn'			=> '仙游县',
			'en'			=> 'xian you xian',
		],
		'350400'	=> [
			'zh-cn'			=> '三明市',
			'en'			=> 'san ming shi',
		],
		'350402'	=> [
			'zh-cn'			=> '梅列区',
			'en'			=> 'mei lie qu',
		],
		'350403'	=> [
			'zh-cn'			=> '三元区',
			'en'			=> 'san yuan qu',
		],
		'350420'	=> [
			'zh-cn'			=> '永安市',
			'en'			=> 'yong an shi',
		],
		'350421'	=> [
			'zh-cn'			=> '明溪县',
			'en'			=> 'ming xi xian',
		],
		'350422'	=> [
			'zh-cn'			=> '永安县',
			'en'			=> 'yong an xian',
		],
		'350423'	=> [
			'zh-cn'			=> '清流县',
			'en'			=> 'qing liu xian',
		],
		'350424'	=> [
			'zh-cn'			=> '宁化县',
			'en'			=> 'ning hua xian',
		],
		'350425'	=> [
			'zh-cn'			=> '大田县',
			'en'			=> 'da tian xian',
		],
		'350426'	=> [
			'zh-cn'			=> '尤溪县',
			'en'			=> 'you xi xian',
		],
		'350428'	=> [
			'zh-cn'			=> '将乐县',
			'en'			=> 'jiang le xian',
		],
		'350429'	=> [
			'zh-cn'			=> '泰宁县',
			'en'			=> 'tai ning xian',
		],
		'350430'	=> [
			'zh-cn'			=> '建宁县',
			'en'			=> 'jian ning xian',
		],
		'350481'	=> [
			'zh-cn'			=> '永安市',
			'en'			=> 'yong an shi',
		],
		'350500'	=> [
			'zh-cn'			=> '泉州市',
			'en'			=> 'quan zhou shi',
		],
		'350501'	=> [
			'zh-cn'			=> '永安市',
			'en'			=> 'yong an shi',
		],
		'350502'	=> [
			'zh-cn'			=> '鲤城区',
			'en'			=> 'li cheng qu',
		],
		'350503'	=> [
			'zh-cn'			=> '丰泽区',
			'en'			=> 'feng ze qu',
		],
		'350504'	=> [
			'zh-cn'			=> '洛江区',
			'en'			=> 'luo jiang qu',
		],
		'350505'	=> [
			'zh-cn'			=> '泉港区',
			'en'			=> 'quan gang qu',
		],
		'350521'	=> [
			'zh-cn'			=> '惠安县',
			'en'			=> 'hui an xian',
		],
		'350522'	=> [
			'zh-cn'			=> '晋江县',
			'en'			=> 'jin jiang xian',
		],
		'350523'	=> [
			'zh-cn'			=> '南安县',
			'en'			=> 'nan an xian',
		],
		'350524'	=> [
			'zh-cn'			=> '安溪县',
			'en'			=> 'an xi xian',
		],
		'350525'	=> [
			'zh-cn'			=> '永春县',
			'en'			=> 'yong chun xian',
		],
		'350526'	=> [
			'zh-cn'			=> '德化县',
			'en'			=> 'de hua xian',
		],
		'350527'	=> [
			'zh-cn'			=> '金门县',
			'en'			=> 'jin men xian',
		],
		'350581'	=> [
			'zh-cn'			=> '石狮市',
			'en'			=> 'shi shi shi',
		],
		'350582'	=> [
			'zh-cn'			=> '晋江市',
			'en'			=> 'jin jiang shi',
		],
		'350583'	=> [
			'zh-cn'			=> '南安市',
			'en'			=> 'nan an shi',
		],
		'350600'	=> [
			'zh-cn'			=> '漳州市',
			'en'			=> 'zhang zhou shi',
		],
		'350602'	=> [
			'zh-cn'			=> '芗城区',
			'en'			=> 'xiang cheng qu',
		],
		'350603'	=> [
			'zh-cn'			=> '龙文区',
			'en'			=> 'long wen qu',
		],
		'350621'	=> [
			'zh-cn'			=> '龙海县',
			'en'			=> 'long hai xian',
		],
		'350622'	=> [
			'zh-cn'			=> '云霄县',
			'en'			=> 'yun xiao xian',
		],
		'350623'	=> [
			'zh-cn'			=> '漳浦县',
			'en'			=> 'zhang pu xian',
		],
		'350624'	=> [
			'zh-cn'			=> '诏安县',
			'en'			=> 'zhao an xian',
		],
		'350625'	=> [
			'zh-cn'			=> '长泰县',
			'en'			=> 'chang tai xian',
		],
		'350626'	=> [
			'zh-cn'			=> '东山县',
			'en'			=> 'dong shan xian',
		],
		'350627'	=> [
			'zh-cn'			=> '南靖县',
			'en'			=> 'nan jing xian',
		],
		'350628'	=> [
			'zh-cn'			=> '平和县',
			'en'			=> 'ping he xian',
		],
		'350629'	=> [
			'zh-cn'			=> '华安县',
			'en'			=> 'hua an xian',
		],
		'350681'	=> [
			'zh-cn'			=> '龙海市',
			'en'			=> 'long hai shi',
		],
		'350700'	=> [
			'zh-cn'			=> '南平市',
			'en'			=> 'nan ping shi',
		],
		'350702'	=> [
			'zh-cn'			=> '延平区',
			'en'			=> 'yan ping qu',
		],
		'350703'	=> [
			'zh-cn'			=> '建阳区',
			'en'			=> 'jian yang qu',
		],
		'350721'	=> [
			'zh-cn'			=> '顺昌县',
			'en'			=> 'shun chang xian',
		],
		'350722'	=> [
			'zh-cn'			=> '浦城县',
			'en'			=> 'pu cheng xian',
		],
		'350723'	=> [
			'zh-cn'			=> '光泽县',
			'en'			=> 'guang ze xian',
		],
		'350724'	=> [
			'zh-cn'			=> '松溪县',
			'en'			=> 'song xi xian',
		],
		'350725'	=> [
			'zh-cn'			=> '政和县',
			'en'			=> 'zheng he xian',
		],
		'350781'	=> [
			'zh-cn'			=> '邵武市',
			'en'			=> 'shao wu shi',
		],
		'350782'	=> [
			'zh-cn'			=> '武夷山市',
			'en'			=> 'wu yi shan shi',
		],
		'350783'	=> [
			'zh-cn'			=> '建瓯市',
			'en'			=> 'jian ou shi',
		],
		'350784'	=> [
			'zh-cn'			=> '建阳市',
			'en'			=> 'jian yang shi',
		],
		'350800'	=> [
			'zh-cn'			=> '龙岩市',
			'en'			=> 'long yan shi',
		],
		'350802'	=> [
			'zh-cn'			=> '新罗区',
			'en'			=> 'xin luo qu',
		],
		'350803'	=> [
			'zh-cn'			=> '永定区',
			'en'			=> 'yong ding qu',
		],
		'350821'	=> [
			'zh-cn'			=> '长汀县',
			'en'			=> 'chang ting xian',
		],
		'350822'	=> [
			'zh-cn'			=> '永定县',
			'en'			=> 'yong ding xian',
		],
		'350823'	=> [
			'zh-cn'			=> '上杭县',
			'en'			=> 'shang hang xian',
		],
		'350824'	=> [
			'zh-cn'			=> '武平县',
			'en'			=> 'wu ping xian',
		],
		'350825'	=> [
			'zh-cn'			=> '连城县',
			'en'			=> 'lian cheng xian',
		],
		'350881'	=> [
			'zh-cn'			=> '漳平市',
			'en'			=> 'zhang ping shi',
		],
		'350900'	=> [
			'zh-cn'			=> '宁德市',
			'en'			=> 'ning de shi',
		],
		'350902'	=> [
			'zh-cn'			=> '蕉城区',
			'en'			=> 'jiao cheng qu',
		],
		'350921'	=> [
			'zh-cn'			=> '霞浦县',
			'en'			=> 'xia pu xian',
		],
		'350922'	=> [
			'zh-cn'			=> '古田县',
			'en'			=> 'gu tian xian',
		],
		'350923'	=> [
			'zh-cn'			=> '屏南县',
			'en'			=> 'ping nan xian',
		],
		'350924'	=> [
			'zh-cn'			=> '寿宁县',
			'en'			=> 'shou ning xian',
		],
		'350925'	=> [
			'zh-cn'			=> '周宁县',
			'en'			=> 'zhou ning xian',
		],
		'350926'	=> [
			'zh-cn'			=> '柘荣县',
			'en'			=> 'zhe rong xian',
		],
		'350981'	=> [
			'zh-cn'			=> '福安市',
			'en'			=> 'fu an shi',
		],
		'350982'	=> [
			'zh-cn'			=> '福鼎市',
			'en'			=> 'fu ding shi',
		],
		'352100'	=> [
			'zh-cn'			=> '南平地区',
			'en'			=> 'nan ping di qu',
		],
		'352101'	=> [
			'zh-cn'			=> '南平市',
			'en'			=> 'nan ping shi',
		],
		'352102'	=> [
			'zh-cn'			=> '邵武市',
			'en'			=> 'shao wu shi',
		],
		'352103'	=> [
			'zh-cn'			=> '武夷山市',
			'en'			=> 'wu yi shan shi',
		],
		'352104'	=> [
			'zh-cn'			=> '建瓯市',
			'en'			=> 'jian ou shi',
		],
		'352121'	=> [
			'zh-cn'			=> '顺昌县',
			'en'			=> 'shun chang xian',
		],
		'352122'	=> [
			'zh-cn'			=> '建阳县',
			'en'			=> 'jian yang xian',
		],
		'352123'	=> [
			'zh-cn'			=> '建瓯县',
			'en'			=> 'jian ou xian',
		],
		'352124'	=> [
			'zh-cn'			=> '浦城县',
			'en'			=> 'pu cheng xian',
		],
		'352125'	=> [
			'zh-cn'			=> '邵武县',
			'en'			=> 'shao wu xian',
		],
		'352126'	=> [
			'zh-cn'			=> '崇安县',
			'en'			=> 'chong an xian',
		],
		'352127'	=> [
			'zh-cn'			=> '光泽县',
			'en'			=> 'guang ze xian',
		],
		'352128'	=> [
			'zh-cn'			=> '松溪县',
			'en'			=> 'song xi xian',
		],
		'352129'	=> [
			'zh-cn'			=> '政和县',
			'en'			=> 'zheng he xian',
		],
		'352200'	=> [
			'zh-cn'			=> '宁德地区',
			'en'			=> 'ning de di qu',
		],
		'352201'	=> [
			'zh-cn'			=> '宁德市',
			'en'			=> 'ning de shi',
		],
		'352202'	=> [
			'zh-cn'			=> '福安市',
			'en'			=> 'fu an shi',
		],
		'352203'	=> [
			'zh-cn'			=> '福鼎市',
			'en'			=> 'fu ding shi',
		],
		'352221'	=> [
			'zh-cn'			=> '宁德县',
			'en'			=> 'ning de xian',
		],
		'352222'	=> [
			'zh-cn'			=> '连江县',
			'en'			=> 'lian jiang xian',
		],
		'352223'	=> [
			'zh-cn'			=> '罗源县',
			'en'			=> 'luo yuan xian',
		],
		'352224'	=> [
			'zh-cn'			=> '福鼎县',
			'en'			=> 'fu ding xian',
		],
		'352225'	=> [
			'zh-cn'			=> '霞浦县',
			'en'			=> 'xia pu xian',
		],
		'352226'	=> [
			'zh-cn'			=> '福安县',
			'en'			=> 'fu an xian',
		],
		'352227'	=> [
			'zh-cn'			=> '古田县',
			'en'			=> 'gu tian xian',
		],
		'352228'	=> [
			'zh-cn'			=> '屏南县',
			'en'			=> 'ping nan xian',
		],
		'352229'	=> [
			'zh-cn'			=> '寿宁县',
			'en'			=> 'shou ning xian',
		],
		'352230'	=> [
			'zh-cn'			=> '周宁县',
			'en'			=> 'zhou ning xian',
		],
		'352231'	=> [
			'zh-cn'			=> '柘荣县',
			'en'			=> 'zhe rong xian',
		],
		'352300'	=> [
			'zh-cn'			=> '莆田地区',
			'en'			=> 'pu tian di qu',
		],
		'352321'	=> [
			'zh-cn'			=> '莆田县',
			'en'			=> 'pu tian xian',
		],
		'352322'	=> [
			'zh-cn'			=> '永泰县',
			'en'			=> 'yong tai xian',
		],
		'352323'	=> [
			'zh-cn'			=> '仙游县',
			'en'			=> 'xian you xian',
		],
		'352324'	=> [
			'zh-cn'			=> '平潭县',
			'en'			=> 'ping tan xian',
		],
		'352325'	=> [
			'zh-cn'			=> '福清县',
			'en'			=> 'fu qing xian',
		],
		'352326'	=> [
			'zh-cn'			=> '闽清县',
			'en'			=> 'min qing xian',
		],
		'352327'	=> [
			'zh-cn'			=> '长乐县',
			'en'			=> 'chang le xian',
		],
		'352400'	=> [
			'zh-cn'			=> '晋江地区',
			'en'			=> 'jin jiang di qu',
		],
		'352401'	=> [
			'zh-cn'			=> '泉州市',
			'en'			=> 'quan zhou shi',
		],
		'352421'	=> [
			'zh-cn'			=> '惠安县',
			'en'			=> 'hui an xian',
		],
		'352422'	=> [
			'zh-cn'			=> '晋江县',
			'en'			=> 'jin jiang xian',
		],
		'352423'	=> [
			'zh-cn'			=> '南安县',
			'en'			=> 'nan an xian',
		],
		'352424'	=> [
			'zh-cn'			=> '安溪县',
			'en'			=> 'an xi xian',
		],
		'352425'	=> [
			'zh-cn'			=> '永春县',
			'en'			=> 'yong chun xian',
		],
		'352426'	=> [
			'zh-cn'			=> '德化县',
			'en'			=> 'de hua xian',
		],
		'352427'	=> [
			'zh-cn'			=> '金门县',
			'en'			=> 'jin men xian',
		],
		'352500'	=> [
			'zh-cn'			=> '龙溪地区',
			'en'			=> 'long xi di qu',
		],
		'352501'	=> [
			'zh-cn'			=> '漳州市',
			'en'			=> 'zhang zhou shi',
		],
		'352521'	=> [
			'zh-cn'			=> '龙海县',
			'en'			=> 'long hai xian',
		],
		'352522'	=> [
			'zh-cn'			=> '云霄县',
			'en'			=> 'yun xiao xian',
		],
		'352523'	=> [
			'zh-cn'			=> '漳浦县',
			'en'			=> 'zhang pu xian',
		],
		'352524'	=> [
			'zh-cn'			=> '诏安县',
			'en'			=> 'zhao an xian',
		],
		'352525'	=> [
			'zh-cn'			=> '长泰县',
			'en'			=> 'chang tai xian',
		],
		'352526'	=> [
			'zh-cn'			=> '东山县',
			'en'			=> 'dong shan xian',
		],
		'352527'	=> [
			'zh-cn'			=> '南靖县',
			'en'			=> 'nan jing xian',
		],
		'352528'	=> [
			'zh-cn'			=> '平和县',
			'en'			=> 'ping he xian',
		],
		'352529'	=> [
			'zh-cn'			=> '华安县',
			'en'			=> 'hua an xian',
		],
		'352600'	=> [
			'zh-cn'			=> '龙岩地区',
			'en'			=> 'long yan di qu',
		],
		'352601'	=> [
			'zh-cn'			=> '龙岩市',
			'en'			=> 'long yan shi',
		],
		'352602'	=> [
			'zh-cn'			=> '漳平市',
			'en'			=> 'zhang ping shi',
		],
		'352622'	=> [
			'zh-cn'			=> '长汀县',
			'en'			=> 'chang ting xian',
		],
		'352623'	=> [
			'zh-cn'			=> '永定县',
			'en'			=> 'yong ding xian',
		],
		'352624'	=> [
			'zh-cn'			=> '上杭县',
			'en'			=> 'shang hang xian',
		],
		'352625'	=> [
			'zh-cn'			=> '武平县',
			'en'			=> 'wu ping xian',
		],
		'352626'	=> [
			'zh-cn'			=> '漳平县',
			'en'			=> 'zhang ping xian',
		],
		'352627'	=> [
			'zh-cn'			=> '连城县',
			'en'			=> 'lian cheng xian',
		],
		'352700'	=> [
			'zh-cn'			=> '三明地区',
			'en'			=> 'san ming di qu',
		],
		'352701'	=> [
			'zh-cn'			=> '三明市',
			'en'			=> 'san ming shi',
		],
		'352721'	=> [
			'zh-cn'			=> '明溪县',
			'en'			=> 'ming xi xian',
		],
		'352722'	=> [
			'zh-cn'			=> '永安县',
			'en'			=> 'yong an xian',
		],
		'352723'	=> [
			'zh-cn'			=> '清流县',
			'en'			=> 'qing liu xian',
		],
		'352724'	=> [
			'zh-cn'			=> '宁化县',
			'en'			=> 'ning hua xian',
		],
		'352725'	=> [
			'zh-cn'			=> '大田县',
			'en'			=> 'da tian xian',
		],
		'352726'	=> [
			'zh-cn'			=> '尤溪县',
			'en'			=> 'you xi xian',
		],
		'352728'	=> [
			'zh-cn'			=> '将乐县',
			'en'			=> 'jiang le xian',
		],
		'352729'	=> [
			'zh-cn'			=> '泰宁县',
			'en'			=> 'tai ning xian',
		],
		'352730'	=> [
			'zh-cn'			=> '建宁县',
			'en'			=> 'jian ning xian',
		],
		'359001'	=> [
			'zh-cn'			=> '永安市',
			'en'			=> 'yong an shi',
		],
		'359002'	=> [
			'zh-cn'			=> '石狮市',
			'en'			=> 'shi shi shi',
		],
		'359003'	=> [
			'zh-cn'			=> '福清市',
			'en'			=> 'fu qing shi',
		],
		'359004'	=> [
			'zh-cn'			=> '晋江市',
			'en'			=> 'jin jiang shi',
		],
		'359005'	=> [
			'zh-cn'			=> '南安市',
			'en'			=> 'nan an shi',
		],
		'359006'	=> [
			'zh-cn'			=> '龙海市',
			'en'			=> 'long hai shi',
		],
		'359007'	=> [
			'zh-cn'			=> '邵武市',
			'en'			=> 'shao wu shi',
		],
		'359008'	=> [
			'zh-cn'			=> '武夷山市',
			'en'			=> 'wu yi shan shi',
		],
		'359009'	=> [
			'zh-cn'			=> '建瓯市',
			'en'			=> 'jian ou shi',
		],
		'359010'	=> [
			'zh-cn'			=> '建阳市',
			'en'			=> 'jian yang shi',
		],
		'359011'	=> [
			'zh-cn'			=> '长乐市',
			'en'			=> 'chang le shi',
		],
		'360000'	=> [
			'zh-cn'			=> '江西省',
			'en'			=> 'jiang xi sheng',
		],
		'360100'	=> [
			'zh-cn'			=> '南昌市',
			'en'			=> 'nan chang shi',
		],
		'360102'	=> [
			'zh-cn'			=> '东湖区',
			'en'			=> 'dong hu qu',
		],
		'360103'	=> [
			'zh-cn'			=> '西湖区',
			'en'			=> 'xi hu qu',
		],
		'360104'	=> [
			'zh-cn'			=> '青云谱区',
			'en'			=> 'qing yun pu qu',
		],
		'360105'	=> [
			'zh-cn'			=> '湾里区',
			'en'			=> 'wan li qu',
		],
		'360111'	=> [
			'zh-cn'			=> '青山湖区',
			'en'			=> 'qing shan hu qu',
		],
		'360112'	=> [
			'zh-cn'			=> '新建区',
			'en'			=> 'xin jian qu',
		],
		'360121'	=> [
			'zh-cn'			=> '南昌县',
			'en'			=> 'nan chang xian',
		],
		'360122'	=> [
			'zh-cn'			=> '新建县',
			'en'			=> 'xin jian xian',
		],
		'360123'	=> [
			'zh-cn'			=> '安义县',
			'en'			=> 'an yi xian',
		],
		'360124'	=> [
			'zh-cn'			=> '进贤县',
			'en'			=> 'jin xian xian',
		],
		'360200'	=> [
			'zh-cn'			=> '景德镇市',
			'en'			=> 'jing de zhen shi',
		],
		'360202'	=> [
			'zh-cn'			=> '昌江区',
			'en'			=> 'chang jiang qu',
		],
		'360203'	=> [
			'zh-cn'			=> '珠山区',
			'en'			=> 'zhu shan qu',
		],
		'360211'	=> [
			'zh-cn'			=> '鹅湖区',
			'en'			=> 'e hu qu',
		],
		'360212'	=> [
			'zh-cn'			=> '蛟潭区',
			'en'			=> 'jiao tan qu',
		],
		'360221'	=> [
			'zh-cn'			=> '乐平县',
			'en'			=> 'le ping xian',
		],
		'360222'	=> [
			'zh-cn'			=> '浮梁县',
			'en'			=> 'fu liang xian',
		],
		'360281'	=> [
			'zh-cn'			=> '乐平市',
			'en'			=> 'le ping shi',
		],
		'360300'	=> [
			'zh-cn'			=> '萍乡市',
			'en'			=> 'ping xiang shi',
		],
		'360302'	=> [
			'zh-cn'			=> '安源区',
			'en'			=> 'an yuan qu',
		],
		'360311'	=> [
			'zh-cn'			=> '上栗区',
			'en'			=> 'shang li qu',
		],
		'360312'	=> [
			'zh-cn'			=> '芦溪区',
			'en'			=> 'lu xi qu',
		],
		'360313'	=> [
			'zh-cn'			=> '湘东区',
			'en'			=> 'xiang dong qu',
		],
		'360321'	=> [
			'zh-cn'			=> '莲花县',
			'en'			=> 'lian hua xian',
		],
		'360322'	=> [
			'zh-cn'			=> '上栗县',
			'en'			=> 'shang li xian',
		],
		'360323'	=> [
			'zh-cn'			=> '芦溪县',
			'en'			=> 'lu xi xian',
		],
		'360400'	=> [
			'zh-cn'			=> '九江市',
			'en'			=> 'jiu jiang shi',
		],
		'360402'	=> [
			'zh-cn'			=> '濂溪区',
			'en'			=> 'lian xi qu',
		],
		'360403'	=> [
			'zh-cn'			=> '浔阳区',
			'en'			=> 'xun yang qu',
		],
		'360421'	=> [
			'zh-cn'			=> '九江县',
			'en'			=> 'jiu jiang xian',
		],
		'360422'	=> [
			'zh-cn'			=> '瑞昌县',
			'en'			=> 'rui chang xian',
		],
		'360423'	=> [
			'zh-cn'			=> '武宁县',
			'en'			=> 'wu ning xian',
		],
		'360424'	=> [
			'zh-cn'			=> '修水县',
			'en'			=> 'xiu shui xian',
		],
		'360425'	=> [
			'zh-cn'			=> '永修县',
			'en'			=> 'yong xiu xian',
		],
		'360426'	=> [
			'zh-cn'			=> '德安县',
			'en'			=> 'de an xian',
		],
		'360427'	=> [
			'zh-cn'			=> '星子县',
			'en'			=> 'xing zi xian',
		],
		'360428'	=> [
			'zh-cn'			=> '都昌县',
			'en'			=> 'du chang xian',
		],
		'360429'	=> [
			'zh-cn'			=> '湖口县',
			'en'			=> 'hu kou xian',
		],
		'360430'	=> [
			'zh-cn'			=> '彭泽县',
			'en'			=> 'peng ze xian',
		],
		'360481'	=> [
			'zh-cn'			=> '瑞昌市',
			'en'			=> 'rui chang shi',
		],
		'360482'	=> [
			'zh-cn'			=> '共青城市',
			'en'			=> 'gong qing cheng shi',
		],
		'360483'	=> [
			'zh-cn'			=> '庐山市',
			'en'			=> 'lu shan shi',
		],
		'360500'	=> [
			'zh-cn'			=> '新余市',
			'en'			=> 'xin yu shi',
		],
		'360502'	=> [
			'zh-cn'			=> '渝水区',
			'en'			=> 'yu shui qu',
		],
		'360521'	=> [
			'zh-cn'			=> '分宜县',
			'en'			=> 'fen yi xian',
		],
		'360600'	=> [
			'zh-cn'			=> '鹰潭市',
			'en'			=> 'ying tan shi',
		],
		'360602'	=> [
			'zh-cn'			=> '月湖区',
			'en'			=> 'yue hu qu',
		],
		'360621'	=> [
			'zh-cn'			=> '贵溪县',
			'en'			=> 'gui xi xian',
		],
		'360622'	=> [
			'zh-cn'			=> '余江县',
			'en'			=> 'yu jiang xian',
		],
		'360681'	=> [
			'zh-cn'			=> '贵溪市',
			'en'			=> 'gui xi shi',
		],
		'360700'	=> [
			'zh-cn'			=> '赣州市',
			'en'			=> 'gan zhou shi',
		],
		'360702'	=> [
			'zh-cn'			=> '章贡区',
			'en'			=> 'zhang gong qu',
		],
		'360703'	=> [
			'zh-cn'			=> '南康区',
			'en'			=> 'nan kang qu',
		],
		'360722'	=> [
			'zh-cn'			=> '信丰县',
			'en'			=> 'xin feng xian',
		],
		'360723'	=> [
			'zh-cn'			=> '大余县',
			'en'			=> 'da yu xian',
		],
		'360724'	=> [
			'zh-cn'			=> '上犹县',
			'en'			=> 'shang you xian',
		],
		'360725'	=> [
			'zh-cn'			=> '崇义县',
			'en'			=> 'chong yi xian',
		],
		'360726'	=> [
			'zh-cn'			=> '安远县',
			'en'			=> 'an yuan xian',
		],
		'360727'	=> [
			'zh-cn'			=> '龙南县',
			'en'			=> 'long nan xian',
		],
		'360728'	=> [
			'zh-cn'			=> '定南县',
			'en'			=> 'ding nan xian',
		],
		'360729'	=> [
			'zh-cn'			=> '全南县',
			'en'			=> 'quan nan xian',
		],
		'360730'	=> [
			'zh-cn'			=> '宁都县',
			'en'			=> 'ning du xian',
		],
		'360731'	=> [
			'zh-cn'			=> '于都县',
			'en'			=> 'yu du xian',
		],
		'360732'	=> [
			'zh-cn'			=> '兴国县',
			'en'			=> 'xing guo xian',
		],
		'360733'	=> [
			'zh-cn'			=> '会昌县',
			'en'			=> 'hui chang xian',
		],
		'360734'	=> [
			'zh-cn'			=> '寻乌县',
			'en'			=> 'xun wu xian',
		],
		'360735'	=> [
			'zh-cn'			=> '石城县',
			'en'			=> 'shi cheng xian',
		],
		'360781'	=> [
			'zh-cn'			=> '瑞金市',
			'en'			=> 'rui jin shi',
		],
		'360782'	=> [
			'zh-cn'			=> '南康市',
			'en'			=> 'nan kang shi',
		],
		'360800'	=> [
			'zh-cn'			=> '吉安市',
			'en'			=> 'ji an shi',
		],
		'360802'	=> [
			'zh-cn'			=> '吉州区',
			'en'			=> 'ji zhou qu',
		],
		'360803'	=> [
			'zh-cn'			=> '青原区',
			'en'			=> 'qing yuan qu',
		],
		'360821'	=> [
			'zh-cn'			=> '吉安县',
			'en'			=> 'ji an xian',
		],
		'360822'	=> [
			'zh-cn'			=> '吉水县',
			'en'			=> 'ji shui xian',
		],
		'360823'	=> [
			'zh-cn'			=> '峡江县',
			'en'			=> 'xia jiang xian',
		],
		'360824'	=> [
			'zh-cn'			=> '新干县',
			'en'			=> 'xin gan xian',
		],
		'360825'	=> [
			'zh-cn'			=> '永丰县',
			'en'			=> 'yong feng xian',
		],
		'360826'	=> [
			'zh-cn'			=> '泰和县',
			'en'			=> 'tai he xian',
		],
		'360827'	=> [
			'zh-cn'			=> '遂川县',
			'en'			=> 'sui chuan xian',
		],
		'360828'	=> [
			'zh-cn'			=> '万安县',
			'en'			=> 'wan an xian',
		],
		'360829'	=> [
			'zh-cn'			=> '安福县',
			'en'			=> 'an fu xian',
		],
		'360830'	=> [
			'zh-cn'			=> '永新县',
			'en'			=> 'yong xin xian',
		],
		'360881'	=> [
			'zh-cn'			=> '井冈山市',
			'en'			=> 'jing gang shan shi',
		],
		'360900'	=> [
			'zh-cn'			=> '宜春市',
			'en'			=> 'yi chun shi',
		],
		'360902'	=> [
			'zh-cn'			=> '袁州区',
			'en'			=> 'yuan zhou qu',
		],
		'360921'	=> [
			'zh-cn'			=> '奉新县',
			'en'			=> 'feng xin xian',
		],
		'360922'	=> [
			'zh-cn'			=> '万载县',
			'en'			=> 'wan zai xian',
		],
		'360923'	=> [
			'zh-cn'			=> '上高县',
			'en'			=> 'shang gao xian',
		],
		'360924'	=> [
			'zh-cn'			=> '宜丰县',
			'en'			=> 'yi feng xian',
		],
		'360925'	=> [
			'zh-cn'			=> '靖安县',
			'en'			=> 'jing an xian',
		],
		'360926'	=> [
			'zh-cn'			=> '铜鼓县',
			'en'			=> 'tong gu xian',
		],
		'360981'	=> [
			'zh-cn'			=> '丰城市',
			'en'			=> 'feng cheng shi',
		],
		'360982'	=> [
			'zh-cn'			=> '樟树市',
			'en'			=> 'zhang shu shi',
		],
		'360983'	=> [
			'zh-cn'			=> '高安市',
			'en'			=> 'gao an shi',
		],
		'361000'	=> [
			'zh-cn'			=> '抚州市',
			'en'			=> 'fu zhou shi',
		],
		'361002'	=> [
			'zh-cn'			=> '临川区',
			'en'			=> 'lin chuan qu',
		],
		'361003'	=> [
			'zh-cn'			=> '东乡区',
			'en'			=> 'dong xiang qu',
		],
		'361021'	=> [
			'zh-cn'			=> '南城县',
			'en'			=> 'nan cheng xian',
		],
		'361022'	=> [
			'zh-cn'			=> '黎川县',
			'en'			=> 'li chuan xian',
		],
		'361023'	=> [
			'zh-cn'			=> '南丰县',
			'en'			=> 'nan feng xian',
		],
		'361024'	=> [
			'zh-cn'			=> '崇仁县',
			'en'			=> 'chong ren xian',
		],
		'361025'	=> [
			'zh-cn'			=> '乐安县',
			'en'			=> 'le an xian',
		],
		'361026'	=> [
			'zh-cn'			=> '宜黄县',
			'en'			=> 'yi huang xian',
		],
		'361027'	=> [
			'zh-cn'			=> '金溪县',
			'en'			=> 'jin xi xian',
		],
		'361028'	=> [
			'zh-cn'			=> '资溪县',
			'en'			=> 'zi xi xian',
		],
		'361029'	=> [
			'zh-cn'			=> '东乡县',
			'en'			=> 'dong xiang xian',
		],
		'361030'	=> [
			'zh-cn'			=> '广昌县',
			'en'			=> 'guang chang xian',
		],
		'361100'	=> [
			'zh-cn'			=> '上饶市',
			'en'			=> 'shang rao shi',
		],
		'361102'	=> [
			'zh-cn'			=> '信州区',
			'en'			=> 'xin zhou qu',
		],
		'361103'	=> [
			'zh-cn'			=> '广丰区',
			'en'			=> 'guang feng qu',
		],
		'361121'	=> [
			'zh-cn'			=> '上饶县',
			'en'			=> 'shang rao xian',
		],
		'361122'	=> [
			'zh-cn'			=> '广丰县',
			'en'			=> 'guang feng xian',
		],
		'361123'	=> [
			'zh-cn'			=> '玉山县',
			'en'			=> 'yu shan xian',
		],
		'361124'	=> [
			'zh-cn'			=> '铅山县',
			'en'			=> 'yan shan xian',
		],
		'361125'	=> [
			'zh-cn'			=> '横峰县',
			'en'			=> 'heng feng xian',
		],
		'361126'	=> [
			'zh-cn'			=> '弋阳县',
			'en'			=> 'yi yang xian',
		],
		'361127'	=> [
			'zh-cn'			=> '余干县',
			'en'			=> 'yu gan xian',
		],
		'361128'	=> [
			'zh-cn'			=> '鄱阳县',
			'en'			=> 'po yang xian',
		],
		'361129'	=> [
			'zh-cn'			=> '万年县',
			'en'			=> 'wan nian xian',
		],
		'361130'	=> [
			'zh-cn'			=> '婺源县',
			'en'			=> 'wu yuan xian',
		],
		'361181'	=> [
			'zh-cn'			=> '德兴市',
			'en'			=> 'de xing shi',
		],
		'362100'	=> [
			'zh-cn'			=> '赣州地区',
			'en'			=> 'gan zhou di qu',
		],
		'362101'	=> [
			'zh-cn'			=> '赣州市',
			'en'			=> 'gan zhou shi',
		],
		'362102'	=> [
			'zh-cn'			=> '瑞金市',
			'en'			=> 'rui jin shi',
		],
		'362103'	=> [
			'zh-cn'			=> '南康市',
			'en'			=> 'nan kang shi',
		],
		'362122'	=> [
			'zh-cn'			=> '南康县',
			'en'			=> 'nan kang xian',
		],
		'362123'	=> [
			'zh-cn'			=> '信丰县',
			'en'			=> 'xin feng xian',
		],
		'362124'	=> [
			'zh-cn'			=> '大余县',
			'en'			=> 'da yu xian',
		],
		'362125'	=> [
			'zh-cn'			=> '上犹县',
			'en'			=> 'shang you xian',
		],
		'362126'	=> [
			'zh-cn'			=> '崇义县',
			'en'			=> 'chong yi xian',
		],
		'362127'	=> [
			'zh-cn'			=> '安远县',
			'en'			=> 'an yuan xian',
		],
		'362128'	=> [
			'zh-cn'			=> '龙南县',
			'en'			=> 'long nan xian',
		],
		'362129'	=> [
			'zh-cn'			=> '定南县',
			'en'			=> 'ding nan xian',
		],
		'362130'	=> [
			'zh-cn'			=> '全南县',
			'en'			=> 'quan nan xian',
		],
		'362131'	=> [
			'zh-cn'			=> '宁都县',
			'en'			=> 'ning du xian',
		],
		'362132'	=> [
			'zh-cn'			=> '于都县',
			'en'			=> 'yu du xian',
		],
		'362133'	=> [
			'zh-cn'			=> '兴国县',
			'en'			=> 'xing guo xian',
		],
		'362134'	=> [
			'zh-cn'			=> '瑞金县',
			'en'			=> 'rui jin xian',
		],
		'362135'	=> [
			'zh-cn'			=> '会昌县',
			'en'			=> 'hui chang xian',
		],
		'362136'	=> [
			'zh-cn'			=> '寻乌县',
			'en'			=> 'xun wu xian',
		],
		'362137'	=> [
			'zh-cn'			=> '石城县',
			'en'			=> 'shi cheng xian',
		],
		'362138'	=> [
			'zh-cn'			=> '广昌县',
			'en'			=> 'guang chang xian',
		],
		'362200'	=> [
			'zh-cn'			=> '宜春地区',
			'en'			=> 'yi chun di qu',
		],
		'362201'	=> [
			'zh-cn'			=> '宜春市',
			'en'			=> 'yi chun shi',
		],
		'362202'	=> [
			'zh-cn'			=> '丰城市',
			'en'			=> 'feng cheng shi',
		],
		'362203'	=> [
			'zh-cn'			=> '樟树市',
			'en'			=> 'zhang shu shi',
		],
		'362204'	=> [
			'zh-cn'			=> '高安市',
			'en'			=> 'gao an shi',
		],
		'362221'	=> [
			'zh-cn'			=> '丰城县',
			'en'			=> 'feng cheng xian',
		],
		'362222'	=> [
			'zh-cn'			=> '高安县',
			'en'			=> 'gao an xian',
		],
		'362223'	=> [
			'zh-cn'			=> '清江县',
			'en'			=> 'qing jiang xian',
		],
		'362224'	=> [
			'zh-cn'			=> '分宜县',
			'en'			=> 'fen yi xian',
		],
		'362225'	=> [
			'zh-cn'			=> '宜春县',
			'en'			=> 'yi chun xian',
		],
		'362226'	=> [
			'zh-cn'			=> '奉新县',
			'en'			=> 'feng xin xian',
		],
		'362227'	=> [
			'zh-cn'			=> '万载县',
			'en'			=> 'wan zai xian',
		],
		'362228'	=> [
			'zh-cn'			=> '上高县',
			'en'			=> 'shang gao xian',
		],
		'362229'	=> [
			'zh-cn'			=> '宜丰县',
			'en'			=> 'yi feng xian',
		],
		'362230'	=> [
			'zh-cn'			=> '新余县',
			'en'			=> 'xin yu xian',
		],
		'362231'	=> [
			'zh-cn'			=> '安义县',
			'en'			=> 'an yi xian',
		],
		'362232'	=> [
			'zh-cn'			=> '靖安县',
			'en'			=> 'jing an xian',
		],
		'362233'	=> [
			'zh-cn'			=> '铜鼓县',
			'en'			=> 'tong gu xian',
		],
		'362300'	=> [
			'zh-cn'			=> '上饶地区',
			'en'			=> 'shang rao di qu',
		],
		'362301'	=> [
			'zh-cn'			=> '上饶市',
			'en'			=> 'shang rao shi',
		],
		'362302'	=> [
			'zh-cn'			=> '德兴市',
			'en'			=> 'de xing shi',
		],
		'362321'	=> [
			'zh-cn'			=> '上饶县',
			'en'			=> 'shang rao xian',
		],
		'362322'	=> [
			'zh-cn'			=> '广丰县',
			'en'			=> 'guang feng xian',
		],
		'362323'	=> [
			'zh-cn'			=> '玉山县',
			'en'			=> 'yu shan xian',
		],
		'362324'	=> [
			'zh-cn'			=> '铅山县',
			'en'			=> 'yan shan xian',
		],
		'362325'	=> [
			'zh-cn'			=> '横峰县',
			'en'			=> 'heng feng xian',
		],
		'362326'	=> [
			'zh-cn'			=> '弋阳县',
			'en'			=> 'yi yang xian',
		],
		'362327'	=> [
			'zh-cn'			=> '贵溪县',
			'en'			=> 'gui xi xian',
		],
		'362328'	=> [
			'zh-cn'			=> '余江县',
			'en'			=> 'yu jiang xian',
		],
		'362329'	=> [
			'zh-cn'			=> '余干县',
			'en'			=> 'yu gan xian',
		],
		'362330'	=> [
			'zh-cn'			=> '波阳县',
			'en'			=> 'bo yang xian',
		],
		'362331'	=> [
			'zh-cn'			=> '万年县',
			'en'			=> 'wan nian xian',
		],
		'362332'	=> [
			'zh-cn'			=> '乐平县',
			'en'			=> 'le ping xian',
		],
		'362333'	=> [
			'zh-cn'			=> '德兴县',
			'en'			=> 'de xing xian',
		],
		'362334'	=> [
			'zh-cn'			=> '婺源县',
			'en'			=> 'wu yuan xian',
		],
		'362400'	=> [
			'zh-cn'			=> '吉安地区',
			'en'			=> 'ji an di qu',
		],
		'362401'	=> [
			'zh-cn'			=> '吉安市',
			'en'			=> 'ji an shi',
		],
		'362402'	=> [
			'zh-cn'			=> '井冈山市',
			'en'			=> 'jing gang shan shi',
		],
		'362421'	=> [
			'zh-cn'			=> '吉安县',
			'en'			=> 'ji an xian',
		],
		'362422'	=> [
			'zh-cn'			=> '吉水县',
			'en'			=> 'ji shui xian',
		],
		'362423'	=> [
			'zh-cn'			=> '峡江县',
			'en'			=> 'xia jiang xian',
		],
		'362424'	=> [
			'zh-cn'			=> '新干县',
			'en'			=> 'xin gan xian',
		],
		'362425'	=> [
			'zh-cn'			=> '永丰县',
			'en'			=> 'yong feng xian',
		],
		'362426'	=> [
			'zh-cn'			=> '泰和县',
			'en'			=> 'tai he xian',
		],
		'362427'	=> [
			'zh-cn'			=> '遂川县',
			'en'			=> 'sui chuan xian',
		],
		'362428'	=> [
			'zh-cn'			=> '万安县',
			'en'			=> 'wan an xian',
		],
		'362429'	=> [
			'zh-cn'			=> '安福县',
			'en'			=> 'an fu xian',
		],
		'362430'	=> [
			'zh-cn'			=> '永新县',
			'en'			=> 'yong xin xian',
		],
		'362431'	=> [
			'zh-cn'			=> '莲花县',
			'en'			=> 'lian hua xian',
		],
		'362432'	=> [
			'zh-cn'			=> '宁冈县',
			'en'			=> 'ning gang xian',
		],
		'362433'	=> [
			'zh-cn'			=> '井冈山县',
			'en'			=> 'jing gang shan xian',
		],
		'362500'	=> [
			'zh-cn'			=> '抚州地区',
			'en'			=> 'fu zhou di qu',
		],
		'362501'	=> [
			'zh-cn'			=> '临川市',
			'en'			=> 'lin chuan shi',
		],
		'362521'	=> [
			'zh-cn'			=> '临川县',
			'en'			=> 'lin chuan xian',
		],
		'362522'	=> [
			'zh-cn'			=> '南城县',
			'en'			=> 'nan cheng xian',
		],
		'362523'	=> [
			'zh-cn'			=> '黎川县',
			'en'			=> 'li chuan xian',
		],
		'362524'	=> [
			'zh-cn'			=> '南丰县',
			'en'			=> 'nan feng xian',
		],
		'362525'	=> [
			'zh-cn'			=> '崇仁县',
			'en'			=> 'chong ren xian',
		],
		'362526'	=> [
			'zh-cn'			=> '乐安县',
			'en'			=> 'le an xian',
		],
		'362527'	=> [
			'zh-cn'			=> '宜黄县',
			'en'			=> 'yi huang xian',
		],
		'362528'	=> [
			'zh-cn'			=> '金溪县',
			'en'			=> 'jin xi xian',
		],
		'362529'	=> [
			'zh-cn'			=> '资溪县',
			'en'			=> 'zi xi xian',
		],
		'362530'	=> [
			'zh-cn'			=> '进贤县',
			'en'			=> 'jin xian xian',
		],
		'362531'	=> [
			'zh-cn'			=> '东乡县',
			'en'			=> 'dong xiang xian',
		],
		'362532'	=> [
			'zh-cn'			=> '广昌县',
			'en'			=> 'guang chang xian',
		],
		'362600'	=> [
			'zh-cn'			=> '九江地区',
			'en'			=> 'jiu jiang di qu',
		],
		'362621'	=> [
			'zh-cn'			=> '九江县',
			'en'			=> 'jiu jiang xian',
		],
		'362622'	=> [
			'zh-cn'			=> '瑞昌县',
			'en'			=> 'rui chang xian',
		],
		'362623'	=> [
			'zh-cn'			=> '武宁县',
			'en'			=> 'wu ning xian',
		],
		'362624'	=> [
			'zh-cn'			=> '修水县',
			'en'			=> 'xiu shui xian',
		],
		'362625'	=> [
			'zh-cn'			=> '永修县',
			'en'			=> 'yong xiu xian',
		],
		'362626'	=> [
			'zh-cn'			=> '德安县',
			'en'			=> 'de an xian',
		],
		'362627'	=> [
			'zh-cn'			=> '星子县',
			'en'			=> 'xing zi xian',
		],
		'362628'	=> [
			'zh-cn'			=> '都昌县',
			'en'			=> 'du chang xian',
		],
		'362629'	=> [
			'zh-cn'			=> '湖口县',
			'en'			=> 'hu kou xian',
		],
		'362630'	=> [
			'zh-cn'			=> '彭泽县',
			'en'			=> 'peng ze xian',
		],
		'369001'	=> [
			'zh-cn'			=> '瑞昌市',
			'en'			=> 'rui chang shi',
		],
		'369002'	=> [
			'zh-cn'			=> '乐平市',
			'en'			=> 'le ping shi',
		],
		'370000'	=> [
			'zh-cn'			=> '山东省',
			'en'			=> 'shan dong sheng',
		],
		'370100'	=> [
			'zh-cn'			=> '济南市',
			'en'			=> 'ji nan shi',
		],
		'370102'	=> [
			'zh-cn'			=> '历下区',
			'en'			=> 'li xia qu',
		],
		'370103'	=> [
			'zh-cn'			=> '市中区',
			'en'			=> 'shi zhong qu',
		],
		'370104'	=> [
			'zh-cn'			=> '槐荫区',
			'en'			=> 'huai yin qu',
		],
		'370105'	=> [
			'zh-cn'			=> '天桥区',
			'en'			=> 'tian qiao qu',
		],
		'370112'	=> [
			'zh-cn'			=> '历城区',
			'en'			=> 'li cheng qu',
		],
		'370113'	=> [
			'zh-cn'			=> '长清区',
			'en'			=> 'chang qing qu',
		],
		'370114'	=> [
			'zh-cn'			=> '章丘区',
			'en'			=> 'zhang qiu qu',
		],
		'370121'	=> [
			'zh-cn'			=> '历城县',
			'en'			=> 'li cheng xian',
		],
		'370122'	=> [
			'zh-cn'			=> '章丘县',
			'en'			=> 'zhang qiu xian',
		],
		'370123'	=> [
			'zh-cn'			=> '长清县',
			'en'			=> 'chang qing xian',
		],
		'370124'	=> [
			'zh-cn'			=> '平阴县',
			'en'			=> 'ping yin xian',
		],
		'370125'	=> [
			'zh-cn'			=> '济阳县',
			'en'			=> 'ji yang xian',
		],
		'370126'	=> [
			'zh-cn'			=> '商河县',
			'en'			=> 'shang he xian',
		],
		'370181'	=> [
			'zh-cn'			=> '章丘市',
			'en'			=> 'zhang qiu shi',
		],
		'370200'	=> [
			'zh-cn'			=> '青岛市',
			'en'			=> 'qing dao shi',
		],
		'370202'	=> [
			'zh-cn'			=> '市南区',
			'en'			=> 'shi nan qu',
		],
		'370203'	=> [
			'zh-cn'			=> '市北区',
			'en'			=> 'shi bei qu',
		],
		'370204'	=> [
			'zh-cn'			=> '台东区',
			'en'			=> 'tai dong qu',
		],
		'370205'	=> [
			'zh-cn'			=> '四方区',
			'en'			=> 'si fang qu',
		],
		'370206'	=> [
			'zh-cn'			=> '沧口区',
			'en'			=> 'cang kou qu',
		],
		'370211'	=> [
			'zh-cn'			=> '黄岛区',
			'en'			=> 'huang dao qu',
		],
		'370212'	=> [
			'zh-cn'			=> '崂山区',
			'en'			=> 'lao shan qu',
		],
		'370213'	=> [
			'zh-cn'			=> '李沧区',
			'en'			=> 'li cang qu',
		],
		'370214'	=> [
			'zh-cn'			=> '城阳区',
			'en'			=> 'cheng yang qu',
		],
		'370221'	=> [
			'zh-cn'			=> '崂山县',
			'en'			=> 'lao shan xian',
		],
		'370222'	=> [
			'zh-cn'			=> '即墨县',
			'en'			=> 'ji mo xian',
		],
		'370223'	=> [
			'zh-cn'			=> '胶南县',
			'en'			=> 'jiao nan xian',
		],
		'370225'	=> [
			'zh-cn'			=> '莱西县',
			'en'			=> 'lai xi xian',
		],
		'370226'	=> [
			'zh-cn'			=> '平度县',
			'en'			=> 'ping du xian',
		],
		'370281'	=> [
			'zh-cn'			=> '胶州市',
			'en'			=> 'jiao zhou shi',
		],
		'370282'	=> [
			'zh-cn'			=> '即墨市',
			'en'			=> 'ji mo shi',
		],
		'370283'	=> [
			'zh-cn'			=> '平度市',
			'en'			=> 'ping du shi',
		],
		'370284'	=> [
			'zh-cn'			=> '胶南市',
			'en'			=> 'jiao nan shi',
		],
		'370285'	=> [
			'zh-cn'			=> '莱西市',
			'en'			=> 'lai xi shi',
		],
		'370300'	=> [
			'zh-cn'			=> '淄博市',
			'en'			=> 'zi bo shi',
		],
		'370302'	=> [
			'zh-cn'			=> '淄川区',
			'en'			=> 'zi chuan qu',
		],
		'370303'	=> [
			'zh-cn'			=> '张店区',
			'en'			=> 'zhang dian qu',
		],
		'370304'	=> [
			'zh-cn'			=> '博山区',
			'en'			=> 'bo shan qu',
		],
		'370305'	=> [
			'zh-cn'			=> '临淄区',
			'en'			=> 'lin zi qu',
		],
		'370306'	=> [
			'zh-cn'			=> '周村区',
			'en'			=> 'zhou cun qu',
		],
		'370321'	=> [
			'zh-cn'			=> '桓台县',
			'en'			=> 'huan tai xian',
		],
		'370322'	=> [
			'zh-cn'			=> '高青县',
			'en'			=> 'gao qing xian',
		],
		'370323'	=> [
			'zh-cn'			=> '沂源县',
			'en'			=> 'yi yuan xian',
		],
		'370400'	=> [
			'zh-cn'			=> '枣庄市',
			'en'			=> 'zao zhuang shi',
		],
		'370402'	=> [
			'zh-cn'			=> '市中区',
			'en'			=> 'shi zhong qu',
		],
		'370403'	=> [
			'zh-cn'			=> '薛城区',
			'en'			=> 'xue cheng qu',
		],
		'370404'	=> [
			'zh-cn'			=> '峄城区',
			'en'			=> 'yi cheng qu',
		],
		'370405'	=> [
			'zh-cn'			=> '台儿庄区',
			'en'			=> 'tai er zhuang qu',
		],
		'370406'	=> [
			'zh-cn'			=> '山亭区',
			'en'			=> 'shan ting qu',
		],
		'370481'	=> [
			'zh-cn'			=> '滕州市',
			'en'			=> 'teng zhou shi',
		],
		'370500'	=> [
			'zh-cn'			=> '东营市',
			'en'			=> 'dong ying shi',
		],
		'370502'	=> [
			'zh-cn'			=> '东营区',
			'en'			=> 'dong ying qu',
		],
		'370503'	=> [
			'zh-cn'			=> '河口区',
			'en'			=> 'he kou qu',
		],
		'370504'	=> [
			'zh-cn'			=> '牛庄区',
			'en'			=> 'niu zhuang qu',
		],
		'370505'	=> [
			'zh-cn'			=> '垦利区',
			'en'			=> 'ken li qu',
		],
		'370521'	=> [
			'zh-cn'			=> '垦利县',
			'en'			=> 'ken li xian',
		],
		'370522'	=> [
			'zh-cn'			=> '利津县',
			'en'			=> 'li jin xian',
		],
		'370523'	=> [
			'zh-cn'			=> '广饶县',
			'en'			=> 'guang rao xian',
		],
		'370600'	=> [
			'zh-cn'			=> '烟台市',
			'en'			=> 'yan tai shi',
		],
		'370602'	=> [
			'zh-cn'			=> '芝罘区',
			'en'			=> 'zhi fu qu',
		],
		'370611'	=> [
			'zh-cn'			=> '福山区',
			'en'			=> 'fu shan qu',
		],
		'370612'	=> [
			'zh-cn'			=> '牟平区',
			'en'			=> 'mu ping qu',
		],
		'370613'	=> [
			'zh-cn'			=> '莱山区',
			'en'			=> 'lai shan qu',
		],
		'370620'	=> [
			'zh-cn'			=> '威海市',
			'en'			=> 'wei hai shi',
		],
		'370622'	=> [
			'zh-cn'			=> '蓬莱县',
			'en'			=> 'peng lai xian',
		],
		'370624'	=> [
			'zh-cn'			=> '招远县',
			'en'			=> 'zhao yuan xian',
		],
		'370627'	=> [
			'zh-cn'			=> '莱阳县',
			'en'			=> 'lai yang xian',
		],
		'370628'	=> [
			'zh-cn'			=> '栖霞县',
			'en'			=> 'qi xia xian',
		],
		'370629'	=> [
			'zh-cn'			=> '海阳县',
			'en'			=> 'hai yang xian',
		],
		'370630'	=> [
			'zh-cn'			=> '乳山县',
			'en'			=> 'ru shan xian',
		],
		'370631'	=> [
			'zh-cn'			=> '牟平县',
			'en'			=> 'mu ping xian',
		],
		'370632'	=> [
			'zh-cn'			=> '文登县',
			'en'			=> 'wen deng xian',
		],
		'370633'	=> [
			'zh-cn'			=> '荣成县',
			'en'			=> 'rong cheng xian',
		],
		'370634'	=> [
			'zh-cn'			=> '长岛县',
			'en'			=> 'chang dao xian',
		],
		'370681'	=> [
			'zh-cn'			=> '龙口市',
			'en'			=> 'long kou shi',
		],
		'370682'	=> [
			'zh-cn'			=> '莱阳市',
			'en'			=> 'lai yang shi',
		],
		'370683'	=> [
			'zh-cn'			=> '莱州市',
			'en'			=> 'lai zhou shi',
		],
		'370684'	=> [
			'zh-cn'			=> '蓬莱市',
			'en'			=> 'peng lai shi',
		],
		'370685'	=> [
			'zh-cn'			=> '招远市',
			'en'			=> 'zhao yuan shi',
		],
		'370686'	=> [
			'zh-cn'			=> '栖霞市',
			'en'			=> 'xi xia shi',
		],
		'370687'	=> [
			'zh-cn'			=> '海阳市',
			'en'			=> 'hai yang shi',
		],
		'370700'	=> [
			'zh-cn'			=> '潍坊市',
			'en'			=> 'wei fang shi',
		],
		'370702'	=> [
			'zh-cn'			=> '潍城区',
			'en'			=> 'wei cheng qu',
		],
		'370703'	=> [
			'zh-cn'			=> '寒亭区',
			'en'			=> 'han ting qu',
		],
		'370704'	=> [
			'zh-cn'			=> '坊子区',
			'en'			=> 'fang zi qu',
		],
		'370705'	=> [
			'zh-cn'			=> '奎文区',
			'en'			=> 'kui wen qu',
		],
		'370721'	=> [
			'zh-cn'			=> '益都县',
			'en'			=> 'yi dou xian',
		],
		'370722'	=> [
			'zh-cn'			=> '安丘县',
			'en'			=> 'an qiu xian',
		],
		'370723'	=> [
			'zh-cn'			=> '寿光县',
			'en'			=> 'shou guang xian',
		],
		'370724'	=> [
			'zh-cn'			=> '临朐县',
			'en'			=> 'lin qu xian',
		],
		'370725'	=> [
			'zh-cn'			=> '昌乐县',
			'en'			=> 'chang le xian',
		],
		'370726'	=> [
			'zh-cn'			=> '昌邑县',
			'en'			=> 'chang yi xian',
		],
		'370727'	=> [
			'zh-cn'			=> '高密县',
			'en'			=> 'gao mi xian',
		],
		'370728'	=> [
			'zh-cn'			=> '诸城县',
			'en'			=> 'zhu cheng xian',
		],
		'370729'	=> [
			'zh-cn'			=> '五莲县',
			'en'			=> 'wu lian xian',
		],
		'370781'	=> [
			'zh-cn'			=> '青州市',
			'en'			=> 'qing zhou shi',
		],
		'370782'	=> [
			'zh-cn'			=> '诸城市',
			'en'			=> 'zhu cheng shi',
		],
		'370783'	=> [
			'zh-cn'			=> '寿光市',
			'en'			=> 'shou guang shi',
		],
		'370784'	=> [
			'zh-cn'			=> '安丘市',
			'en'			=> 'an qiu shi',
		],
		'370785'	=> [
			'zh-cn'			=> '高密市',
			'en'			=> 'gao mi shi',
		],
		'370786'	=> [
			'zh-cn'			=> '昌邑市',
			'en'			=> 'chang yi shi',
		],
		'370800'	=> [
			'zh-cn'			=> '济宁市',
			'en'			=> 'ji ning shi',
		],
		'370802'	=> [
			'zh-cn'			=> '市中区',
			'en'			=> 'shi zhong qu',
		],
		'370811'	=> [
			'zh-cn'			=> '任城区',
			'en'			=> 'ren cheng qu',
		],
		'370812'	=> [
			'zh-cn'			=> '兖州区',
			'en'			=> 'yan zhou qu',
		],
		'370822'	=> [
			'zh-cn'			=> '兖州县',
			'en'			=> 'yan zhou xian',
		],
		'370823'	=> [
			'zh-cn'			=> '曲阜县',
			'en'			=> 'qu fu xian',
		],
		'370826'	=> [
			'zh-cn'			=> '微山县',
			'en'			=> 'wei shan xian',
		],
		'370827'	=> [
			'zh-cn'			=> '鱼台县',
			'en'			=> 'yu tai xian',
		],
		'370828'	=> [
			'zh-cn'			=> '金乡县',
			'en'			=> 'jin xiang xian',
		],
		'370829'	=> [
			'zh-cn'			=> '嘉祥县',
			'en'			=> 'jia xiang xian',
		],
		'370830'	=> [
			'zh-cn'			=> '汶上县',
			'en'			=> 'wen shang xian',
		],
		'370831'	=> [
			'zh-cn'			=> '泗水县',
			'en'			=> 'si shui xian',
		],
		'370832'	=> [
			'zh-cn'			=> '梁山县',
			'en'			=> 'liang shan xian',
		],
		'370881'	=> [
			'zh-cn'			=> '曲阜市',
			'en'			=> 'qu fu shi',
		],
		'370882'	=> [
			'zh-cn'			=> '兖州市',
			'en'			=> 'yan zhou shi',
		],
		'370883'	=> [
			'zh-cn'			=> '邹城市',
			'en'			=> 'zou cheng shi',
		],
		'370900'	=> [
			'zh-cn'			=> '泰安市',
			'en'			=> 'tai an shi',
		],
		'370901'	=> [
			'zh-cn'			=> '莱芜市',
			'en'			=> 'lai wu shi',
		],
		'370902'	=> [
			'zh-cn'			=> '泰山区',
			'en'			=> 'tai shan qu',
		],
		'370911'	=> [
			'zh-cn'			=> '岱岳区',
			'en'			=> 'dai yue qu',
		],
		'370920'	=> [
			'zh-cn'			=> '新泰市',
			'en'			=> 'xin tai shi',
		],
		'370921'	=> [
			'zh-cn'			=> '宁阳县',
			'en'			=> 'ning yang xian',
		],
		'370922'	=> [
			'zh-cn'			=> '肥城县',
			'en'			=> 'fei cheng xian',
		],
		'370923'	=> [
			'zh-cn'			=> '东平县',
			'en'			=> 'dong ping xian',
		],
		'370982'	=> [
			'zh-cn'			=> '新泰市',
			'en'			=> 'xin tai shi',
		],
		'370983'	=> [
			'zh-cn'			=> '肥城市',
			'en'			=> 'fei cheng shi',
		],
		'371000'	=> [
			'zh-cn'			=> '威海市',
			'en'			=> 'wei hai shi',
		],
		'371001'	=> [
			'zh-cn'			=> '威海市',
			'en'			=> 'wei hai shi',
		],
		'371002'	=> [
			'zh-cn'			=> '环翠区',
			'en'			=> 'huan cui qu',
		],
		'371003'	=> [
			'zh-cn'			=> '文登区',
			'en'			=> 'wen deng qu',
		],
		'371021'	=> [
			'zh-cn'			=> '乳山县',
			'en'			=> 'ru shan xian',
		],
		'371022'	=> [
			'zh-cn'			=> '文登县',
			'en'			=> 'wen deng xian',
		],
		'371023'	=> [
			'zh-cn'			=> '荣成县',
			'en'			=> 'rong cheng xian',
		],
		'371081'	=> [
			'zh-cn'			=> '文登市',
			'en'			=> 'wen deng shi',
		],
		'371082'	=> [
			'zh-cn'			=> '荣成市',
			'en'			=> 'rong cheng shi',
		],
		'371083'	=> [
			'zh-cn'			=> '乳山市',
			'en'			=> 'ru shan shi',
		],
		'371100'	=> [
			'zh-cn'			=> '日照市',
			'en'			=> 'ri zhao shi',
		],
		'371102'	=> [
			'zh-cn'			=> '东港区',
			'en'			=> 'dong gang qu',
		],
		'371103'	=> [
			'zh-cn'			=> '岚山区',
			'en'			=> 'lan shan qu',
		],
		'371121'	=> [
			'zh-cn'			=> '五莲县',
			'en'			=> 'wu lian xian',
		],
		'371200'	=> [
			'zh-cn'			=> '莱芜市',
			'en'			=> 'lai wu shi',
		],
		'371202'	=> [
			'zh-cn'			=> '莱城区',
			'en'			=> 'lai cheng qu',
		],
		'371203'	=> [
			'zh-cn'			=> '钢城区',
			'en'			=> 'gang cheng qu',
		],
		'371300'	=> [
			'zh-cn'			=> '临沂市',
			'en'			=> 'lin yi shi',
		],
		'371302'	=> [
			'zh-cn'			=> '兰山区',
			'en'			=> 'lan shan qu',
		],
		'371311'	=> [
			'zh-cn'			=> '罗庄区',
			'en'			=> 'luo zhuang qu',
		],
		'371312'	=> [
			'zh-cn'			=> '河东区',
			'en'			=> 'he dong qu',
		],
		'371321'	=> [
			'zh-cn'			=> '沂南县',
			'en'			=> 'yi nan xian',
		],
		'371322'	=> [
			'zh-cn'			=> '郯城县',
			'en'			=> 'tan cheng xian',
		],
		'371323'	=> [
			'zh-cn'			=> '沂水县',
			'en'			=> 'yi shui xian',
		],
		'371324'	=> [
			'zh-cn'			=> '兰陵县',
			'en'			=> 'lan ling xian',
		],
		'371326'	=> [
			'zh-cn'			=> '平邑县',
			'en'			=> 'ping yi xian',
		],
		'371327'	=> [
			'zh-cn'			=> '莒南县',
			'en'			=> 'ju nan xian',
		],
		'371328'	=> [
			'zh-cn'			=> '蒙阴县',
			'en'			=> 'meng yin xian',
		],
		'371329'	=> [
			'zh-cn'			=> '临沭县',
			'en'			=> 'lin shu xian',
		],
		'371400'	=> [
			'zh-cn'			=> '德州市',
			'en'			=> 'de zhou shi',
		],
		'371402'	=> [
			'zh-cn'			=> '德城区',
			'en'			=> 'de cheng qu',
		],
		'371403'	=> [
			'zh-cn'			=> '陵城区',
			'en'			=> 'ling cheng qu',
		],
		'371422'	=> [
			'zh-cn'			=> '宁津县',
			'en'			=> 'ning jin xian',
		],
		'371423'	=> [
			'zh-cn'			=> '庆云县',
			'en'			=> 'qing yun xian',
		],
		'371424'	=> [
			'zh-cn'			=> '临邑县',
			'en'			=> 'lin yi xian',
		],
		'371425'	=> [
			'zh-cn'			=> '齐河县',
			'en'			=> 'qi he xian',
		],
		'371426'	=> [
			'zh-cn'			=> '平原县',
			'en'			=> 'ping yuan xian',
		],
		'371427'	=> [
			'zh-cn'			=> '夏津县',
			'en'			=> 'xia jin xian',
		],
		'371428'	=> [
			'zh-cn'			=> '武城县',
			'en'			=> 'wu cheng xian',
		],
		'371481'	=> [
			'zh-cn'			=> '乐陵市',
			'en'			=> 'le ling shi',
		],
		'371482'	=> [
			'zh-cn'			=> '禹城市',
			'en'			=> 'yu cheng shi',
		],
		'371500'	=> [
			'zh-cn'			=> '聊城市',
			'en'			=> 'liao cheng shi',
		],
		'371502'	=> [
			'zh-cn'			=> '东昌府区',
			'en'			=> 'dong chang fu qu',
		],
		'371521'	=> [
			'zh-cn'			=> '阳谷县',
			'en'			=> 'yang gu xian',
		],
		'371523'	=> [
			'zh-cn'			=> '茌平县',
			'en'			=> 'chi ping xian',
		],
		'371524'	=> [
			'zh-cn'			=> '东阿县',
			'en'			=> 'dong e xian',
		],
		'371526'	=> [
			'zh-cn'			=> '高唐县',
			'en'			=> 'gao tang xian',
		],
		'371581'	=> [
			'zh-cn'			=> '临清市',
			'en'			=> 'lin qing shi',
		],
		'371600'	=> [
			'zh-cn'			=> '滨州市',
			'en'			=> 'bin zhou shi',
		],
		'371602'	=> [
			'zh-cn'			=> '滨城区',
			'en'			=> 'bin cheng qu',
		],
		'371603'	=> [
			'zh-cn'			=> '沾化区',
			'en'			=> 'zhan hua qu',
		],
		'371621'	=> [
			'zh-cn'			=> '惠民县',
			'en'			=> 'hui min xian',
		],
		'371622'	=> [
			'zh-cn'			=> '阳信县',
			'en'			=> 'yang xin xian',
		],
		'371623'	=> [
			'zh-cn'			=> '无棣县',
			'en'			=> 'wu di xian',
		],
		'371624'	=> [
			'zh-cn'			=> '沾化县',
			'en'			=> 'zhan hua xian',
		],
		'371625'	=> [
			'zh-cn'			=> '博兴县',
			'en'			=> 'bo xing xian',
		],
		'371626'	=> [
			'zh-cn'			=> '邹平县',
			'en'			=> 'zou ping xian',
		],
		'371700'	=> [
			'zh-cn'			=> '菏泽市',
			'en'			=> 'he ze shi',
		],
		'371702'	=> [
			'zh-cn'			=> '牡丹区',
			'en'			=> 'mu dan qu',
		],
		'371703'	=> [
			'zh-cn'			=> '定陶区',
			'en'			=> 'ding tao qu',
		],
		'371723'	=> [
			'zh-cn'			=> '成武县',
			'en'			=> 'cheng wu xian',
		],
		'371724'	=> [
			'zh-cn'			=> '巨野县',
			'en'			=> 'ju ye xian',
		],
		'371725'	=> [
			'zh-cn'			=> '郓城县',
			'en'			=> 'yun cheng xian',
		],
		'371726'	=> [
			'zh-cn'			=> '鄄城县',
			'en'			=> 'juan cheng xian',
		],
		'371727'	=> [
			'zh-cn'			=> '定陶县',
			'en'			=> 'ding tao xian',
		],
		'371728'	=> [
			'zh-cn'			=> '东明县',
			'en'			=> 'dong ming xian',
		],
		'372100'	=> [
			'zh-cn'			=> '烟台地区',
			'en'			=> 'yan tai di qu',
		],
		'372101'	=> [
			'zh-cn'			=> '烟台市',
			'en'			=> 'yan tai shi',
		],
		'372102'	=> [
			'zh-cn'			=> '威海市',
			'en'			=> 'wei hai shi',
		],
		'372121'	=> [
			'zh-cn'			=> '福山县',
			'en'			=> 'fu shan xian',
		],
		'372122'	=> [
			'zh-cn'			=> '蓬莱县',
			'en'			=> 'peng lai xian',
		],
		'372124'	=> [
			'zh-cn'			=> '招远县',
			'en'			=> 'zhao yuan xian',
		],
		'372126'	=> [
			'zh-cn'			=> '莱西县',
			'en'			=> 'lai xi xian',
		],
		'372127'	=> [
			'zh-cn'			=> '莱阳县',
			'en'			=> 'lai yang xian',
		],
		'372128'	=> [
			'zh-cn'			=> '栖霞县',
			'en'			=> 'qi xia xian',
		],
		'372129'	=> [
			'zh-cn'			=> '海阳县',
			'en'			=> 'hai yang xian',
		],
		'372130'	=> [
			'zh-cn'			=> '乳山县',
			'en'			=> 'ru shan xian',
		],
		'372131'	=> [
			'zh-cn'			=> '牟平县',
			'en'			=> 'mu ping xian',
		],
		'372132'	=> [
			'zh-cn'			=> '文登县',
			'en'			=> 'wen deng xian',
		],
		'372133'	=> [
			'zh-cn'			=> '荣成县',
			'en'			=> 'rong cheng xian',
		],
		'372134'	=> [
			'zh-cn'			=> '长岛县',
			'en'			=> 'chang dao xian',
		],
		'372200'	=> [
			'zh-cn'			=> '潍坊地区',
			'en'			=> 'wei fang di qu',
		],
		'372201'	=> [
			'zh-cn'			=> '潍坊市',
			'en'			=> 'wei fang shi',
		],
		'372221'	=> [
			'zh-cn'			=> '益都县',
			'en'			=> 'yi dou xian',
		],
		'372222'	=> [
			'zh-cn'			=> '安丘县',
			'en'			=> 'an qiu xian',
		],
		'372223'	=> [
			'zh-cn'			=> '寿光县',
			'en'			=> 'shou guang xian',
		],
		'372224'	=> [
			'zh-cn'			=> '临朐县',
			'en'			=> 'lin qu xian',
		],
		'372225'	=> [
			'zh-cn'			=> '昌乐县',
			'en'			=> 'chang le xian',
		],
		'372226'	=> [
			'zh-cn'			=> '昌邑县',
			'en'			=> 'chang yi xian',
		],
		'372227'	=> [
			'zh-cn'			=> '高密县',
			'en'			=> 'gao mi xian',
		],
		'372228'	=> [
			'zh-cn'			=> '诸城县',
			'en'			=> 'zhu cheng xian',
		],
		'372229'	=> [
			'zh-cn'			=> '五莲县',
			'en'			=> 'wu lian xian',
		],
		'372231'	=> [
			'zh-cn'			=> '平度县',
			'en'			=> 'ping du xian',
		],
		'372300'	=> [
			'zh-cn'			=> '滨州地区',
			'en'			=> 'bin zhou di qu',
		],
		'372301'	=> [
			'zh-cn'			=> '滨州市',
			'en'			=> 'bin zhou shi',
		],
		'372321'	=> [
			'zh-cn'			=> '惠民县',
			'en'			=> 'hui min xian',
		],
		'372323'	=> [
			'zh-cn'			=> '阳信县',
			'en'			=> 'yang xin xian',
		],
		'372324'	=> [
			'zh-cn'			=> '无棣县',
			'en'			=> 'wu di xian',
		],
		'372325'	=> [
			'zh-cn'			=> '沾化县',
			'en'			=> 'zhan hua xian',
		],
		'372326'	=> [
			'zh-cn'			=> '桓台县',
			'en'			=> 'huan tai xian',
		],
		'372327'	=> [
			'zh-cn'			=> '广饶县',
			'en'			=> 'guang rao xian',
		],
		'372328'	=> [
			'zh-cn'			=> '博兴县',
			'en'			=> 'bo xing xian',
		],
		'372329'	=> [
			'zh-cn'			=> '垦利县',
			'en'			=> 'ken li xian',
		],
		'372330'	=> [
			'zh-cn'			=> '邹平县',
			'en'			=> 'zou ping xian',
		],
		'372331'	=> [
			'zh-cn'			=> '高青县',
			'en'			=> 'gao qing xian',
		],
		'372332'	=> [
			'zh-cn'			=> '利津县',
			'en'			=> 'li jin xian',
		],
		'372400'	=> [
			'zh-cn'			=> '德州地区',
			'en'			=> 'de zhou di qu',
		],
		'372401'	=> [
			'zh-cn'			=> '德州市',
			'en'			=> 'de zhou shi',
		],
		'372402'	=> [
			'zh-cn'			=> '乐陵市',
			'en'			=> 'le ling shi',
		],
		'372403'	=> [
			'zh-cn'			=> '禹城市',
			'en'			=> 'yu cheng shi',
		],
		'372422'	=> [
			'zh-cn'			=> '平原县',
			'en'			=> 'ping yuan xian',
		],
		'372423'	=> [
			'zh-cn'			=> '夏津县',
			'en'			=> 'xia jin xian',
		],
		'372424'	=> [
			'zh-cn'			=> '武城县',
			'en'			=> 'wu cheng xian',
		],
		'372425'	=> [
			'zh-cn'			=> '齐河县',
			'en'			=> 'qi he xian',
		],
		'372426'	=> [
			'zh-cn'			=> '禹城县',
			'en'			=> 'yu cheng xian',
		],
		'372427'	=> [
			'zh-cn'			=> '乐陵县',
			'en'			=> 'le ling xian',
		],
		'372428'	=> [
			'zh-cn'			=> '临邑县',
			'en'			=> 'lin yi xian',
		],
		'372429'	=> [
			'zh-cn'			=> '商河县',
			'en'			=> 'shang he xian',
		],
		'372430'	=> [
			'zh-cn'			=> '济阳县',
			'en'			=> 'ji yang xian',
		],
		'372431'	=> [
			'zh-cn'			=> '宁津县',
			'en'			=> 'ning jin xian',
		],
		'372432'	=> [
			'zh-cn'			=> '庆云县',
			'en'			=> 'qing yun xian',
		],
		'372500'	=> [
			'zh-cn'			=> '聊城地区',
			'en'			=> 'liao cheng di qu',
		],
		'372501'	=> [
			'zh-cn'			=> '聊城市',
			'en'			=> 'liao cheng shi',
		],
		'372502'	=> [
			'zh-cn'			=> '临清市',
			'en'			=> 'lin qing shi',
		],
		'372521'	=> [
			'zh-cn'			=> '聊城县',
			'en'			=> 'liao cheng xian',
		],
		'372522'	=> [
			'zh-cn'			=> '阳谷县',
			'en'			=> 'yang gu xian',
		],
		'372524'	=> [
			'zh-cn'			=> '茌平县',
			'en'			=> 'chi ping xian',
		],
		'372525'	=> [
			'zh-cn'			=> '东阿县',
			'en'			=> 'dong e xian',
		],
		'372527'	=> [
			'zh-cn'			=> '高唐县',
			'en'			=> 'gao tang xian',
		],
		'372528'	=> [
			'zh-cn'			=> '临清县',
			'en'			=> 'lin qing xian',
		],
		'372600'	=> [
			'zh-cn'			=> '泰安地区',
			'en'			=> 'tai an di qu',
		],
		'372601'	=> [
			'zh-cn'			=> '泰安市',
			'en'			=> 'tai an shi',
		],
		'372602'	=> [
			'zh-cn'			=> '莱芜市',
			'en'			=> 'lai wu shi',
		],
		'372603'	=> [
			'zh-cn'			=> '新泰市',
			'en'			=> 'xin tai shi',
		],
		'372621'	=> [
			'zh-cn'			=> '莱芜县',
			'en'			=> 'lai wu xian',
		],
		'372622'	=> [
			'zh-cn'			=> '新泰县',
			'en'			=> 'xin tai xian',
		],
		'372623'	=> [
			'zh-cn'			=> '泰安县',
			'en'			=> 'tai an xian',
		],
		'372624'	=> [
			'zh-cn'			=> '宁阳县',
			'en'			=> 'ning yang xian',
		],
		'372625'	=> [
			'zh-cn'			=> '肥城县',
			'en'			=> 'fei cheng xian',
		],
		'372626'	=> [
			'zh-cn'			=> '东平县',
			'en'			=> 'dong ping xian',
		],
		'372627'	=> [
			'zh-cn'			=> '平阴县',
			'en'			=> 'ping yin xian',
		],
		'372628'	=> [
			'zh-cn'			=> '新汶县',
			'en'			=> 'xin wen xian',
		],
		'372629'	=> [
			'zh-cn'			=> '汶上县',
			'en'			=> 'wen shang xian',
		],
		'372630'	=> [
			'zh-cn'			=> '泗水县',
			'en'			=> 'si shui xian',
		],
		'372700'	=> [
			'zh-cn'			=> '济宁地区',
			'en'			=> 'ji ning di qu',
		],
		'372701'	=> [
			'zh-cn'			=> '济宁市',
			'en'			=> 'ji ning shi',
		],
		'372721'	=> [
			'zh-cn'			=> '济宁县',
			'en'			=> 'ji ning xian',
		],
		'372722'	=> [
			'zh-cn'			=> '兖州县',
			'en'			=> 'yan zhou xian',
		],
		'372723'	=> [
			'zh-cn'			=> '曲阜县',
			'en'			=> 'qu fu xian',
		],
		'372724'	=> [
			'zh-cn'			=> '泗水县',
			'en'			=> 'si shui xian',
		],
		'372726'	=> [
			'zh-cn'			=> '微山县',
			'en'			=> 'wei shan xian',
		],
		'372727'	=> [
			'zh-cn'			=> '鱼台县',
			'en'			=> 'yu tai xian',
		],
		'372728'	=> [
			'zh-cn'			=> '金乡县',
			'en'			=> 'jin xiang xian',
		],
		'372729'	=> [
			'zh-cn'			=> '嘉祥县',
			'en'			=> 'jia xiang xian',
		],
		'372730'	=> [
			'zh-cn'			=> '汶上县',
			'en'			=> 'wen shang xian',
		],
		'372800'	=> [
			'zh-cn'			=> '临沂地区',
			'en'			=> 'lin yi di qu',
		],
		'372801'	=> [
			'zh-cn'			=> '临沂市',
			'en'			=> 'lin yi shi',
		],
		'372821'	=> [
			'zh-cn'			=> '临沂县',
			'en'			=> 'lin yi xian',
		],
		'372822'	=> [
			'zh-cn'			=> '郯城县',
			'en'			=> 'tan cheng xian',
		],
		'372823'	=> [
			'zh-cn'			=> '苍山县',
			'en'			=> 'cang shan xian',
		],
		'372824'	=> [
			'zh-cn'			=> '莒南县',
			'en'			=> 'ju nan xian',
		],
		'372825'	=> [
			'zh-cn'			=> '日照县',
			'en'			=> 'ri zhao xian',
		],
		'372827'	=> [
			'zh-cn'			=> '沂水县',
			'en'			=> 'yi shui xian',
		],
		'372828'	=> [
			'zh-cn'			=> '沂源县',
			'en'			=> 'yi yuan xian',
		],
		'372829'	=> [
			'zh-cn'			=> '蒙阴县',
			'en'			=> 'meng yin xian',
		],
		'372830'	=> [
			'zh-cn'			=> '平邑县',
			'en'			=> 'ping yi xian',
		],
		'372832'	=> [
			'zh-cn'			=> '沂南县',
			'en'			=> 'yi nan xian',
		],
		'372833'	=> [
			'zh-cn'			=> '临沭县',
			'en'			=> 'lin shu xian',
		],
		'372900'	=> [
			'zh-cn'			=> '菏泽地区',
			'en'			=> 'he ze di qu',
		],
		'372901'	=> [
			'zh-cn'			=> '菏泽市',
			'en'			=> 'he ze shi',
		],
		'372921'	=> [
			'zh-cn'			=> '菏泽县',
			'en'			=> 'he ze xian',
		],
		'372923'	=> [
			'zh-cn'			=> '定陶县',
			'en'			=> 'ding tao xian',
		],
		'372924'	=> [
			'zh-cn'			=> '成武县',
			'en'			=> 'cheng wu xian',
		],
		'372926'	=> [
			'zh-cn'			=> '巨野县',
			'en'			=> 'ju ye xian',
		],
		'372927'	=> [
			'zh-cn'			=> '梁山县',
			'en'			=> 'liang shan xian',
		],
		'372928'	=> [
			'zh-cn'			=> '郓城县',
			'en'			=> 'yun cheng xian',
		],
		'372929'	=> [
			'zh-cn'			=> '鄄城县',
			'en'			=> 'juan cheng xian',
		],
		'372930'	=> [
			'zh-cn'			=> '东明县',
			'en'			=> 'dong ming xian',
		],
		'379001'	=> [
			'zh-cn'			=> '青州市',
			'en'			=> 'qing zhou shi',
		],
		'379002'	=> [
			'zh-cn'			=> '龙口市',
			'en'			=> 'long kou shi',
		],
		'379003'	=> [
			'zh-cn'			=> '曲阜市',
			'en'			=> 'qu fu shi',
		],
		'379004'	=> [
			'zh-cn'			=> '莱芜市',
			'en'			=> 'lai wu shi',
		],
		'379005'	=> [
			'zh-cn'			=> '新泰市',
			'en'			=> 'xin tai shi',
		],
		'379006'	=> [
			'zh-cn'			=> '胶州市',
			'en'			=> 'jiao zhou shi',
		],
		'379007'	=> [
			'zh-cn'			=> '诸城市',
			'en'			=> 'zhu cheng shi',
		],
		'379008'	=> [
			'zh-cn'			=> '莱阳市',
			'en'			=> 'lai yang shi',
		],
		'379009'	=> [
			'zh-cn'			=> '莱州市',
			'en'			=> 'lai zhou shi',
		],
		'379010'	=> [
			'zh-cn'			=> '滕州市',
			'en'			=> 'teng zhou shi',
		],
		'379011'	=> [
			'zh-cn'			=> '文登市',
			'en'			=> 'wen deng shi',
		],
		'379012'	=> [
			'zh-cn'			=> '荣成市',
			'en'			=> 'rong cheng shi',
		],
		'379013'	=> [
			'zh-cn'			=> '即墨市',
			'en'			=> 'ji mo shi',
		],
		'379014'	=> [
			'zh-cn'			=> '平度市',
			'en'			=> 'ping du shi',
		],
		'379015'	=> [
			'zh-cn'			=> '莱西市',
			'en'			=> 'lai xi shi',
		],
		'379016'	=> [
			'zh-cn'			=> '胶南市',
			'en'			=> 'jiao nan shi',
		],
		'379017'	=> [
			'zh-cn'			=> '蓬莱市',
			'en'			=> 'peng lai shi',
		],
		'379018'	=> [
			'zh-cn'			=> '招远市',
			'en'			=> 'zhao yuan shi',
		],
		'379019'	=> [
			'zh-cn'			=> '寿光市',
			'en'			=> 'shou guang shi',
		],
		'379020'	=> [
			'zh-cn'			=> '乳山市',
			'en'			=> 'ru shan shi',
		],
		'379021'	=> [
			'zh-cn'			=> '乐陵市',
			'en'			=> 'le ling shi',
		],
		'379022'	=> [
			'zh-cn'			=> '禹城市',
			'en'			=> 'yu cheng shi',
		],
		'379023'	=> [
			'zh-cn'			=> '安丘市',
			'en'			=> 'an qiu shi',
		],
		'379024'	=> [
			'zh-cn'			=> '昌邑市',
			'en'			=> 'chang yi shi',
		],
		'379025'	=> [
			'zh-cn'			=> '高密市',
			'en'			=> 'gao mi shi',
		],
		'410000'	=> [
			'zh-cn'			=> '河南省',
			'en'			=> 'he nan sheng',
		],
		'410100'	=> [
			'zh-cn'			=> '郑州市',
			'en'			=> 'zheng zhou shi',
		],
		'410102'	=> [
			'zh-cn'			=> '中原区',
			'en'			=> 'zhong yuan qu',
		],
		'410103'	=> [
			'zh-cn'			=> '二七区',
			'en'			=> 'er qi qu',
		],
		'410104'	=> [
			'zh-cn'			=> '管城回族区',
			'en'			=> 'guan cheng hui zu qu',
		],
		'410105'	=> [
			'zh-cn'			=> '金水区',
			'en'			=> 'jin shui qu',
		],
		'410106'	=> [
			'zh-cn'			=> '上街区',
			'en'			=> 'shang jie qu',
		],
		'410107'	=> [
			'zh-cn'			=> '新密区',
			'en'			=> 'xin mi qu',
		],
		'410108'	=> [
			'zh-cn'			=> '惠济区',
			'en'			=> 'hui ji qu',
		],
		'410111'	=> [
			'zh-cn'			=> '金海区',
			'en'			=> 'jin hai qu',
		],
		'410121'	=> [
			'zh-cn'			=> '荥阳县',
			'en'			=> 'xing yang xian',
		],
		'410122'	=> [
			'zh-cn'			=> '中牟县',
			'en'			=> 'zhong mu xian',
		],
		'410123'	=> [
			'zh-cn'			=> '新郑县',
			'en'			=> 'xin zheng xian',
		],
		'410125'	=> [
			'zh-cn'			=> '登封县',
			'en'			=> 'deng feng xian',
		],
		'410181'	=> [
			'zh-cn'			=> '巩义市',
			'en'			=> 'gong yi shi',
		],
		'410182'	=> [
			'zh-cn'			=> '荥阳市',
			'en'			=> 'xing yang shi',
		],
		'410183'	=> [
			'zh-cn'			=> '新密市',
			'en'			=> 'xin mi shi',
		],
		'410184'	=> [
			'zh-cn'			=> '新郑市',
			'en'			=> 'xin zheng shi',
		],
		'410185'	=> [
			'zh-cn'			=> '登封市',
			'en'			=> 'deng feng shi',
		],
		'410200'	=> [
			'zh-cn'			=> '开封市',
			'en'			=> 'kai feng shi',
		],
		'410202'	=> [
			'zh-cn'			=> '龙亭区',
			'en'			=> 'long ting qu',
		],
		'410203'	=> [
			'zh-cn'			=> '顺河回族区',
			'en'			=> 'shun he hui zu qu',
		],
		'410204'	=> [
			'zh-cn'			=> '鼓楼区',
			'en'			=> 'gu lou qu',
		],
		'410205'	=> [
			'zh-cn'			=> '禹王台区',
			'en'			=> 'yu wang tai qu',
		],
		'410211'	=> [
			'zh-cn'			=> '金明区',
			'en'			=> 'jin ming qu',
		],
		'410212'	=> [
			'zh-cn'			=> '祥符区',
			'en'			=> 'xiang fu qu',
		],
		'410222'	=> [
			'zh-cn'			=> '通许县',
			'en'			=> 'tong xu xian',
		],
		'410223'	=> [
			'zh-cn'			=> '尉氏县',
			'en'			=> 'wei shi xian',
		],
		'410224'	=> [
			'zh-cn'			=> '开封县',
			'en'			=> 'kai feng xian',
		],
		'410225'	=> [
			'zh-cn'			=> '兰考县',
			'en'			=> 'lan kao xian',
		],
		'410300'	=> [
			'zh-cn'			=> '洛阳市',
			'en'			=> 'luo yang shi',
		],
		'410302'	=> [
			'zh-cn'			=> '老城区',
			'en'			=> 'lao cheng qu',
		],
		'410303'	=> [
			'zh-cn'			=> '西工区',
			'en'			=> 'xi gong qu',
		],
		'410304'	=> [
			'zh-cn'			=> '瀍河回族区',
			'en'			=> 'chan he hui zu qu',
		],
		'410305'	=> [
			'zh-cn'			=> '涧西区',
			'en'			=> 'jian xi qu',
		],
		'410306'	=> [
			'zh-cn'			=> '吉利区',
			'en'			=> 'ji li qu',
		],
		'410311'	=> [
			'zh-cn'			=> '洛龙区',
			'en'			=> 'luo long qu',
		],
		'410321'	=> [
			'zh-cn'			=> '偃师县',
			'en'			=> 'yan shi xian',
		],
		'410322'	=> [
			'zh-cn'			=> '孟津县',
			'en'			=> 'meng jin xian',
		],
		'410323'	=> [
			'zh-cn'			=> '新安县',
			'en'			=> 'xin an xian',
		],
		'410324'	=> [
			'zh-cn'			=> '栾川县',
			'en'			=> 'luan chuan xian',
		],
		'410326'	=> [
			'zh-cn'			=> '汝阳县',
			'en'			=> 'ru yang xian',
		],
		'410327'	=> [
			'zh-cn'			=> '宜阳县',
			'en'			=> 'yi yang xian',
		],
		'410328'	=> [
			'zh-cn'			=> '洛宁县',
			'en'			=> 'luo ning xian',
		],
		'410329'	=> [
			'zh-cn'			=> '伊川县',
			'en'			=> 'yi chuan xian',
		],
		'410381'	=> [
			'zh-cn'			=> '偃师市',
			'en'			=> 'yan shi shi',
		],
		'410400'	=> [
			'zh-cn'			=> '平顶山市',
			'en'			=> 'ping ding shan shi',
		],
		'410402'	=> [
			'zh-cn'			=> '新华区',
			'en'			=> 'xin hua qu',
		],
		'410403'	=> [
			'zh-cn'			=> '卫东区',
			'en'			=> 'wei dong qu',
		],
		'410404'	=> [
			'zh-cn'			=> '石龙区',
			'en'			=> 'shi long qu',
		],
		'410411'	=> [
			'zh-cn'			=> '湛河区',
			'en'			=> 'zhan he qu',
		],
		'410412'	=> [
			'zh-cn'			=> '舞钢区',
			'en'			=> 'wu gang qu',
		],
		'410421'	=> [
			'zh-cn'			=> '宝丰县',
			'en'			=> 'bao feng xian',
		],
		'410423'	=> [
			'zh-cn'			=> '鲁山县',
			'en'			=> 'lu shan xian',
		],
		'410424'	=> [
			'zh-cn'			=> '临汝县',
			'en'			=> 'lin ru xian',
		],
		'410426'	=> [
			'zh-cn'			=> '襄城县',
			'en'			=> 'xiang cheng xian',
		],
		'410481'	=> [
			'zh-cn'			=> '舞钢市',
			'en'			=> 'wu gang shi',
		],
		'410482'	=> [
			'zh-cn'			=> '汝州市',
			'en'			=> 'ru zhou shi',
		],
		'410500'	=> [
			'zh-cn'			=> '安阳市',
			'en'			=> 'an yang shi',
		],
		'410502'	=> [
			'zh-cn'			=> '文峰区',
			'en'			=> 'wen feng qu',
		],
		'410503'	=> [
			'zh-cn'			=> '北关区',
			'en'			=> 'bei guan qu',
		],
		'410504'	=> [
			'zh-cn'			=> '铁西区',
			'en'			=> 'tie xi qu',
		],
		'410505'	=> [
			'zh-cn'			=> '殷都区',
			'en'			=> 'yin du qu',
		],
		'410506'	=> [
			'zh-cn'			=> '龙安区',
			'en'			=> 'long an qu',
		],
		'410522'	=> [
			'zh-cn'			=> '安阳县',
			'en'			=> 'an yang xian',
		],
		'410523'	=> [
			'zh-cn'			=> '汤阴县',
			'en'			=> 'tang yin xian',
		],
		'410527'	=> [
			'zh-cn'			=> '内黄县',
			'en'			=> 'nei huang xian',
		],
		'410581'	=> [
			'zh-cn'			=> '林州市',
			'en'			=> 'lin zhou shi',
		],
		'410600'	=> [
			'zh-cn'			=> '鹤壁市',
			'en'			=> 'he bi shi',
		],
		'410602'	=> [
			'zh-cn'			=> '鹤山区',
			'en'			=> 'he shan qu',
		],
		'410603'	=> [
			'zh-cn'			=> '山城区',
			'en'			=> 'shan cheng qu',
		],
		'410611'	=> [
			'zh-cn'			=> '淇滨区',
			'en'			=> 'qi bin qu',
		],
		'410700'	=> [
			'zh-cn'			=> '新乡市',
			'en'			=> 'xin xiang shi',
		],
		'410702'	=> [
			'zh-cn'			=> '红旗区',
			'en'			=> 'hong qi qu',
		],
		'410703'	=> [
			'zh-cn'			=> '卫滨区',
			'en'			=> 'wei bin qu',
		],
		'410704'	=> [
			'zh-cn'			=> '凤泉区',
			'en'			=> 'feng quan qu',
		],
		'410711'	=> [
			'zh-cn'			=> '牧野区',
			'en'			=> 'mu ye qu',
		],
		'410721'	=> [
			'zh-cn'			=> '新乡县',
			'en'			=> 'xin xiang xian',
		],
		'410724'	=> [
			'zh-cn'			=> '获嘉县',
			'en'			=> 'huo jia xian',
		],
		'410725'	=> [
			'zh-cn'			=> '原阳县',
			'en'			=> 'yuan yang xian',
		],
		'410726'	=> [
			'zh-cn'			=> '延津县',
			'en'			=> 'yan jin xian',
		],
		'410727'	=> [
			'zh-cn'			=> '封丘县',
			'en'			=> 'feng qiu xian',
		],
		'410728'	=> [
			'zh-cn'			=> '长垣县',
			'en'			=> 'chang yuan xian',
		],
		'410781'	=> [
			'zh-cn'			=> '卫辉市',
			'en'			=> 'wei hui shi',
		],
		'410782'	=> [
			'zh-cn'			=> '辉县市',
			'en'			=> 'hui xian shi',
		],
		'410800'	=> [
			'zh-cn'			=> '焦作市',
			'en'			=> 'jiao zuo shi',
		],
		'410802'	=> [
			'zh-cn'			=> '解放区',
			'en'			=> 'jie fang qu',
		],
		'410803'	=> [
			'zh-cn'			=> '中站区',
			'en'			=> 'zhong zhan qu',
		],
		'410804'	=> [
			'zh-cn'			=> '马村区',
			'en'			=> 'ma cun qu',
		],
		'410811'	=> [
			'zh-cn'			=> '山阳区',
			'en'			=> 'shan yang qu',
		],
		'410821'	=> [
			'zh-cn'			=> '修武县',
			'en'			=> 'xiu wu xian',
		],
		'410822'	=> [
			'zh-cn'			=> '博爱县',
			'en'			=> 'bo ai xian',
		],
		'410823'	=> [
			'zh-cn'			=> '武陟县',
			'en'			=> 'wu zhi xian',
		],
		'410824'	=> [
			'zh-cn'			=> '沁阳县',
			'en'			=> 'qin yang xian',
		],
		'410827'	=> [
			'zh-cn'			=> '济源县',
			'en'			=> 'ji yuan xian',
		],
		'410881'	=> [
			'zh-cn'			=> '济源市',
			'en'			=> 'ji yuan shi',
		],
		'410882'	=> [
			'zh-cn'			=> '沁阳市',
			'en'			=> 'qin yang shi',
		],
		'410883'	=> [
			'zh-cn'			=> '孟州市',
			'en'			=> 'meng zhou shi',
		],
		'410900'	=> [
			'zh-cn'			=> '濮阳市',
			'en'			=> 'pu yang shi',
		],
		'410902'	=> [
			'zh-cn'			=> '华龙区',
			'en'			=> 'hua long qu',
		],
		'410922'	=> [
			'zh-cn'			=> '清丰县',
			'en'			=> 'qing feng xian',
		],
		'410923'	=> [
			'zh-cn'			=> '南乐县',
			'en'			=> 'nan le xian',
		],
		'410924'	=> [
			'zh-cn'			=> '内黄县',
			'en'			=> 'nei huang xian',
		],
		'410925'	=> [
			'zh-cn'			=> '长垣县',
			'en'			=> 'chang yuan xian',
		],
		'410927'	=> [
			'zh-cn'			=> '台前县',
			'en'			=> 'tai qian xian',
		],
		'410928'	=> [
			'zh-cn'			=> '濮阳县',
			'en'			=> 'pu yang xian',
		],
		'411000'	=> [
			'zh-cn'			=> '许昌市',
			'en'			=> 'xu chang shi',
		],
		'411002'	=> [
			'zh-cn'			=> '魏都区',
			'en'			=> 'wei du qu',
		],
		'411003'	=> [
			'zh-cn'			=> '建安区',
			'en'			=> 'jian an qu',
		],
		'411022'	=> [
			'zh-cn'			=> '长葛县',
			'en'			=> 'chang ge xian',
		],
		'411023'	=> [
			'zh-cn'			=> '许昌县',
			'en'			=> 'xu chang xian',
		],
		'411024'	=> [
			'zh-cn'			=> '鄢陵县',
			'en'			=> 'yan ling xian',
		],
		'411025'	=> [
			'zh-cn'			=> '襄城县',
			'en'			=> 'xiang cheng xian',
		],
		'411081'	=> [
			'zh-cn'			=> '禹州市',
			'en'			=> 'yu zhou shi',
		],
		'411082'	=> [
			'zh-cn'			=> '长葛市',
			'en'			=> 'chang ge shi',
		],
		'411100'	=> [
			'zh-cn'			=> '漯河市',
			'en'			=> 'ta he shi',
		],
		'411102'	=> [
			'zh-cn'			=> '源汇区',
			'en'			=> 'yuan hui qu',
		],
		'411103'	=> [
			'zh-cn'			=> '郾城区',
			'en'			=> 'yan cheng qu',
		],
		'411104'	=> [
			'zh-cn'			=> '召陵区',
			'en'			=> 'shao ling qu',
		],
		'411121'	=> [
			'zh-cn'			=> '舞阳县',
			'en'			=> 'wu yang xian',
		],
		'411122'	=> [
			'zh-cn'			=> '临颍县',
			'en'			=> 'lin ying xian',
		],
		'411123'	=> [
			'zh-cn'			=> '郾城县',
			'en'			=> 'yan cheng xian',
		],
		'411200'	=> [
			'zh-cn'			=> '三门峡市',
			'en'			=> 'san men xia shi',
		],
		'411202'	=> [
			'zh-cn'			=> '湖滨区',
			'en'			=> 'hu bin qu',
		],
		'411203'	=> [
			'zh-cn'			=> '陕州区',
			'en'			=> 'shan zhou qu',
		],
		'411221'	=> [
			'zh-cn'			=> '渑池县',
			'en'			=> 'mian chi xian',
		],
		'411223'	=> [
			'zh-cn'			=> '灵宝县',
			'en'			=> 'ling bao xian',
		],
		'411224'	=> [
			'zh-cn'			=> '卢氏县',
			'en'			=> 'lu shi xian',
		],
		'411281'	=> [
			'zh-cn'			=> '义马市',
			'en'			=> 'yi ma shi',
		],
		'411282'	=> [
			'zh-cn'			=> '灵宝市',
			'en'			=> 'ling bao shi',
		],
		'411300'	=> [
			'zh-cn'			=> '南阳市',
			'en'			=> 'nan yang shi',
		],
		'411302'	=> [
			'zh-cn'			=> '宛城区',
			'en'			=> 'wan cheng qu',
		],
		'411303'	=> [
			'zh-cn'			=> '卧龙区',
			'en'			=> 'wo long qu',
		],
		'411321'	=> [
			'zh-cn'			=> '南召县',
			'en'			=> 'nan zhao xian',
		],
		'411322'	=> [
			'zh-cn'			=> '方城县',
			'en'			=> 'fang cheng xian',
		],
		'411323'	=> [
			'zh-cn'			=> '西峡县',
			'en'			=> 'xi xia xian',
		],
		'411324'	=> [
			'zh-cn'			=> '镇平县',
			'en'			=> 'zhen ping xian',
		],
		'411325'	=> [
			'zh-cn'			=> '内乡县',
			'en'			=> 'nei xiang xian',
		],
		'411326'	=> [
			'zh-cn'			=> '淅川县',
			'en'			=> 'xi chuan xian',
		],
		'411327'	=> [
			'zh-cn'			=> '社旗县',
			'en'			=> 'she qi xian',
		],
		'411328'	=> [
			'zh-cn'			=> '唐河县',
			'en'			=> 'tang he xian',
		],
		'411329'	=> [
			'zh-cn'			=> '新野县',
			'en'			=> 'xin ye xian',
		],
		'411330'	=> [
			'zh-cn'			=> '桐柏县',
			'en'			=> 'tong bai xian',
		],
		'411381'	=> [
			'zh-cn'			=> '邓州市',
			'en'			=> 'deng zhou shi',
		],
		'411400'	=> [
			'zh-cn'			=> '商丘市',
			'en'			=> 'shang qiu shi',
		],
		'411402'	=> [
			'zh-cn'			=> '梁园区',
			'en'			=> 'liang yuan qu',
		],
		'411403'	=> [
			'zh-cn'			=> '睢阳区',
			'en'			=> 'sui yang qu',
		],
		'411421'	=> [
			'zh-cn'			=> '民权县',
			'en'			=> 'min quan xian',
		],
		'411423'	=> [
			'zh-cn'			=> '宁陵县',
			'en'			=> 'ning ling xian',
		],
		'411424'	=> [
			'zh-cn'			=> '柘城县',
			'en'			=> 'zhe cheng xian',
		],
		'411425'	=> [
			'zh-cn'			=> '虞城县',
			'en'			=> 'yu cheng xian',
		],
		'411426'	=> [
			'zh-cn'			=> '夏邑县',
			'en'			=> 'xia yi xian',
		],
		'411481'	=> [
			'zh-cn'			=> '永城市',
			'en'			=> 'yong cheng shi',
		],
		'411500'	=> [
			'zh-cn'			=> '信阳市',
			'en'			=> 'xin yang shi',
		],
		'411502'	=> [
			'zh-cn'			=> '浉河区',
			'en'			=> 'shi he qu',
		],
		'411503'	=> [
			'zh-cn'			=> '平桥区',
			'en'			=> 'ping qiao qu',
		],
		'411521'	=> [
			'zh-cn'			=> '罗山县',
			'en'			=> 'luo shan xian',
		],
		'411522'	=> [
			'zh-cn'			=> '光山县',
			'en'			=> 'guang shan xian',
		],
		'411524'	=> [
			'zh-cn'			=> '商城县',
			'en'			=> 'shang cheng xian',
		],
		'411525'	=> [
			'zh-cn'			=> '固始县',
			'en'			=> 'gu shi xian',
		],
		'411526'	=> [
			'zh-cn'			=> '潢川县',
			'en'			=> 'huang chuan xian',
		],
		'411527'	=> [
			'zh-cn'			=> '淮滨县',
			'en'			=> 'huai bin xian',
		],
		'411600'	=> [
			'zh-cn'			=> '周口市',
			'en'			=> 'zhou kou shi',
		],
		'411602'	=> [
			'zh-cn'			=> '川汇区',
			'en'			=> 'chuan hui qu',
		],
		'411621'	=> [
			'zh-cn'			=> '扶沟县',
			'en'			=> 'fu gou xian',
		],
		'411622'	=> [
			'zh-cn'			=> '西华县',
			'en'			=> 'xi hua xian',
		],
		'411623'	=> [
			'zh-cn'			=> '商水县',
			'en'			=> 'shang shui xian',
		],
		'411624'	=> [
			'zh-cn'			=> '沈丘县',
			'en'			=> 'shen qiu xian',
		],
		'411625'	=> [
			'zh-cn'			=> '郸城县',
			'en'			=> 'dan cheng xian',
		],
		'411626'	=> [
			'zh-cn'			=> '淮阳县',
			'en'			=> 'huai yang xian',
		],
		'411627'	=> [
			'zh-cn'			=> '太康县',
			'en'			=> 'tai kang xian',
		],
		'411628'	=> [
			'zh-cn'			=> '鹿邑县',
			'en'			=> 'lu yi xian',
		],
		'411681'	=> [
			'zh-cn'			=> '项城市',
			'en'			=> 'xiang cheng shi',
		],
		'411700'	=> [
			'zh-cn'			=> '驻马店市',
			'en'			=> 'zhu ma dian shi',
		],
		'411702'	=> [
			'zh-cn'			=> '驿城区',
			'en'			=> 'yi cheng qu',
		],
		'411721'	=> [
			'zh-cn'			=> '西平县',
			'en'			=> 'xi ping xian',
		],
		'411722'	=> [
			'zh-cn'			=> '上蔡县',
			'en'			=> 'shang cai xian',
		],
		'411723'	=> [
			'zh-cn'			=> '平舆县',
			'en'			=> 'ping yu xian',
		],
		'411724'	=> [
			'zh-cn'			=> '正阳县',
			'en'			=> 'zheng yang xian',
		],
		'411725'	=> [
			'zh-cn'			=> '确山县',
			'en'			=> 'que shan xian',
		],
		'411726'	=> [
			'zh-cn'			=> '泌阳县',
			'en'			=> 'bi yang xian',
		],
		'411727'	=> [
			'zh-cn'			=> '汝南县',
			'en'			=> 'ru nan xian',
		],
		'411728'	=> [
			'zh-cn'			=> '遂平县',
			'en'			=> 'sui ping xian',
		],
		'411729'	=> [
			'zh-cn'			=> '新蔡县',
			'en'			=> 'xin cai xian',
		],
		'412100'	=> [
			'zh-cn'			=> '开封地区',
			'en'			=> 'kai feng di qu',
		],
		'412122'	=> [
			'zh-cn'			=> '通许县',
			'en'			=> 'tong xu xian',
		],
		'412123'	=> [
			'zh-cn'			=> '尉氏县',
			'en'			=> 'wei shi xian',
		],
		'412124'	=> [
			'zh-cn'			=> '开封县',
			'en'			=> 'kai feng xian',
		],
		'412125'	=> [
			'zh-cn'			=> '中牟县',
			'en'			=> 'zhong mu xian',
		],
		'412127'	=> [
			'zh-cn'			=> '登封县',
			'en'			=> 'deng feng xian',
		],
		'412128'	=> [
			'zh-cn'			=> '新郑县',
			'en'			=> 'xin zheng xian',
		],
		'412130'	=> [
			'zh-cn'			=> '兰考县',
			'en'			=> 'lan kao xian',
		],
		'412200'	=> [
			'zh-cn'			=> '新乡地区',
			'en'			=> 'xin xiang di qu',
		],
		'412201'	=> [
			'zh-cn'			=> '新乡市',
			'en'			=> 'xin xiang shi',
		],
		'412202'	=> [
			'zh-cn'			=> '红旗区',
			'en'			=> 'hong qi qu',
		],
		'412203'	=> [
			'zh-cn'			=> '新华区',
			'en'			=> 'xin hua qu',
		],
		'412204'	=> [
			'zh-cn'			=> '北站区',
			'en'			=> 'bei zhan qu',
		],
		'412221'	=> [
			'zh-cn'			=> '沁阳县',
			'en'			=> 'qin yang xian',
		],
		'412222'	=> [
			'zh-cn'			=> '新乡县',
			'en'			=> 'xin xiang xian',
		],
		'412223'	=> [
			'zh-cn'			=> '济源县',
			'en'			=> 'ji yuan xian',
		],
		'412226'	=> [
			'zh-cn'			=> '武陟县',
			'en'			=> 'wu zhi xian',
		],
		'412228'	=> [
			'zh-cn'			=> '获嘉县',
			'en'			=> 'huo jia xian',
		],
		'412229'	=> [
			'zh-cn'			=> '博爱县',
			'en'			=> 'bo ai xian',
		],
		'412231'	=> [
			'zh-cn'			=> '修武县',
			'en'			=> 'xiu wu xian',
		],
		'412232'	=> [
			'zh-cn'			=> '原阳县',
			'en'			=> 'yuan yang xian',
		],
		'412233'	=> [
			'zh-cn'			=> '延津县',
			'en'			=> 'yan jin xian',
		],
		'412234'	=> [
			'zh-cn'			=> '封丘县',
			'en'			=> 'feng qiu xian',
		],
		'412300'	=> [
			'zh-cn'			=> '商丘地区',
			'en'			=> 'shang qiu di qu',
		],
		'412301'	=> [
			'zh-cn'			=> '商丘市',
			'en'			=> 'shang qiu shi',
		],
		'412302'	=> [
			'zh-cn'			=> '永城市',
			'en'			=> 'yong cheng shi',
		],
		'412321'	=> [
			'zh-cn'			=> '虞城县',
			'en'			=> 'yu cheng xian',
		],
		'412322'	=> [
			'zh-cn'			=> '商丘县',
			'en'			=> 'shang qiu xian',
		],
		'412323'	=> [
			'zh-cn'			=> '民权县',
			'en'			=> 'min quan xian',
		],
		'412324'	=> [
			'zh-cn'			=> '宁陵县',
			'en'			=> 'ning ling xian',
		],
		'412326'	=> [
			'zh-cn'			=> '夏邑县',
			'en'			=> 'xia yi xian',
		],
		'412327'	=> [
			'zh-cn'			=> '柘城县',
			'en'			=> 'zhe cheng xian',
		],
		'412328'	=> [
			'zh-cn'			=> '永城县',
			'en'			=> 'yong cheng xian',
		],
		'412400'	=> [
			'zh-cn'			=> '安阳地区',
			'en'			=> 'an yang di qu',
		],
		'412401'	=> [
			'zh-cn'			=> '安阳市',
			'en'			=> 'an yang shi',
		],
		'412402'	=> [
			'zh-cn'			=> '文峰区',
			'en'			=> 'wen feng qu',
		],
		'412403'	=> [
			'zh-cn'			=> '北关区',
			'en'			=> 'bei guan qu',
		],
		'412404'	=> [
			'zh-cn'			=> '铁西区',
			'en'			=> 'tie xi qu',
		],
		'412422'	=> [
			'zh-cn'			=> '安阳县',
			'en'			=> 'an yang xian',
		],
		'412423'	=> [
			'zh-cn'			=> '汤阴县',
			'en'			=> 'tang yin xian',
		],
		'412430'	=> [
			'zh-cn'			=> '濮阳县',
			'en'			=> 'pu yang xian',
		],
		'412432'	=> [
			'zh-cn'			=> '清丰县',
			'en'			=> 'qing feng xian',
		],
		'412433'	=> [
			'zh-cn'			=> '南乐县',
			'en'			=> 'nan le xian',
		],
		'412434'	=> [
			'zh-cn'			=> '内黄县',
			'en'			=> 'nei huang xian',
		],
		'412435'	=> [
			'zh-cn'			=> '长垣县',
			'en'			=> 'chang yuan xian',
		],
		'412437'	=> [
			'zh-cn'			=> '台前县',
			'en'			=> 'tai qian xian',
		],
		'412500'	=> [
			'zh-cn'			=> '洛阳地区',
			'en'			=> 'luo yang di qu',
		],
		'412501'	=> [
			'zh-cn'			=> '三门峡市',
			'en'			=> 'san men xia shi',
		],
		'412502'	=> [
			'zh-cn'			=> '义马市',
			'en'			=> 'yi ma shi',
		],
		'412521'	=> [
			'zh-cn'			=> '孟津县',
			'en'			=> 'meng jin xian',
		],
		'412522'	=> [
			'zh-cn'			=> '偃师县',
			'en'			=> 'yan shi xian',
		],
		'412523'	=> [
			'zh-cn'			=> '新安县',
			'en'			=> 'xin an xian',
		],
		'412524'	=> [
			'zh-cn'			=> '渑池县',
			'en'			=> 'mian chi xian',
		],
		'412526'	=> [
			'zh-cn'			=> '灵宝县',
			'en'			=> 'ling bao xian',
		],
		'412527'	=> [
			'zh-cn'			=> '伊川县',
			'en'			=> 'yi chuan xian',
		],
		'412528'	=> [
			'zh-cn'			=> '汝阳县',
			'en'			=> 'ru yang xian',
		],
		'412530'	=> [
			'zh-cn'			=> '洛宁县',
			'en'			=> 'luo ning xian',
		],
		'412531'	=> [
			'zh-cn'			=> '卢氏县',
			'en'			=> 'lu shi xian',
		],
		'412532'	=> [
			'zh-cn'			=> '栾川县',
			'en'			=> 'luan chuan xian',
		],
		'412533'	=> [
			'zh-cn'			=> '临汝县',
			'en'			=> 'lin ru xian',
		],
		'412534'	=> [
			'zh-cn'			=> '宜阳县',
			'en'			=> 'yi yang xian',
		],
		'412600'	=> [
			'zh-cn'			=> '许昌地区',
			'en'			=> 'xu chang di qu',
		],
		'412601'	=> [
			'zh-cn'			=> '许昌市',
			'en'			=> 'xu chang shi',
		],
		'412602'	=> [
			'zh-cn'			=> '漯河市',
			'en'			=> 'ta he shi',
		],
		'412621'	=> [
			'zh-cn'			=> '长葛县',
			'en'			=> 'chang ge xian',
		],
		'412623'	=> [
			'zh-cn'			=> '鄢陵县',
			'en'			=> 'yan ling xian',
		],
		'412624'	=> [
			'zh-cn'			=> '许昌县',
			'en'			=> 'xu chang xian',
		],
		'412626'	=> [
			'zh-cn'			=> '临颍县',
			'en'			=> 'lin ying xian',
		],
		'412627'	=> [
			'zh-cn'			=> '襄城县',
			'en'			=> 'xiang cheng xian',
		],
		'412629'	=> [
			'zh-cn'			=> '郾城县',
			'en'			=> 'yan cheng xian',
		],
		'412630'	=> [
			'zh-cn'			=> '鲁山县',
			'en'			=> 'lu shan xian',
		],
		'412631'	=> [
			'zh-cn'			=> '宝丰县',
			'en'			=> 'bao feng xian',
		],
		'412632'	=> [
			'zh-cn'			=> '舞阳县',
			'en'			=> 'wu yang xian',
		],
		'412700'	=> [
			'zh-cn'			=> '周口地区',
			'en'			=> 'zhou kou di qu',
		],
		'412701'	=> [
			'zh-cn'			=> '周口市',
			'en'			=> 'zhou kou shi',
		],
		'412702'	=> [
			'zh-cn'			=> '项城市',
			'en'			=> 'xiang cheng shi',
		],
		'412721'	=> [
			'zh-cn'			=> '扶沟县',
			'en'			=> 'fu gou xian',
		],
		'412722'	=> [
			'zh-cn'			=> '西华县',
			'en'			=> 'xi hua xian',
		],
		'412723'	=> [
			'zh-cn'			=> '商水县',
			'en'			=> 'shang shui xian',
		],
		'412724'	=> [
			'zh-cn'			=> '太康县',
			'en'			=> 'tai kang xian',
		],
		'412725'	=> [
			'zh-cn'			=> '鹿邑县',
			'en'			=> 'lu yi xian',
		],
		'412726'	=> [
			'zh-cn'			=> '郸城县',
			'en'			=> 'dan cheng xian',
		],
		'412727'	=> [
			'zh-cn'			=> '淮阳县',
			'en'			=> 'huai yang xian',
		],
		'412728'	=> [
			'zh-cn'			=> '沈丘县',
			'en'			=> 'shen qiu xian',
		],
		'412729'	=> [
			'zh-cn'			=> '项城县',
			'en'			=> 'xiang cheng xian',
		],
		'412800'	=> [
			'zh-cn'			=> '驻马店地区',
			'en'			=> 'zhu ma dian di qu',
		],
		'412801'	=> [
			'zh-cn'			=> '驻马店市',
			'en'			=> 'zhu ma dian shi',
		],
		'412821'	=> [
			'zh-cn'			=> '确山县',
			'en'			=> 'que shan xian',
		],
		'412822'	=> [
			'zh-cn'			=> '泌阳县',
			'en'			=> 'bi yang xian',
		],
		'412823'	=> [
			'zh-cn'			=> '遂平县',
			'en'			=> 'sui ping xian',
		],
		'412824'	=> [
			'zh-cn'			=> '西平县',
			'en'			=> 'xi ping xian',
		],
		'412825'	=> [
			'zh-cn'			=> '上蔡县',
			'en'			=> 'shang cai xian',
		],
		'412826'	=> [
			'zh-cn'			=> '汝南县',
			'en'			=> 'ru nan xian',
		],
		'412827'	=> [
			'zh-cn'			=> '平舆县',
			'en'			=> 'ping yu xian',
		],
		'412828'	=> [
			'zh-cn'			=> '新蔡县',
			'en'			=> 'xin cai xian',
		],
		'412829'	=> [
			'zh-cn'			=> '正阳县',
			'en'			=> 'zheng yang xian',
		],
		'412900'	=> [
			'zh-cn'			=> '南阳地区',
			'en'			=> 'nan yang di qu',
		],
		'412901'	=> [
			'zh-cn'			=> '南阳市',
			'en'			=> 'nan yang shi',
		],
		'412902'	=> [
			'zh-cn'			=> '邓州市',
			'en'			=> 'deng zhou shi',
		],
		'412921'	=> [
			'zh-cn'			=> '南召县',
			'en'			=> 'nan zhao xian',
		],
		'412922'	=> [
			'zh-cn'			=> '方城县',
			'en'			=> 'fang cheng xian',
		],
		'412923'	=> [
			'zh-cn'			=> '西峡县',
			'en'			=> 'xi xia xian',
		],
		'412924'	=> [
			'zh-cn'			=> '南阳县',
			'en'			=> 'nan yang xian',
		],
		'412925'	=> [
			'zh-cn'			=> '镇平县',
			'en'			=> 'zhen ping xian',
		],
		'412926'	=> [
			'zh-cn'			=> '内乡县',
			'en'			=> 'nei xiang xian',
		],
		'412927'	=> [
			'zh-cn'			=> '淅川县',
			'en'			=> 'xi chuan xian',
		],
		'412928'	=> [
			'zh-cn'			=> '社旗县',
			'en'			=> 'she qi xian',
		],
		'412929'	=> [
			'zh-cn'			=> '唐河县',
			'en'			=> 'tang he xian',
		],
		'412931'	=> [
			'zh-cn'			=> '新野县',
			'en'			=> 'xin ye xian',
		],
		'412932'	=> [
			'zh-cn'			=> '桐柏县',
			'en'			=> 'tong bai xian',
		],
		'413000'	=> [
			'zh-cn'			=> '信阳地区',
			'en'			=> 'xin yang di qu',
		],
		'413001'	=> [
			'zh-cn'			=> '信阳市',
			'en'			=> 'xin yang shi',
		],
		'413022'	=> [
			'zh-cn'			=> '淮滨县',
			'en'			=> 'huai bin xian',
		],
		'413023'	=> [
			'zh-cn'			=> '信阳县',
			'en'			=> 'xin yang xian',
		],
		'413024'	=> [
			'zh-cn'			=> '潢川县',
			'en'			=> 'huang chuan xian',
		],
		'413025'	=> [
			'zh-cn'			=> '光山县',
			'en'			=> 'guang shan xian',
		],
		'413026'	=> [
			'zh-cn'			=> '固始县',
			'en'			=> 'gu shi xian',
		],
		'413027'	=> [
			'zh-cn'			=> '商城县',
			'en'			=> 'shang cheng xian',
		],
		'413028'	=> [
			'zh-cn'			=> '罗山县',
			'en'			=> 'luo shan xian',
		],
		'419001'	=> [
			'zh-cn'			=> '济源市',
			'en'			=> 'ji yuan shi',
		],
		'419002'	=> [
			'zh-cn'			=> '汝州市',
			'en'			=> 'ru zhou shi',
		],
		'419003'	=> [
			'zh-cn'			=> '济源市',
			'en'			=> 'ji yuan shi',
		],
		'419004'	=> [
			'zh-cn'			=> '禹州市',
			'en'			=> 'yu zhou shi',
		],
		'419005'	=> [
			'zh-cn'			=> '卫辉市',
			'en'			=> 'wei hui shi',
		],
		'419006'	=> [
			'zh-cn'			=> '辉县市',
			'en'			=> 'hui xian shi',
		],
		'419007'	=> [
			'zh-cn'			=> '沁阳市',
			'en'			=> 'qin yang shi',
		],
		'419008'	=> [
			'zh-cn'			=> '舞钢市',
			'en'			=> 'wu gang shi',
		],
		'419009'	=> [
			'zh-cn'			=> '巩义市',
			'en'			=> 'gong yi shi',
		],
		'419010'	=> [
			'zh-cn'			=> '灵宝市',
			'en'			=> 'ling bao shi',
		],
		'419011'	=> [
			'zh-cn'			=> '长葛市',
			'en'			=> 'chang ge shi',
		],
		'419012'	=> [
			'zh-cn'			=> '偃师市',
			'en'			=> 'yan shi shi',
		],
		'419013'	=> [
			'zh-cn'			=> '邓州市',
			'en'			=> 'deng zhou shi',
		],
		'419014'	=> [
			'zh-cn'			=> '林州市',
			'en'			=> 'lin zhou shi',
		],
		'419015'	=> [
			'zh-cn'			=> '新密市',
			'en'			=> 'xin mi shi',
		],
		'419016'	=> [
			'zh-cn'			=> '荥阳市',
			'en'			=> 'xing yang shi',
		],
		'419017'	=> [
			'zh-cn'			=> '新郑市',
			'en'			=> 'xin zheng shi',
		],
		'419018'	=> [
			'zh-cn'			=> '登封市',
			'en'			=> 'deng feng shi',
		],
		'420000'	=> [
			'zh-cn'			=> '湖北省',
			'en'			=> 'hu bei sheng',
		],
		'420100'	=> [
			'zh-cn'			=> '武汉市',
			'en'			=> 'wu han shi',
		],
		'420102'	=> [
			'zh-cn'			=> '江岸区',
			'en'			=> 'jiang an qu',
		],
		'420103'	=> [
			'zh-cn'			=> '江汉区',
			'en'			=> 'jiang han qu',
		],
		'420104'	=> [
			'zh-cn'			=> '硚口区',
			'en'			=> 'qiao kou qu',
		],
		'420105'	=> [
			'zh-cn'			=> '汉阳区',
			'en'			=> 'han yang qu',
		],
		'420106'	=> [
			'zh-cn'			=> '武昌区',
			'en'			=> 'wu chang qu',
		],
		'420107'	=> [
			'zh-cn'			=> '青山区',
			'en'			=> 'qing shan qu',
		],
		'420111'	=> [
			'zh-cn'			=> '洪山区',
			'en'			=> 'hong shan qu',
		],
		'420112'	=> [
			'zh-cn'			=> '东西湖区',
			'en'			=> 'dong xi hu qu',
		],
		'420113'	=> [
			'zh-cn'			=> '汉南区',
			'en'			=> 'han nan qu',
		],
		'420114'	=> [
			'zh-cn'			=> '蔡甸区',
			'en'			=> 'cai dian qu',
		],
		'420115'	=> [
			'zh-cn'			=> '江夏区',
			'en'			=> 'jiang xia qu',
		],
		'420116'	=> [
			'zh-cn'			=> '黄陂区',
			'en'			=> 'huang pi qu',
		],
		'420117'	=> [
			'zh-cn'			=> '新洲区',
			'en'			=> 'xin zhou qu',
		],
		'420121'	=> [
			'zh-cn'			=> '汉阳县',
			'en'			=> 'han yang xian',
		],
		'420122'	=> [
			'zh-cn'			=> '武昌县',
			'en'			=> 'wu chang xian',
		],
		'420123'	=> [
			'zh-cn'			=> '黄陂县',
			'en'			=> 'huang po xian',
		],
		'420124'	=> [
			'zh-cn'			=> '新洲县',
			'en'			=> 'xin zhou xian',
		],
		'420200'	=> [
			'zh-cn'			=> '黄石市',
			'en'			=> 'huang shi shi',
		],
		'420202'	=> [
			'zh-cn'			=> '黄石港区',
			'en'			=> 'huang shi gang qu',
		],
		'420203'	=> [
			'zh-cn'			=> '西塞山区',
			'en'			=> 'xi sai shan qu',
		],
		'420204'	=> [
			'zh-cn'			=> '下陆区',
			'en'			=> 'xia lu qu',
		],
		'420205'	=> [
			'zh-cn'			=> '铁山区',
			'en'			=> 'tie shan qu',
		],
		'420221'	=> [
			'zh-cn'			=> '大冶县',
			'en'			=> 'da ye xian',
		],
		'420222'	=> [
			'zh-cn'			=> '阳新县',
			'en'			=> 'yang xin xian',
		],
		'420281'	=> [
			'zh-cn'			=> '大冶市',
			'en'			=> 'da ye shi',
		],
		'420300'	=> [
			'zh-cn'			=> '十堰市',
			'en'			=> 'shi yan shi',
		],
		'420302'	=> [
			'zh-cn'			=> '茅箭区',
			'en'			=> 'mao jian qu',
		],
		'420303'	=> [
			'zh-cn'			=> '张湾区',
			'en'			=> 'zhang wan qu',
		],
		'420304'	=> [
			'zh-cn'			=> '郧阳区',
			'en'			=> 'yun yang qu',
		],
		'420322'	=> [
			'zh-cn'			=> '郧西县',
			'en'			=> 'yun xi xian',
		],
		'420323'	=> [
			'zh-cn'			=> '竹山县',
			'en'			=> 'zhu shan xian',
		],
		'420324'	=> [
			'zh-cn'			=> '竹溪县',
			'en'			=> 'zhu xi xian',
		],
		'420381'	=> [
			'zh-cn'			=> '丹江口市',
			'en'			=> 'dan jiang kou shi',
		],
		'420400'	=> [
			'zh-cn'			=> '沙市市',
			'en'			=> 'sha shi shi',
		],
		'420500'	=> [
			'zh-cn'			=> '宜昌市',
			'en'			=> 'yi chang shi',
		],
		'420502'	=> [
			'zh-cn'			=> '西陵区',
			'en'			=> 'xi ling qu',
		],
		'420503'	=> [
			'zh-cn'			=> '伍家岗区',
			'en'			=> 'wu jia gang qu',
		],
		'420504'	=> [
			'zh-cn'			=> '点军区',
			'en'			=> 'dian jun qu',
		],
		'420505'	=> [
			'zh-cn'			=> '猇亭区',
			'en'			=> 'xiao ting qu',
		],
		'420506'	=> [
			'zh-cn'			=> '夷陵区',
			'en'			=> 'yi ling qu',
		],
		'420521'	=> [
			'zh-cn'			=> '宜昌县',
			'en'			=> 'yi chang xian',
		],
		'420523'	=> [
			'zh-cn'			=> '枝江县',
			'en'			=> 'zhi jiang xian',
		],
		'420525'	=> [
			'zh-cn'			=> '远安县',
			'en'			=> 'yuan an xian',
		],
		'420526'	=> [
			'zh-cn'			=> '兴山县',
			'en'			=> 'xing shan xian',
		],
		'420527'	=> [
			'zh-cn'			=> '秭归县',
			'en'			=> 'zi gui xian',
		],
		'420528'	=> [
			'zh-cn'			=> '长阳土家族自治县',
			'en'			=> 'zhang yang tu jia zu zi zhi xian',
		],
		'420529'	=> [
			'zh-cn'			=> '五峰土家族自治县',
			'en'			=> 'wu feng tu jia zu zi zhi xian',
		],
		'420581'	=> [
			'zh-cn'			=> '宜都市',
			'en'			=> 'yi du shi',
		],
		'420582'	=> [
			'zh-cn'			=> '当阳市',
			'en'			=> 'dang yang shi',
		],
		'420583'	=> [
			'zh-cn'			=> '枝江市',
			'en'			=> 'zhi jiang shi',
		],
		'420600'	=> [
			'zh-cn'			=> '襄阳市',
			'en'			=> 'xiang yang shi',
		],
		'420602'	=> [
			'zh-cn'			=> '襄城区',
			'en'			=> 'xiang cheng qu',
		],
		'420603'	=> [
			'zh-cn'			=> '樊东区',
			'en'			=> 'fan dong qu',
		],
		'420604'	=> [
			'zh-cn'			=> '樊西区',
			'en'			=> 'fan xi qu',
		],
		'420606'	=> [
			'zh-cn'			=> '樊城区',
			'en'			=> 'fan cheng qu',
		],
		'420607'	=> [
			'zh-cn'			=> '襄州区',
			'en'			=> 'xiang zhou qu',
		],
		'420619'	=> [
			'zh-cn'			=> '随州市',
			'en'			=> 'sui zhou shi',
		],
		'420620'	=> [
			'zh-cn'			=> '老河口市',
			'en'			=> 'lao he kou shi',
		],
		'420621'	=> [
			'zh-cn'			=> '襄阳县',
			'en'			=> 'xiang yang xian',
		],
		'420622'	=> [
			'zh-cn'			=> '枣阳县',
			'en'			=> 'zao yang xian',
		],
		'420623'	=> [
			'zh-cn'			=> '宜城县',
			'en'			=> 'yi cheng xian',
		],
		'420624'	=> [
			'zh-cn'			=> '南漳县',
			'en'			=> 'nan zhang xian',
		],
		'420625'	=> [
			'zh-cn'			=> '谷城县',
			'en'			=> 'gu cheng xian',
		],
		'420626'	=> [
			'zh-cn'			=> '保康县',
			'en'			=> 'bao kang xian',
		],
		'420682'	=> [
			'zh-cn'			=> '老河口市',
			'en'			=> 'lao he kou shi',
		],
		'420683'	=> [
			'zh-cn'			=> '枣阳市',
			'en'			=> 'zao yang shi',
		],
		'420684'	=> [
			'zh-cn'			=> '宜城市',
			'en'			=> 'yi cheng shi',
		],
		'420700'	=> [
			'zh-cn'			=> '鄂州市',
			'en'			=> 'e zhou shi',
		],
		'420702'	=> [
			'zh-cn'			=> '梁子湖区',
			'en'			=> 'liang zi hu qu',
		],
		'420703'	=> [
			'zh-cn'			=> '华容区',
			'en'			=> 'hua rong qu',
		],
		'420704'	=> [
			'zh-cn'			=> '鄂城区',
			'en'			=> 'e cheng qu',
		],
		'420800'	=> [
			'zh-cn'			=> '荆门市',
			'en'			=> 'jing men shi',
		],
		'420802'	=> [
			'zh-cn'			=> '东宝区',
			'en'			=> 'dong bao qu',
		],
		'420803'	=> [
			'zh-cn'			=> '沙洋区',
			'en'			=> 'sha yang qu',
		],
		'420804'	=> [
			'zh-cn'			=> '掇刀区',
			'en'			=> 'duo dao qu',
		],
		'420821'	=> [
			'zh-cn'			=> '京山县',
			'en'			=> 'jing shan xian',
		],
		'420822'	=> [
			'zh-cn'			=> '沙洋县',
			'en'			=> 'sha yang xian',
		],
		'420881'	=> [
			'zh-cn'			=> '钟祥市',
			'en'			=> 'zhong xiang shi',
		],
		'420900'	=> [
			'zh-cn'			=> '孝感市',
			'en'			=> 'xiao gan shi',
		],
		'420902'	=> [
			'zh-cn'			=> '孝南区',
			'en'			=> 'xiao nan qu',
		],
		'420921'	=> [
			'zh-cn'			=> '孝昌县',
			'en'			=> 'xiao chang xian',
		],
		'420922'	=> [
			'zh-cn'			=> '大悟县',
			'en'			=> 'da wu xian',
		],
		'420923'	=> [
			'zh-cn'			=> '云梦县',
			'en'			=> 'yun meng xian',
		],
		'420924'	=> [
			'zh-cn'			=> '汉川县',
			'en'			=> 'han chuan xian',
		],
		'420981'	=> [
			'zh-cn'			=> '应城市',
			'en'			=> 'ying cheng shi',
		],
		'420982'	=> [
			'zh-cn'			=> '安陆市',
			'en'			=> 'an lu shi',
		],
		'420983'	=> [
			'zh-cn'			=> '广水市',
			'en'			=> 'guang shui shi',
		],
		'420984'	=> [
			'zh-cn'			=> '汉川市',
			'en'			=> 'han chuan shi',
		],
		'421000'	=> [
			'zh-cn'			=> '荆州市',
			'en'			=> 'jing zhou shi',
		],
		'421002'	=> [
			'zh-cn'			=> '沙市区',
			'en'			=> 'sha shi qu',
		],
		'421003'	=> [
			'zh-cn'			=> '荆州区',
			'en'			=> 'jing zhou qu',
		],
		'421004'	=> [
			'zh-cn'			=> '江陵区',
			'en'			=> 'jiang ling qu',
		],
		'421022'	=> [
			'zh-cn'			=> '公安县',
			'en'			=> 'gong an xian',
		],
		'421023'	=> [
			'zh-cn'			=> '监利县',
			'en'			=> 'jian li xian',
		],
		'421024'	=> [
			'zh-cn'			=> '江陵县',
			'en'			=> 'jiang ling xian',
		],
		'421025'	=> [
			'zh-cn'			=> '京山县',
			'en'			=> 'jing shan xian',
		],
		'421081'	=> [
			'zh-cn'			=> '石首市',
			'en'			=> 'shi shou shi',
		],
		'421082'	=> [
			'zh-cn'			=> '钟祥市',
			'en'			=> 'zhong xiang shi',
		],
		'421083'	=> [
			'zh-cn'			=> '洪湖市',
			'en'			=> 'hong hu shi',
		],
		'421087'	=> [
			'zh-cn'			=> '松滋市',
			'en'			=> 'song zi shi',
		],
		'421100'	=> [
			'zh-cn'			=> '黄冈市',
			'en'			=> 'huang gang shi',
		],
		'421102'	=> [
			'zh-cn'			=> '黄州区',
			'en'			=> 'huang zhou qu',
		],
		'421121'	=> [
			'zh-cn'			=> '团风县',
			'en'			=> 'tuan feng xian',
		],
		'421122'	=> [
			'zh-cn'			=> '红安县',
			'en'			=> 'hong an xian',
		],
		'421123'	=> [
			'zh-cn'			=> '罗田县',
			'en'			=> 'luo tian xian',
		],
		'421124'	=> [
			'zh-cn'			=> '英山县',
			'en'			=> 'ying shan xian',
		],
		'421125'	=> [
			'zh-cn'			=> '浠水县',
			'en'			=> 'xi shui xian',
		],
		'421126'	=> [
			'zh-cn'			=> '蕲春县',
			'en'			=> 'qi chun xian',
		],
		'421127'	=> [
			'zh-cn'			=> '黄梅县',
			'en'			=> 'huang mei xian',
		],
		'421181'	=> [
			'zh-cn'			=> '麻城市',
			'en'			=> 'ma cheng shi',
		],
		'421182'	=> [
			'zh-cn'			=> '武穴市',
			'en'			=> 'wu xue shi',
		],
		'421200'	=> [
			'zh-cn'			=> '咸宁市',
			'en'			=> 'xian ning shi',
		],
		'421202'	=> [
			'zh-cn'			=> '咸安区',
			'en'			=> 'xian an qu',
		],
		'421221'	=> [
			'zh-cn'			=> '嘉鱼县',
			'en'			=> 'jia yu xian',
		],
		'421222'	=> [
			'zh-cn'			=> '通城县',
			'en'			=> 'tong cheng xian',
		],
		'421223'	=> [
			'zh-cn'			=> '崇阳县',
			'en'			=> 'chong yang xian',
		],
		'421224'	=> [
			'zh-cn'			=> '通山县',
			'en'			=> 'tong shan xian',
		],
		'421281'	=> [
			'zh-cn'			=> '赤壁市',
			'en'			=> 'chi bi shi',
		],
		'421300'	=> [
			'zh-cn'			=> '随州市',
			'en'			=> 'sui zhou shi',
		],
		'421303'	=> [
			'zh-cn'			=> '曾都区',
			'en'			=> 'zeng du qu',
		],
		'421381'	=> [
			'zh-cn'			=> '广水市',
			'en'			=> 'guang shui shi',
		],
		'422100'	=> [
			'zh-cn'			=> '黄冈地区',
			'en'			=> 'huang gang di qu',
		],
		'422101'	=> [
			'zh-cn'			=> '麻城市',
			'en'			=> 'ma cheng shi',
		],
		'422102'	=> [
			'zh-cn'			=> '武穴市',
			'en'			=> 'wu xue shi',
		],
		'422103'	=> [
			'zh-cn'			=> '黄州市',
			'en'			=> 'huang zhou shi',
		],
		'422121'	=> [
			'zh-cn'			=> '黄冈县',
			'en'			=> 'huang gang xian',
		],
		'422122'	=> [
			'zh-cn'			=> '新洲县',
			'en'			=> 'xin zhou xian',
		],
		'422123'	=> [
			'zh-cn'			=> '红安县',
			'en'			=> 'hong an xian',
		],
		'422124'	=> [
			'zh-cn'			=> '麻城县',
			'en'			=> 'ma cheng xian',
		],
		'422125'	=> [
			'zh-cn'			=> '罗田县',
			'en'			=> 'luo tian xian',
		],
		'422126'	=> [
			'zh-cn'			=> '英山县',
			'en'			=> 'ying shan xian',
		],
		'422127'	=> [
			'zh-cn'			=> '浠水县',
			'en'			=> 'xi shui xian',
		],
		'422128'	=> [
			'zh-cn'			=> '蕲春县',
			'en'			=> 'qi chun xian',
		],
		'422129'	=> [
			'zh-cn'			=> '广济县',
			'en'			=> 'guang ji xian',
		],
		'422130'	=> [
			'zh-cn'			=> '黄梅县',
			'en'			=> 'huang mei xian',
		],
		'422131'	=> [
			'zh-cn'			=> '鄂城县',
			'en'			=> 'e cheng xian',
		],
		'422200'	=> [
			'zh-cn'			=> '孝感地区',
			'en'			=> 'xiao gan di qu',
		],
		'422201'	=> [
			'zh-cn'			=> '孝感市',
			'en'			=> 'xiao gan shi',
		],
		'422202'	=> [
			'zh-cn'			=> '应城市',
			'en'			=> 'ying cheng shi',
		],
		'422203'	=> [
			'zh-cn'			=> '安陆市',
			'en'			=> 'an lu shi',
		],
		'422204'	=> [
			'zh-cn'			=> '广水市',
			'en'			=> 'guang shui shi',
		],
		'422221'	=> [
			'zh-cn'			=> '孝感县',
			'en'			=> 'xiao gan xian',
		],
		'422222'	=> [
			'zh-cn'			=> '黄陂县',
			'en'			=> 'huang po xian',
		],
		'422223'	=> [
			'zh-cn'			=> '大悟县',
			'en'			=> 'da wu xian',
		],
		'422224'	=> [
			'zh-cn'			=> '应山县',
			'en'			=> 'ying shan xian',
		],
		'422225'	=> [
			'zh-cn'			=> '安陆县',
			'en'			=> 'an lu xian',
		],
		'422226'	=> [
			'zh-cn'			=> '云梦县',
			'en'			=> 'yun meng xian',
		],
		'422227'	=> [
			'zh-cn'			=> '应城县',
			'en'			=> 'ying cheng xian',
		],
		'422228'	=> [
			'zh-cn'			=> '汉川县',
			'en'			=> 'han chuan xian',
		],
		'422300'	=> [
			'zh-cn'			=> '咸宁地区',
			'en'			=> 'xian ning di qu',
		],
		'422301'	=> [
			'zh-cn'			=> '咸宁市',
			'en'			=> 'xian ning shi',
		],
		'422302'	=> [
			'zh-cn'			=> '蒲圻市',
			'en'			=> 'pu qi shi',
		],
		'422321'	=> [
			'zh-cn'			=> '咸宁县',
			'en'			=> 'xian ning xian',
		],
		'422322'	=> [
			'zh-cn'			=> '嘉鱼县',
			'en'			=> 'jia yu xian',
		],
		'422323'	=> [
			'zh-cn'			=> '蒲圻县',
			'en'			=> 'pu qi xian',
		],
		'422324'	=> [
			'zh-cn'			=> '通城县',
			'en'			=> 'tong cheng xian',
		],
		'422325'	=> [
			'zh-cn'			=> '崇阳县',
			'en'			=> 'chong yang xian',
		],
		'422326'	=> [
			'zh-cn'			=> '通山县',
			'en'			=> 'tong shan xian',
		],
		'422327'	=> [
			'zh-cn'			=> '阳新县',
			'en'			=> 'yang xin xian',
		],
		'422400'	=> [
			'zh-cn'			=> '荆州地区',
			'en'			=> 'jing zhou di qu',
		],
		'422401'	=> [
			'zh-cn'			=> '仙桃市',
			'en'			=> 'xian tao shi',
		],
		'422402'	=> [
			'zh-cn'			=> '石首市',
			'en'			=> 'shi shou shi',
		],
		'422403'	=> [
			'zh-cn'			=> '洪湖市',
			'en'			=> 'hong hu shi',
		],
		'422404'	=> [
			'zh-cn'			=> '天门市',
			'en'			=> 'tian men shi',
		],
		'422405'	=> [
			'zh-cn'			=> '潜江市',
			'en'			=> 'qian jiang shi',
		],
		'422406'	=> [
			'zh-cn'			=> '钟祥市',
			'en'			=> 'zhong xiang shi',
		],
		'422421'	=> [
			'zh-cn'			=> '江陵县',
			'en'			=> 'jiang ling xian',
		],
		'422422'	=> [
			'zh-cn'			=> '松滋县',
			'en'			=> 'song zi xian',
		],
		'422423'	=> [
			'zh-cn'			=> '公安县',
			'en'			=> 'gong an xian',
		],
		'422424'	=> [
			'zh-cn'			=> '石首县',
			'en'			=> 'shi shou xian',
		],
		'422425'	=> [
			'zh-cn'			=> '监利县',
			'en'			=> 'jian li xian',
		],
		'422426'	=> [
			'zh-cn'			=> '洪湖县',
			'en'			=> 'hong hu xian',
		],
		'422427'	=> [
			'zh-cn'			=> '沔阳县',
			'en'			=> 'mian yang xian',
		],
		'422428'	=> [
			'zh-cn'			=> '天门县',
			'en'			=> 'tian men xian',
		],
		'422429'	=> [
			'zh-cn'			=> '潜江县',
			'en'			=> 'qian jiang xian',
		],
		'422430'	=> [
			'zh-cn'			=> '荆门县',
			'en'			=> 'jing men xian',
		],
		'422431'	=> [
			'zh-cn'			=> '钟祥县',
			'en'			=> 'zhong xiang xian',
		],
		'422432'	=> [
			'zh-cn'			=> '京山县',
			'en'			=> 'jing shan xian',
		],
		'422500'	=> [
			'zh-cn'			=> '襄阳地区',
			'en'			=> 'xiang yang di qu',
		],
		'422501'	=> [
			'zh-cn'			=> '随州市',
			'en'			=> 'sui zhou shi',
		],
		'422502'	=> [
			'zh-cn'			=> '老河口市',
			'en'			=> 'lao he kou shi',
		],
		'422521'	=> [
			'zh-cn'			=> '樊阳县',
			'en'			=> 'fan yang xian',
		],
		'422522'	=> [
			'zh-cn'			=> '枣阳县',
			'en'			=> 'zao yang xian',
		],
		'422523'	=> [
			'zh-cn'			=> '宜城县',
			'en'			=> 'yi cheng xian',
		],
		'422524'	=> [
			'zh-cn'			=> '南漳县',
			'en'			=> 'nan zhang xian',
		],
		'422525'	=> [
			'zh-cn'			=> '谷城县',
			'en'			=> 'gu cheng xian',
		],
		'422526'	=> [
			'zh-cn'			=> '保康县',
			'en'			=> 'bao kang xian',
		],
		'422528'	=> [
			'zh-cn'			=> '光化县',
			'en'			=> 'guang hua xian',
		],
		'422600'	=> [
			'zh-cn'			=> '郧阳地区',
			'en'			=> 'yun yang di qu',
		],
		'422601'	=> [
			'zh-cn'			=> '丹江口市',
			'en'			=> 'dan jiang kou shi',
		],
		'422623'	=> [
			'zh-cn'			=> '郧西县',
			'en'			=> 'yun xi xian',
		],
		'422624'	=> [
			'zh-cn'			=> '竹山县',
			'en'			=> 'zhu shan xian',
		],
		'422625'	=> [
			'zh-cn'			=> '竹溪县',
			'en'			=> 'zhu xi xian',
		],
		'422627'	=> [
			'zh-cn'			=> '神农架林区',
			'en'			=> 'shen nong jia lin qu',
		],
		'422700'	=> [
			'zh-cn'			=> '宜昌地区',
			'en'			=> 'yi chang di qu',
		],
		'422701'	=> [
			'zh-cn'			=> '枝城市',
			'en'			=> 'zhi cheng shi',
		],
		'422702'	=> [
			'zh-cn'			=> '当阳市',
			'en'			=> 'dang yang shi',
		],
		'422721'	=> [
			'zh-cn'			=> '宜昌县',
			'en'			=> 'yi chang xian',
		],
		'422722'	=> [
			'zh-cn'			=> '宜都县',
			'en'			=> 'yi du xian',
		],
		'422723'	=> [
			'zh-cn'			=> '枝江县',
			'en'			=> 'zhi jiang xian',
		],
		'422724'	=> [
			'zh-cn'			=> '当阳县',
			'en'			=> 'dang yang xian',
		],
		'422725'	=> [
			'zh-cn'			=> '远安县',
			'en'			=> 'yuan an xian',
		],
		'422726'	=> [
			'zh-cn'			=> '兴山县',
			'en'			=> 'xing shan xian',
		],
		'422727'	=> [
			'zh-cn'			=> '秭归县',
			'en'			=> 'zi gui xian',
		],
		'422728'	=> [
			'zh-cn'			=> '长阳土家族自治县',
			'en'			=> 'zhang yang tu jia zu zi zhi xian',
		],
		'422729'	=> [
			'zh-cn'			=> '五峰土家族自治县',
			'en'			=> 'wu feng tu jia zu zi zhi xian',
		],
		'422800'	=> [
			'zh-cn'			=> '恩施土家族苗族自治州',
			'en'			=> 'en shi tu jia zu miao zu zi zhi zhou',
		],
		'422801'	=> [
			'zh-cn'			=> '恩施市',
			'en'			=> 'en shi shi',
		],
		'422802'	=> [
			'zh-cn'			=> '利川市',
			'en'			=> 'li chuan shi',
		],
		'422821'	=> [
			'zh-cn'			=> '恩施县',
			'en'			=> 'en shi xian',
		],
		'422822'	=> [
			'zh-cn'			=> '建始县',
			'en'			=> 'jian shi xian',
		],
		'422823'	=> [
			'zh-cn'			=> '巴东县',
			'en'			=> 'ba dong xian',
		],
		'422824'	=> [
			'zh-cn'			=> '利川县',
			'en'			=> 'li chuan xian',
		],
		'422825'	=> [
			'zh-cn'			=> '宣恩县',
			'en'			=> 'xuan en xian',
		],
		'422826'	=> [
			'zh-cn'			=> '咸丰县',
			'en'			=> 'xian feng xian',
		],
		'422827'	=> [
			'zh-cn'			=> '来凤县',
			'en'			=> 'lai feng xian',
		],
		'422828'	=> [
			'zh-cn'			=> '鹤峰县',
			'en'			=> 'he feng xian',
		],
		'422921'	=> [
			'zh-cn'			=> '神农架林区',
			'en'			=> 'shen nong jia lin qu',
		],
		'429001'	=> [
			'zh-cn'			=> '随州市',
			'en'			=> 'sui zhou shi',
		],
		'429002'	=> [
			'zh-cn'			=> '老河口市',
			'en'			=> 'lao he kou shi',
		],
		'429003'	=> [
			'zh-cn'			=> '枣阳市',
			'en'			=> 'zao yang shi',
		],
		'429004'	=> [
			'zh-cn'			=> '仙桃市',
			'en'			=> 'xian tao shi',
		],
		'429005'	=> [
			'zh-cn'			=> '潜江市',
			'en'			=> 'qian jiang shi',
		],
		'429006'	=> [
			'zh-cn'			=> '天门市',
			'en'			=> 'tian men shi',
		],
		'429007'	=> [
			'zh-cn'			=> '枝城市',
			'en'			=> 'zhi cheng shi',
		],
		'429008'	=> [
			'zh-cn'			=> '当阳市',
			'en'			=> 'dang yang shi',
		],
		'429009'	=> [
			'zh-cn'			=> '应城市',
			'en'			=> 'ying cheng shi',
		],
		'429010'	=> [
			'zh-cn'			=> '安陆市',
			'en'			=> 'an lu shi',
		],
		'429011'	=> [
			'zh-cn'			=> '广水市',
			'en'			=> 'guang shui shi',
		],
		'429012'	=> [
			'zh-cn'			=> '石首市',
			'en'			=> 'shi shou shi',
		],
		'429013'	=> [
			'zh-cn'			=> '洪湖市',
			'en'			=> 'hong hu shi',
		],
		'429014'	=> [
			'zh-cn'			=> '钟祥市',
			'en'			=> 'zhong xiang shi',
		],
		'429015'	=> [
			'zh-cn'			=> '丹江口市',
			'en'			=> 'dan jiang kou shi',
		],
		'429016'	=> [
			'zh-cn'			=> '大冶市',
			'en'			=> 'da ye shi',
		],
		'429017'	=> [
			'zh-cn'			=> '宜城市',
			'en'			=> 'yi cheng shi',
		],
		'429021'	=> [
			'zh-cn'			=> '神农架林区',
			'en'			=> 'shen nong jia lin qu',
		],
		'430000'	=> [
			'zh-cn'			=> '湖南省',
			'en'			=> 'hu nan sheng',
		],
		'430100'	=> [
			'zh-cn'			=> '长沙市',
			'en'			=> 'chang sha shi',
		],
		'430102'	=> [
			'zh-cn'			=> '芙蓉区',
			'en'			=> 'fu rong qu',
		],
		'430103'	=> [
			'zh-cn'			=> '天心区',
			'en'			=> 'tian xin qu',
		],
		'430104'	=> [
			'zh-cn'			=> '岳麓区',
			'en'			=> 'yue lu qu',
		],
		'430105'	=> [
			'zh-cn'			=> '开福区',
			'en'			=> 'kai fu qu',
		],
		'430111'	=> [
			'zh-cn'			=> '雨花区',
			'en'			=> 'yu hua qu',
		],
		'430112'	=> [
			'zh-cn'			=> '望城区',
			'en'			=> 'wang cheng qu',
		],
		'430121'	=> [
			'zh-cn'			=> '长沙县',
			'en'			=> 'chang sha xian',
		],
		'430122'	=> [
			'zh-cn'			=> '望城县',
			'en'			=> 'wang cheng xian',
		],
		'430123'	=> [
			'zh-cn'			=> '浏阳县',
			'en'			=> 'liu yang xian',
		],
		'430124'	=> [
			'zh-cn'			=> '宁乡县',
			'en'			=> 'ning xiang xian',
		],
		'430181'	=> [
			'zh-cn'			=> '浏阳市',
			'en'			=> 'liu yang shi',
		],
		'430200'	=> [
			'zh-cn'			=> '株洲市',
			'en'			=> 'zhu zhou shi',
		],
		'430202'	=> [
			'zh-cn'			=> '荷塘区',
			'en'			=> 'he tang qu',
		],
		'430203'	=> [
			'zh-cn'			=> '芦淞区',
			'en'			=> 'lu song qu',
		],
		'430204'	=> [
			'zh-cn'			=> '石峰区',
			'en'			=> 'shi feng qu',
		],
		'430211'	=> [
			'zh-cn'			=> '天元区',
			'en'			=> 'tian yuan qu',
		],
		'430219'	=> [
			'zh-cn'			=> '醴陵市',
			'en'			=> 'li ling shi',
		],
		'430221'	=> [
			'zh-cn'			=> '株洲县',
			'en'			=> 'zhu zhou xian',
		],
		'430222'	=> [
			'zh-cn'			=> '醴陵县',
			'en'			=> 'li ling xian',
		],
		'430224'	=> [
			'zh-cn'			=> '茶陵县',
			'en'			=> 'cha ling xian',
		],
		'430225'	=> [
			'zh-cn'			=> '炎陵县',
			'en'			=> 'yan ling xian',
		],
		'430281'	=> [
			'zh-cn'			=> '醴陵市',
			'en'			=> 'li ling shi',
		],
		'430300'	=> [
			'zh-cn'			=> '湘潭市',
			'en'			=> 'xiang tan shi',
		],
		'430302'	=> [
			'zh-cn'			=> '雨湖区',
			'en'			=> 'yu hu qu',
		],
		'430303'	=> [
			'zh-cn'			=> '湘江区',
			'en'			=> 'xiang jiang qu',
		],
		'430304'	=> [
			'zh-cn'			=> '岳塘区',
			'en'			=> 'yue tang qu',
		],
		'430305'	=> [
			'zh-cn'			=> '板塘区',
			'en'			=> 'ban tang qu',
		],
		'430306'	=> [
			'zh-cn'			=> '韶山区',
			'en'			=> 'shao shan qu',
		],
		'430321'	=> [
			'zh-cn'			=> '湘潭县',
			'en'			=> 'xiang tan xian',
		],
		'430322'	=> [
			'zh-cn'			=> '湘乡县',
			'en'			=> 'xiang xiang xian',
		],
		'430381'	=> [
			'zh-cn'			=> '湘乡市',
			'en'			=> 'xiang xiang shi',
		],
		'430382'	=> [
			'zh-cn'			=> '韶山市',
			'en'			=> 'shao shan shi',
		],
		'430400'	=> [
			'zh-cn'			=> '衡阳市',
			'en'			=> 'heng yang shi',
		],
		'430402'	=> [
			'zh-cn'			=> '江东区',
			'en'			=> 'jiang dong qu',
		],
		'430403'	=> [
			'zh-cn'			=> '城南区',
			'en'			=> 'cheng nan qu',
		],
		'430404'	=> [
			'zh-cn'			=> '城北区',
			'en'			=> 'cheng bei qu',
		],
		'430405'	=> [
			'zh-cn'			=> '珠晖区',
			'en'			=> 'zhu hui qu',
		],
		'430406'	=> [
			'zh-cn'			=> '雁峰区',
			'en'			=> 'yan feng qu',
		],
		'430407'	=> [
			'zh-cn'			=> '石鼓区',
			'en'			=> 'dan gu qu',
		],
		'430408'	=> [
			'zh-cn'			=> '蒸湘区',
			'en'			=> 'zheng xiang qu',
		],
		'430412'	=> [
			'zh-cn'			=> '南岳区',
			'en'			=> 'nan yue qu',
		],
		'430421'	=> [
			'zh-cn'			=> '衡阳县',
			'en'			=> 'heng yang xian',
		],
		'430422'	=> [
			'zh-cn'			=> '衡南县',
			'en'			=> 'heng nan xian',
		],
		'430423'	=> [
			'zh-cn'			=> '衡山县',
			'en'			=> 'heng shan xian',
		],
		'430424'	=> [
			'zh-cn'			=> '衡东县',
			'en'			=> 'heng dong xian',
		],
		'430425'	=> [
			'zh-cn'			=> '常宁县',
			'en'			=> 'chang ning xian',
		],
		'430426'	=> [
			'zh-cn'			=> '祁东县',
			'en'			=> 'qi dong xian',
		],
		'430427'	=> [
			'zh-cn'			=> '耒阳县',
			'en'			=> 'lei yang xian',
		],
		'430481'	=> [
			'zh-cn'			=> '耒阳市',
			'en'			=> 'lei yang shi',
		],
		'430482'	=> [
			'zh-cn'			=> '常宁市',
			'en'			=> 'chang ning shi',
		],
		'430500'	=> [
			'zh-cn'			=> '邵阳市',
			'en'			=> 'shao yang shi',
		],
		'430502'	=> [
			'zh-cn'			=> '双清区',
			'en'			=> 'shuang qing qu',
		],
		'430503'	=> [
			'zh-cn'			=> '大祥区',
			'en'			=> 'da xiang qu',
		],
		'430504'	=> [
			'zh-cn'			=> '桥头区',
			'en'			=> 'qiao tou qu',
		],
		'430511'	=> [
			'zh-cn'			=> '北塔区',
			'en'			=> 'bei ta qu',
		],
		'430521'	=> [
			'zh-cn'			=> '邵东县',
			'en'			=> 'shao dong xian',
		],
		'430522'	=> [
			'zh-cn'			=> '新邵县',
			'en'			=> 'xin shao xian',
		],
		'430523'	=> [
			'zh-cn'			=> '邵阳县',
			'en'			=> 'shao yang xian',
		],
		'430524'	=> [
			'zh-cn'			=> '隆回县',
			'en'			=> 'long hui xian',
		],
		'430525'	=> [
			'zh-cn'			=> '洞口县',
			'en'			=> 'dong kou xian',
		],
		'430526'	=> [
			'zh-cn'			=> '武冈县',
			'en'			=> 'wu gang xian',
		],
		'430527'	=> [
			'zh-cn'			=> '绥宁县',
			'en'			=> 'sui ning xian',
		],
		'430528'	=> [
			'zh-cn'			=> '新宁县',
			'en'			=> 'xin ning xian',
		],
		'430529'	=> [
			'zh-cn'			=> '城步苗族自治县',
			'en'			=> 'cheng bu miao zu zi zhi xian',
		],
		'430581'	=> [
			'zh-cn'			=> '武冈市',
			'en'			=> 'wu gang shi',
		],
		'430600'	=> [
			'zh-cn'			=> '岳阳市',
			'en'			=> 'yue yang shi',
		],
		'430602'	=> [
			'zh-cn'			=> '岳阳楼区',
			'en'			=> 'yue yang lou qu',
		],
		'430603'	=> [
			'zh-cn'			=> '云溪区',
			'en'			=> 'yun xi qu',
		],
		'430611'	=> [
			'zh-cn'			=> '君山区',
			'en'			=> 'jun shan qu',
		],
		'430621'	=> [
			'zh-cn'			=> '岳阳县',
			'en'			=> 'yue yang xian',
		],
		'430622'	=> [
			'zh-cn'			=> '临湘县',
			'en'			=> 'lin xiang xian',
		],
		'430623'	=> [
			'zh-cn'			=> '华容县',
			'en'			=> 'hua rong xian',
		],
		'430624'	=> [
			'zh-cn'			=> '湘阴县',
			'en'			=> 'xiang yin xian',
		],
		'430626'	=> [
			'zh-cn'			=> '平江县',
			'en'			=> 'ping jiang xian',
		],
		'430627'	=> [
			'zh-cn'			=> '汨罗县',
			'en'			=> 'mi luo xian',
		],
		'430681'	=> [
			'zh-cn'			=> '汨罗市',
			'en'			=> 'mi luo shi',
		],
		'430682'	=> [
			'zh-cn'			=> '临湘市',
			'en'			=> 'lin xiang shi',
		],
		'430700'	=> [
			'zh-cn'			=> '常德市',
			'en'			=> 'chang de shi',
		],
		'430702'	=> [
			'zh-cn'			=> '武陵区',
			'en'			=> 'wu ling qu',
		],
		'430703'	=> [
			'zh-cn'			=> '鼎城区',
			'en'			=> 'ding cheng qu',
		],
		'430721'	=> [
			'zh-cn'			=> '安乡县',
			'en'			=> 'an xiang xian',
		],
		'430722'	=> [
			'zh-cn'			=> '汉寿县',
			'en'			=> 'han shou xian',
		],
		'430724'	=> [
			'zh-cn'			=> '临澧县',
			'en'			=> 'lin li xian',
		],
		'430725'	=> [
			'zh-cn'			=> '桃源县',
			'en'			=> 'tao yuan xian',
		],
		'430726'	=> [
			'zh-cn'			=> '石门县',
			'en'			=> 'shi men xian',
		],
		'430781'	=> [
			'zh-cn'			=> '津市市',
			'en'			=> 'jin shi shi',
		],
		'430800'	=> [
			'zh-cn'			=> '张家界市',
			'en'			=> 'zhang jia jie shi',
		],
		'430802'	=> [
			'zh-cn'			=> '永定区',
			'en'			=> 'yong ding qu',
		],
		'430811'	=> [
			'zh-cn'			=> '武陵源区',
			'en'			=> 'wu ling yuan qu',
		],
		'430821'	=> [
			'zh-cn'			=> '慈利县',
			'en'			=> 'ci li xian',
		],
		'430822'	=> [
			'zh-cn'			=> '桑植县',
			'en'			=> 'sang zhi xian',
		],
		'430900'	=> [
			'zh-cn'			=> '益阳市',
			'en'			=> 'yi yang shi',
		],
		'430902'	=> [
			'zh-cn'			=> '资阳区',
			'en'			=> 'zi yang qu',
		],
		'430903'	=> [
			'zh-cn'			=> '赫山区',
			'en'			=> 'he shan qu',
		],
		'430922'	=> [
			'zh-cn'			=> '桃江县',
			'en'			=> 'tao jiang xian',
		],
		'430923'	=> [
			'zh-cn'			=> '安化县',
			'en'			=> 'an hua xian',
		],
		'430981'	=> [
			'zh-cn'			=> '沅江市',
			'en'			=> 'yuan jiang shi',
		],
		'431000'	=> [
			'zh-cn'			=> '郴州市',
			'en'			=> 'chen zhou shi',
		],
		'431002'	=> [
			'zh-cn'			=> '北湖区',
			'en'			=> 'bei hu qu',
		],
		'431003'	=> [
			'zh-cn'			=> '苏仙区',
			'en'			=> 'su xian qu',
		],
		'431021'	=> [
			'zh-cn'			=> '桂阳县',
			'en'			=> 'gui yang xian',
		],
		'431022'	=> [
			'zh-cn'			=> '宜章县',
			'en'			=> 'yi zhang xian',
		],
		'431023'	=> [
			'zh-cn'			=> '永兴县',
			'en'			=> 'yong xing xian',
		],
		'431024'	=> [
			'zh-cn'			=> '嘉禾县',
			'en'			=> 'jia he xian',
		],
		'431025'	=> [
			'zh-cn'			=> '临武县',
			'en'			=> 'lin wu xian',
		],
		'431026'	=> [
			'zh-cn'			=> '汝城县',
			'en'			=> 'ru cheng xian',
		],
		'431027'	=> [
			'zh-cn'			=> '桂东县',
			'en'			=> 'gui dong xian',
		],
		'431028'	=> [
			'zh-cn'			=> '安仁县',
			'en'			=> 'an ren xian',
		],
		'431081'	=> [
			'zh-cn'			=> '资兴市',
			'en'			=> 'zi xing shi',
		],
		'431100'	=> [
			'zh-cn'			=> '永州市',
			'en'			=> 'yong zhou shi',
		],
		'431102'	=> [
			'zh-cn'			=> '零陵区',
			'en'			=> 'ling ling qu',
		],
		'431103'	=> [
			'zh-cn'			=> '冷水滩区',
			'en'			=> 'leng shui tan qu',
		],
		'431121'	=> [
			'zh-cn'			=> '祁阳县',
			'en'			=> 'qi yang xian',
		],
		'431122'	=> [
			'zh-cn'			=> '东安县',
			'en'			=> 'dong an xian',
		],
		'431123'	=> [
			'zh-cn'			=> '双牌县',
			'en'			=> 'shuang pai xian',
		],
		'431125'	=> [
			'zh-cn'			=> '江永县',
			'en'			=> 'jiang yong xian',
		],
		'431126'	=> [
			'zh-cn'			=> '宁远县',
			'en'			=> 'ning yuan xian',
		],
		'431127'	=> [
			'zh-cn'			=> '蓝山县',
			'en'			=> 'lan shan xian',
		],
		'431128'	=> [
			'zh-cn'			=> '新田县',
			'en'			=> 'xin tian xian',
		],
		'431129'	=> [
			'zh-cn'			=> '江华瑶族自治县',
			'en'			=> 'jiang hua yao zu zi zhi xian',
		],
		'431200'	=> [
			'zh-cn'			=> '怀化市',
			'en'			=> 'huai hua shi',
		],
		'431202'	=> [
			'zh-cn'			=> '鹤城区',
			'en'			=> 'he cheng qu',
		],
		'431221'	=> [
			'zh-cn'			=> '中方县',
			'en'			=> 'zhong fang xian',
		],
		'431222'	=> [
			'zh-cn'			=> '沅陵县',
			'en'			=> 'yuan ling xian',
		],
		'431223'	=> [
			'zh-cn'			=> '辰溪县',
			'en'			=> 'chen xi xian',
		],
		'431224'	=> [
			'zh-cn'			=> '溆浦县',
			'en'			=> 'xu pu xian',
		],
		'431225'	=> [
			'zh-cn'			=> '会同县',
			'en'			=> 'hui tong xian',
		],
		'431226'	=> [
			'zh-cn'			=> '麻阳苗族自治县',
			'en'			=> 'ma yang miao zu zi zhi xian',
		],
		'431227'	=> [
			'zh-cn'			=> '新晃侗族自治县',
			'en'			=> 'xin huang dong zu zi zhi xian',
		],
		'431228'	=> [
			'zh-cn'			=> '芷江侗族自治县',
			'en'			=> 'zhi jiang dong zu zi zhi xian',
		],
		'431229'	=> [
			'zh-cn'			=> '靖州苗族侗族自治县',
			'en'			=> 'jing zhou miao zu dong zu zi zhi xian',
		],
		'431230'	=> [
			'zh-cn'			=> '通道侗族自治县',
			'en'			=> 'tong dao dong zu zi zhi xian',
		],
		'431281'	=> [
			'zh-cn'			=> '洪江市',
			'en'			=> 'hong jiang shi',
		],
		'431300'	=> [
			'zh-cn'			=> '娄底市',
			'en'			=> 'lou di shi',
		],
		'431302'	=> [
			'zh-cn'			=> '娄星区',
			'en'			=> 'lou xing qu',
		],
		'431321'	=> [
			'zh-cn'			=> '双峰县',
			'en'			=> 'shuang feng xian',
		],
		'431322'	=> [
			'zh-cn'			=> '新化县',
			'en'			=> 'xin hua xian',
		],
		'431381'	=> [
			'zh-cn'			=> '冷水江市',
			'en'			=> 'leng shui jiang shi',
		],
		'431382'	=> [
			'zh-cn'			=> '涟源市',
			'en'			=> 'lian yuan shi',
		],
		'432100'	=> [
			'zh-cn'			=> '湘潭地区',
			'en'			=> 'xiang tan di qu',
		],
		'432121'	=> [
			'zh-cn'			=> '湘潭县',
			'en'			=> 'xiang tan xian',
		],
		'432122'	=> [
			'zh-cn'			=> '湘乡县',
			'en'			=> 'xiang xiang xian',
		],
		'432123'	=> [
			'zh-cn'			=> '浏阳县',
			'en'			=> 'liu yang xian',
		],
		'432125'	=> [
			'zh-cn'			=> '醴陵县',
			'en'			=> 'li ling xian',
		],
		'432127'	=> [
			'zh-cn'			=> '茶陵县',
			'en'			=> 'cha ling xian',
		],
		'432200'	=> [
			'zh-cn'			=> '岳阳地区',
			'en'			=> 'yue yang di qu',
		],
		'432201'	=> [
			'zh-cn'			=> '岳阳市',
			'en'			=> 'yue yang shi',
		],
		'432222'	=> [
			'zh-cn'			=> '平江县',
			'en'			=> 'ping jiang xian',
		],
		'432223'	=> [
			'zh-cn'			=> '湘阴县',
			'en'			=> 'xiang yin xian',
		],
		'432224'	=> [
			'zh-cn'			=> '汨罗县',
			'en'			=> 'mi luo xian',
		],
		'432225'	=> [
			'zh-cn'			=> '临湘县',
			'en'			=> 'lin xiang xian',
		],
		'432226'	=> [
			'zh-cn'			=> '华容县',
			'en'			=> 'hua rong xian',
		],
		'432300'	=> [
			'zh-cn'			=> '益阳地区',
			'en'			=> 'yi yang di qu',
		],
		'432301'	=> [
			'zh-cn'			=> '益阳市',
			'en'			=> 'yi yang shi',
		],
		'432302'	=> [
			'zh-cn'			=> '沅江市',
			'en'			=> 'yuan jiang shi',
		],
		'432321'	=> [
			'zh-cn'			=> '益阳县',
			'en'			=> 'yi yang xian',
		],
		'432323'	=> [
			'zh-cn'			=> '沅江县',
			'en'			=> 'yuan jiang xian',
		],
		'432324'	=> [
			'zh-cn'			=> '宁乡县',
			'en'			=> 'ning xiang xian',
		],
		'432325'	=> [
			'zh-cn'			=> '桃江县',
			'en'			=> 'tao jiang xian',
		],
		'432326'	=> [
			'zh-cn'			=> '安化县',
			'en'			=> 'an hua xian',
		],
		'432400'	=> [
			'zh-cn'			=> '常德地区',
			'en'			=> 'chang de di qu',
		],
		'432401'	=> [
			'zh-cn'			=> '常德市',
			'en'			=> 'chang de shi',
		],
		'432402'	=> [
			'zh-cn'			=> '津市市',
			'en'			=> 'jin shi shi',
		],
		'432421'	=> [
			'zh-cn'			=> '常德县',
			'en'			=> 'chang de xian',
		],
		'432422'	=> [
			'zh-cn'			=> '安乡县',
			'en'			=> 'an xiang xian',
		],
		'432423'	=> [
			'zh-cn'			=> '汉寿县',
			'en'			=> 'han shou xian',
		],
		'432425'	=> [
			'zh-cn'			=> '临澧县',
			'en'			=> 'lin li xian',
		],
		'432426'	=> [
			'zh-cn'			=> '桃源县',
			'en'			=> 'tao yuan xian',
		],
		'432427'	=> [
			'zh-cn'			=> '石门县',
			'en'			=> 'shi men xian',
		],
		'432428'	=> [
			'zh-cn'			=> '慈利县',
			'en'			=> 'ci li xian',
		],
		'432500'	=> [
			'zh-cn'			=> '娄底地区',
			'en'			=> 'lou di di qu',
		],
		'432501'	=> [
			'zh-cn'			=> '娄底市',
			'en'			=> 'lou di shi',
		],
		'432502'	=> [
			'zh-cn'			=> '冷水江市',
			'en'			=> 'leng shui jiang shi',
		],
		'432503'	=> [
			'zh-cn'			=> '涟源市',
			'en'			=> 'lian yuan shi',
		],
		'432521'	=> [
			'zh-cn'			=> '涟源县',
			'en'			=> 'lian yuan xian',
		],
		'432522'	=> [
			'zh-cn'			=> '双峰县',
			'en'			=> 'shuang feng xian',
		],
		'432523'	=> [
			'zh-cn'			=> '邵东县',
			'en'			=> 'shao dong xian',
		],
		'432524'	=> [
			'zh-cn'			=> '新化县',
			'en'			=> 'xin hua xian',
		],
		'432525'	=> [
			'zh-cn'			=> '新邵县',
			'en'			=> 'xin shao xian',
		],
		'432600'	=> [
			'zh-cn'			=> '邵阳地区',
			'en'			=> 'shao yang di qu',
		],
		'432621'	=> [
			'zh-cn'			=> '邵阳县',
			'en'			=> 'shao yang xian',
		],
		'432622'	=> [
			'zh-cn'			=> '隆回县',
			'en'			=> 'long hui xian',
		],
		'432623'	=> [
			'zh-cn'			=> '武冈县',
			'en'			=> 'wu gang xian',
		],
		'432624'	=> [
			'zh-cn'			=> '洞口县',
			'en'			=> 'dong kou xian',
		],
		'432625'	=> [
			'zh-cn'			=> '新宁县',
			'en'			=> 'xin ning xian',
		],
		'432626'	=> [
			'zh-cn'			=> '绥宁县',
			'en'			=> 'sui ning xian',
		],
		'432627'	=> [
			'zh-cn'			=> '城步苗族自治县',
			'en'			=> 'cheng bu miao zu zi zhi xian',
		],
		'432700'	=> [
			'zh-cn'			=> '衡阳地区',
			'en'			=> 'heng yang di qu',
		],
		'432721'	=> [
			'zh-cn'			=> '衡阳县',
			'en'			=> 'heng yang xian',
		],
		'432722'	=> [
			'zh-cn'			=> '衡南县',
			'en'			=> 'heng nan xian',
		],
		'432723'	=> [
			'zh-cn'			=> '衡山县',
			'en'			=> 'heng shan xian',
		],
		'432724'	=> [
			'zh-cn'			=> '衡东县',
			'en'			=> 'heng dong xian',
		],
		'432725'	=> [
			'zh-cn'			=> '常宁县',
			'en'			=> 'chang ning xian',
		],
		'432726'	=> [
			'zh-cn'			=> '祁东县',
			'en'			=> 'qi dong xian',
		],
		'432727'	=> [
			'zh-cn'			=> '祁阳县',
			'en'			=> 'qi yang xian',
		],
		'432800'	=> [
			'zh-cn'			=> '郴州地区',
			'en'			=> 'chen zhou di qu',
		],
		'432801'	=> [
			'zh-cn'			=> '郴州市',
			'en'			=> 'chen zhou shi',
		],
		'432802'	=> [
			'zh-cn'			=> '资兴市',
			'en'			=> 'zi xing shi',
		],
		'432822'	=> [
			'zh-cn'			=> '桂阳县',
			'en'			=> 'gui yang xian',
		],
		'432823'	=> [
			'zh-cn'			=> '永兴县',
			'en'			=> 'yong xing xian',
		],
		'432824'	=> [
			'zh-cn'			=> '宜章县',
			'en'			=> 'yi zhang xian',
		],
		'432825'	=> [
			'zh-cn'			=> '资兴县',
			'en'			=> 'zi xing xian',
		],
		'432826'	=> [
			'zh-cn'			=> '嘉禾县',
			'en'			=> 'jia he xian',
		],
		'432827'	=> [
			'zh-cn'			=> '临武县',
			'en'			=> 'lin wu xian',
		],
		'432828'	=> [
			'zh-cn'			=> '汝城县',
			'en'			=> 'ru cheng xian',
		],
		'432829'	=> [
			'zh-cn'			=> '桂东县',
			'en'			=> 'gui dong xian',
		],
		'432830'	=> [
			'zh-cn'			=> '安仁县',
			'en'			=> 'an ren xian',
		],
		'432831'	=> [
			'zh-cn'			=> '耒阳县',
			'en'			=> 'lei yang xian',
		],
		'432900'	=> [
			'zh-cn'			=> '零陵地区',
			'en'			=> 'ling ling di qu',
		],
		'432901'	=> [
			'zh-cn'			=> '永州市',
			'en'			=> 'yong zhou shi',
		],
		'432902'	=> [
			'zh-cn'			=> '冷水滩市',
			'en'			=> 'leng shui tan shi',
		],
		'432921'	=> [
			'zh-cn'			=> '零陵县',
			'en'			=> 'ling ling xian',
		],
		'432922'	=> [
			'zh-cn'			=> '东安县',
			'en'			=> 'dong an xian',
		],
		'432924'	=> [
			'zh-cn'			=> '宁远县',
			'en'			=> 'ning yuan xian',
		],
		'432925'	=> [
			'zh-cn'			=> '江永县',
			'en'			=> 'jiang yong xian',
		],
		'432926'	=> [
			'zh-cn'			=> '江华瑶族自治县',
			'en'			=> 'jiang hua yao zu zi zhi xian',
		],
		'432927'	=> [
			'zh-cn'			=> '蓝山县',
			'en'			=> 'lan shan xian',
		],
		'432928'	=> [
			'zh-cn'			=> '新田县',
			'en'			=> 'xin tian xian',
		],
		'432929'	=> [
			'zh-cn'			=> '双牌县',
			'en'			=> 'shuang pai xian',
		],
		'432930'	=> [
			'zh-cn'			=> '祁阳县',
			'en'			=> 'qi yang xian',
		],
		'433000'	=> [
			'zh-cn'			=> '怀化地区',
			'en'			=> 'huai hua di qu',
		],
		'433001'	=> [
			'zh-cn'			=> '怀化市',
			'en'			=> 'huai hua shi',
		],
		'433002'	=> [
			'zh-cn'			=> '洪江市',
			'en'			=> 'hong jiang shi',
		],
		'433021'	=> [
			'zh-cn'			=> '黔阳县',
			'en'			=> 'qian yang xian',
		],
		'433022'	=> [
			'zh-cn'			=> '沅陵县',
			'en'			=> 'yuan ling xian',
		],
		'433023'	=> [
			'zh-cn'			=> '辰溪县',
			'en'			=> 'chen xi xian',
		],
		'433024'	=> [
			'zh-cn'			=> '溆浦县',
			'en'			=> 'xu pu xian',
		],
		'433025'	=> [
			'zh-cn'			=> '麻阳苗族自治县',
			'en'			=> 'ma yang miao zu zi zhi xian',
		],
		'433026'	=> [
			'zh-cn'			=> '新晃侗族自治县',
			'en'			=> 'xin huang dong zu zi zhi xian',
		],
		'433027'	=> [
			'zh-cn'			=> '芷江侗族自治县',
			'en'			=> 'zhi jiang dong zu zi zhi xian',
		],
		'433028'	=> [
			'zh-cn'			=> '怀化县',
			'en'			=> 'huai hua xian',
		],
		'433029'	=> [
			'zh-cn'			=> '会同县',
			'en'			=> 'hui tong xian',
		],
		'433030'	=> [
			'zh-cn'			=> '靖州苗族侗族自治县',
			'en'			=> 'jing zhou miao zu dong zu zi zhi xian',
		],
		'433031'	=> [
			'zh-cn'			=> '通道侗族自治县',
			'en'			=> 'tong dao dong zu zi zhi xian',
		],
		'433100'	=> [
			'zh-cn'			=> '湘西土家族苗族自治州',
			'en'			=> 'xiang xi tu jia zu miao zu zi zhi zhou',
		],
		'433101'	=> [
			'zh-cn'			=> '吉首市',
			'en'			=> 'ji shou shi',
		],
		'433102'	=> [
			'zh-cn'			=> '大庸市',
			'en'			=> 'da yong shi',
		],
		'433121'	=> [
			'zh-cn'			=> '吉首县',
			'en'			=> 'ji shou xian',
		],
		'433122'	=> [
			'zh-cn'			=> '泸溪县',
			'en'			=> 'lu xi xian',
		],
		'433123'	=> [
			'zh-cn'			=> '凤凰县',
			'en'			=> 'feng huang xian',
		],
		'433124'	=> [
			'zh-cn'			=> '花垣县',
			'en'			=> 'hua yuan xian',
		],
		'433125'	=> [
			'zh-cn'			=> '保靖县',
			'en'			=> 'bao jing xian',
		],
		'433126'	=> [
			'zh-cn'			=> '古丈县',
			'en'			=> 'gu zhang xian',
		],
		'433127'	=> [
			'zh-cn'			=> '永顺县',
			'en'			=> 'yong shun xian',
		],
		'433128'	=> [
			'zh-cn'			=> '大庸县',
			'en'			=> 'da yong xian',
		],
		'433129'	=> [
			'zh-cn'			=> '桑植县',
			'en'			=> 'sang zhi xian',
		],
		'433130'	=> [
			'zh-cn'			=> '龙山县',
			'en'			=> 'long shan xian',
		],
		'439001'	=> [
			'zh-cn'			=> '醴陵市',
			'en'			=> 'li ling shi',
		],
		'439002'	=> [
			'zh-cn'			=> '湘乡市',
			'en'			=> 'xiang xiang shi',
		],
		'439003'	=> [
			'zh-cn'			=> '耒阳市',
			'en'			=> 'lei yang shi',
		],
		'439004'	=> [
			'zh-cn'			=> '汨罗市',
			'en'			=> 'mi luo shi',
		],
		'439005'	=> [
			'zh-cn'			=> '津市市',
			'en'			=> 'jin shi shi',
		],
		'439006'	=> [
			'zh-cn'			=> '韶山市',
			'en'			=> 'shao shan shi',
		],
		'439007'	=> [
			'zh-cn'			=> '临湘市',
			'en'			=> 'lin xiang shi',
		],
		'439008'	=> [
			'zh-cn'			=> '浏阳市',
			'en'			=> 'liu yang shi',
		],
		'439009'	=> [
			'zh-cn'			=> '资兴市',
			'en'			=> 'zi xing shi',
		],
		'439010'	=> [
			'zh-cn'			=> '沅江市',
			'en'			=> 'yuan jiang shi',
		],
		'439011'	=> [
			'zh-cn'			=> '武冈市',
			'en'			=> 'wu gang shi',
		],
		'440000'	=> [
			'zh-cn'			=> '广东省',
			'en'			=> 'guang dong sheng',
		],
		'440100'	=> [
			'zh-cn'			=> '广州市',
			'en'			=> 'guang zhou shi',
		],
		'440102'	=> [
			'zh-cn'			=> '东山区',
			'en'			=> 'dong shan qu',
		],
		'440103'	=> [
			'zh-cn'			=> '荔湾区',
			'en'			=> 'li wan qu',
		],
		'440104'	=> [
			'zh-cn'			=> '越秀区',
			'en'			=> 'yue xiu qu',
		],
		'440105'	=> [
			'zh-cn'			=> '海珠区',
			'en'			=> 'hai zhu qu',
		],
		'440106'	=> [
			'zh-cn'			=> '天河区',
			'en'			=> 'tian he qu',
		],
		'440107'	=> [
			'zh-cn'			=> '芳村区',
			'en'			=> 'fang cun qu',
		],
		'440111'	=> [
			'zh-cn'			=> '白云区',
			'en'			=> 'bai yun qu',
		],
		'440112'	=> [
			'zh-cn'			=> '黄埔区',
			'en'			=> 'huang pu qu',
		],
		'440113'	=> [
			'zh-cn'			=> '番禺区',
			'en'			=> 'pan yu qu',
		],
		'440114'	=> [
			'zh-cn'			=> '花都区',
			'en'			=> 'hua du qu',
		],
		'440115'	=> [
			'zh-cn'			=> '南沙区',
			'en'			=> 'nan sha qu',
		],
		'440116'	=> [
			'zh-cn'			=> '萝岗区',
			'en'			=> 'luo gang qu',
		],
		'440117'	=> [
			'zh-cn'			=> '从化区',
			'en'			=> 'cong hua qu',
		],
		'440118'	=> [
			'zh-cn'			=> '增城区',
			'en'			=> 'zeng cheng qu',
		],
		'440122'	=> [
			'zh-cn'			=> '从化县',
			'en'			=> 'cong hua xian',
		],
		'440123'	=> [
			'zh-cn'			=> '新丰县',
			'en'			=> 'xin feng xian',
		],
		'440124'	=> [
			'zh-cn'			=> '龙门县',
			'en'			=> 'long men xian',
		],
		'440125'	=> [
			'zh-cn'			=> '增城县',
			'en'			=> 'zeng cheng xian',
		],
		'440126'	=> [
			'zh-cn'			=> '番禺县',
			'en'			=> 'pan yu xian',
		],
		'440127'	=> [
			'zh-cn'			=> '清远县',
			'en'			=> 'qing yuan xian',
		],
		'440128'	=> [
			'zh-cn'			=> '佛冈县',
			'en'			=> 'fo gang xian',
		],
		'440181'	=> [
			'zh-cn'			=> '番禺市',
			'en'			=> 'pan yu shi',
		],
		'440182'	=> [
			'zh-cn'			=> '花都市',
			'en'			=> 'hua du shi',
		],
		'440183'	=> [
			'zh-cn'			=> '增城市',
			'en'			=> 'zeng cheng shi',
		],
		'440184'	=> [
			'zh-cn'			=> '从化市',
			'en'			=> 'cong hua shi',
		],
		'440200'	=> [
			'zh-cn'			=> '韶关市',
			'en'			=> 'shao guan shi',
		],
		'440202'	=> [
			'zh-cn'			=> '北江区',
			'en'			=> 'bei jiang qu',
		],
		'440203'	=> [
			'zh-cn'			=> '武江区',
			'en'			=> 'wu jiang qu',
		],
		'440204'	=> [
			'zh-cn'			=> '浈江区',
			'en'			=> 'zhen jiang qu',
		],
		'440205'	=> [
			'zh-cn'			=> '曲江区',
			'en'			=> 'qu jiang qu',
		],
		'440221'	=> [
			'zh-cn'			=> '曲江县',
			'en'			=> 'qu jiang xian',
		],
		'440222'	=> [
			'zh-cn'			=> '始兴县',
			'en'			=> 'shi xing xian',
		],
		'440223'	=> [
			'zh-cn'			=> '南雄县',
			'en'			=> 'nan xiong xian',
		],
		'440224'	=> [
			'zh-cn'			=> '仁化县',
			'en'			=> 'ren hua xian',
		],
		'440225'	=> [
			'zh-cn'			=> '乐昌县',
			'en'			=> 'le chang xian',
		],
		'440227'	=> [
			'zh-cn'			=> '阳山县',
			'en'			=> 'yang shan xian',
		],
		'440228'	=> [
			'zh-cn'			=> '英德县',
			'en'			=> 'ying de xian',
		],
		'440229'	=> [
			'zh-cn'			=> '翁源县',
			'en'			=> 'weng yuan xian',
		],
		'440230'	=> [
			'zh-cn'			=> '连山壮族瑶族自治县',
			'en'			=> 'lian shan zhuang zu yao zu zi zhi xian',
		],
		'440231'	=> [
			'zh-cn'			=> '连南瑶族自治县',
			'en'			=> 'lian nan yao zu zi zhi xian',
		],
		'440232'	=> [
			'zh-cn'			=> '乳源瑶族自治县',
			'en'			=> 'ru yuan yao zu zi zhi xian',
		],
		'440233'	=> [
			'zh-cn'			=> '新丰县',
			'en'			=> 'xin feng xian',
		],
		'440281'	=> [
			'zh-cn'			=> '乐昌市',
			'en'			=> 'le chang shi',
		],
		'440282'	=> [
			'zh-cn'			=> '南雄市',
			'en'			=> 'nan xiong shi',
		],
		'440300'	=> [
			'zh-cn'			=> '深圳市',
			'en'			=> 'shen zhen shi',
		],
		'440303'	=> [
			'zh-cn'			=> '罗湖区',
			'en'			=> 'luo hu qu',
		],
		'440304'	=> [
			'zh-cn'			=> '福田区',
			'en'			=> 'fu tian qu',
		],
		'440305'	=> [
			'zh-cn'			=> '南山区',
			'en'			=> 'nan shan qu',
		],
		'440306'	=> [
			'zh-cn'			=> '宝安区',
			'en'			=> 'bao an qu',
		],
		'440307'	=> [
			'zh-cn'			=> '龙岗区',
			'en'			=> 'long gang qu',
		],
		'440308'	=> [
			'zh-cn'			=> '盐田区',
			'en'			=> 'yan tian qu',
		],
		'440309'	=> [
			'zh-cn'			=> '龙华区',
			'en'			=> 'long hua qu',
		],
		'440310'	=> [
			'zh-cn'			=> '坪山区',
			'en'			=> 'ping shan qu',
		],
		'440321'	=> [
			'zh-cn'			=> '宝安县',
			'en'			=> 'bao an xian',
		],
		'440400'	=> [
			'zh-cn'			=> '珠海市',
			'en'			=> 'zhu hai shi',
		],
		'440402'	=> [
			'zh-cn'			=> '香洲区',
			'en'			=> 'xiang zhou qu',
		],
		'440403'	=> [
			'zh-cn'			=> '斗门区',
			'en'			=> 'dou men qu',
		],
		'440404'	=> [
			'zh-cn'			=> '金湾区',
			'en'			=> 'jin wan qu',
		],
		'440421'	=> [
			'zh-cn'			=> '斗门县',
			'en'			=> 'dou men xian',
		],
		'440500'	=> [
			'zh-cn'			=> '汕头市',
			'en'			=> 'shan tou shi',
		],
		'440502'	=> [
			'zh-cn'			=> '龙湖区',
			'en'			=> 'long hu qu',
		],
		'440503'	=> [
			'zh-cn'			=> '金园区',
			'en'			=> 'jin yuan qu',
		],
		'440504'	=> [
			'zh-cn'			=> '升平区',
			'en'			=> 'sheng ping qu',
		],
		'440505'	=> [
			'zh-cn'			=> '金沙区',
			'en'			=> 'jin sha qu',
		],
		'440506'	=> [
			'zh-cn'			=> '达豪区',
			'en'			=> 'da hao qu',
		],
		'440507'	=> [
			'zh-cn'			=> '龙湖区',
			'en'			=> 'long hu qu',
		],
		'440508'	=> [
			'zh-cn'			=> '金园区',
			'en'			=> 'jin yuan qu',
		],
		'440509'	=> [
			'zh-cn'			=> '升平区',
			'en'			=> 'sheng ping qu',
		],
		'440510'	=> [
			'zh-cn'			=> '河浦区',
			'en'			=> 'he pu qu',
		],
		'440511'	=> [
			'zh-cn'			=> '金平区',
			'en'			=> 'jin ping qu',
		],
		'440512'	=> [
			'zh-cn'			=> '濠江区',
			'en'			=> 'hao jiang qu',
		],
		'440513'	=> [
			'zh-cn'			=> '潮阳区',
			'en'			=> 'chao yang qu',
		],
		'440514'	=> [
			'zh-cn'			=> '潮南区',
			'en'			=> 'chao nan qu',
		],
		'440515'	=> [
			'zh-cn'			=> '澄海区',
			'en'			=> 'cheng hai qu',
		],
		'440520'	=> [
			'zh-cn'			=> '潮州市',
			'en'			=> 'chao zhou shi',
		],
		'440521'	=> [
			'zh-cn'			=> '澄海县',
			'en'			=> 'cheng hai xian',
		],
		'440522'	=> [
			'zh-cn'			=> '饶平县',
			'en'			=> 'rao ping xian',
		],
		'440523'	=> [
			'zh-cn'			=> '南澳县',
			'en'			=> 'nan ao xian',
		],
		'440524'	=> [
			'zh-cn'			=> '潮阳县',
			'en'			=> 'chao yang xian',
		],
		'440525'	=> [
			'zh-cn'			=> '揭阳县',
			'en'			=> 'jie yang xian',
		],
		'440526'	=> [
			'zh-cn'			=> '揭西县',
			'en'			=> 'jie xi xian',
		],
		'440527'	=> [
			'zh-cn'			=> '普宁县',
			'en'			=> 'pu ning xian',
		],
		'440528'	=> [
			'zh-cn'			=> '惠来县',
			'en'			=> 'hui lai xian',
		],
		'440582'	=> [
			'zh-cn'			=> '潮阳市',
			'en'			=> 'chao yang shi',
		],
		'440583'	=> [
			'zh-cn'			=> '澄海市',
			'en'			=> 'cheng hai shi',
		],
		'440600'	=> [
			'zh-cn'			=> '佛山市',
			'en'			=> 'fo shan shi',
		],
		'440602'	=> [
			'zh-cn'			=> '石湾区',
			'en'			=> 'shi wan qu',
		],
		'440603'	=> [
			'zh-cn'			=> '石湾区',
			'en'			=> 'shi wan qu',
		],
		'440604'	=> [
			'zh-cn'			=> '禅城区',
			'en'			=> 'chan cheng qu',
		],
		'440605'	=> [
			'zh-cn'			=> '南海区',
			'en'			=> 'nan hai qu',
		],
		'440606'	=> [
			'zh-cn'			=> '顺德区',
			'en'			=> 'shun de qu',
		],
		'440607'	=> [
			'zh-cn'			=> '三水区',
			'en'			=> 'san shui qu',
		],
		'440608'	=> [
			'zh-cn'			=> '高明区',
			'en'			=> 'gao ming qu',
		],
		'440620'	=> [
			'zh-cn'			=> '中山市',
			'en'			=> 'zhong shan shi',
		],
		'440621'	=> [
			'zh-cn'			=> '三水县',
			'en'			=> 'san shui xian',
		],
		'440622'	=> [
			'zh-cn'			=> '南海县',
			'en'			=> 'nan hai xian',
		],
		'440623'	=> [
			'zh-cn'			=> '顺德县',
			'en'			=> 'shun de xian',
		],
		'440624'	=> [
			'zh-cn'			=> '高明县',
			'en'			=> 'gao ming xian',
		],
		'440681'	=> [
			'zh-cn'			=> '顺德市',
			'en'			=> 'shun de shi',
		],
		'440682'	=> [
			'zh-cn'			=> '南海市',
			'en'			=> 'nan hai shi',
		],
		'440683'	=> [
			'zh-cn'			=> '三水市',
			'en'			=> 'san shui shi',
		],
		'440684'	=> [
			'zh-cn'			=> '高明市',
			'en'			=> 'gao ming shi',
		],
		'440700'	=> [
			'zh-cn'			=> '江门市',
			'en'			=> 'jiang men shi',
		],
		'440703'	=> [
			'zh-cn'			=> '蓬江区',
			'en'			=> 'peng jiang qu',
		],
		'440704'	=> [
			'zh-cn'			=> '江海区',
			'en'			=> 'jiang hai qu',
		],
		'440705'	=> [
			'zh-cn'			=> '新会区',
			'en'			=> 'xin hui qu',
		],
		'440721'	=> [
			'zh-cn'			=> '新会县',
			'en'			=> 'xin hui xian',
		],
		'440722'	=> [
			'zh-cn'			=> '台山县',
			'en'			=> 'tai shan xian',
		],
		'440723'	=> [
			'zh-cn'			=> '恩平县',
			'en'			=> 'en ping xian',
		],
		'440724'	=> [
			'zh-cn'			=> '开平县',
			'en'			=> 'kai ping xian',
		],
		'440725'	=> [
			'zh-cn'			=> '鹤山县',
			'en'			=> 'he shan xian',
		],
		'440726'	=> [
			'zh-cn'			=> '阳江县',
			'en'			=> 'yang jiang xian',
		],
		'440727'	=> [
			'zh-cn'			=> '阳春县',
			'en'			=> 'yang chun xian',
		],
		'440781'	=> [
			'zh-cn'			=> '台山市',
			'en'			=> 'tai shan shi',
		],
		'440782'	=> [
			'zh-cn'			=> '新会市',
			'en'			=> 'xin hui shi',
		],
		'440783'	=> [
			'zh-cn'			=> '开平市',
			'en'			=> 'kai ping shi',
		],
		'440784'	=> [
			'zh-cn'			=> '鹤山市',
			'en'			=> 'he shan shi',
		],
		'440785'	=> [
			'zh-cn'			=> '恩平市',
			'en'			=> 'en ping shi',
		],
		'440800'	=> [
			'zh-cn'			=> '湛江市',
			'en'			=> 'zhan jiang shi',
		],
		'440802'	=> [
			'zh-cn'			=> '赤坎区',
			'en'			=> 'chi kan qu',
		],
		'440803'	=> [
			'zh-cn'			=> '霞山区',
			'en'			=> 'xia shan qu',
		],
		'440804'	=> [
			'zh-cn'			=> '坡头区',
			'en'			=> 'po tou qu',
		],
		'440811'	=> [
			'zh-cn'			=> '麻章区',
			'en'			=> 'ma zhang qu',
		],
		'440821'	=> [
			'zh-cn'			=> '吴川县',
			'en'			=> 'wu chuan xian',
		],
		'440822'	=> [
			'zh-cn'			=> '廉江县',
			'en'			=> 'lian jiang xian',
		],
		'440823'	=> [
			'zh-cn'			=> '遂溪县',
			'en'			=> 'sui xi xian',
		],
		'440824'	=> [
			'zh-cn'			=> '海康县',
			'en'			=> 'hai kang xian',
		],
		'440825'	=> [
			'zh-cn'			=> '徐闻县',
			'en'			=> 'xu wen xian',
		],
		'440881'	=> [
			'zh-cn'			=> '廉江市',
			'en'			=> 'lian jiang shi',
		],
		'440882'	=> [
			'zh-cn'			=> '雷州市',
			'en'			=> 'lei zhou shi',
		],
		'440883'	=> [
			'zh-cn'			=> '吴川市',
			'en'			=> 'wu chuan shi',
		],
		'440900'	=> [
			'zh-cn'			=> '茂名市',
			'en'			=> 'mao ming shi',
		],
		'440902'	=> [
			'zh-cn'			=> '茂南区',
			'en'			=> 'mao nan qu',
		],
		'440903'	=> [
			'zh-cn'			=> '茂港区',
			'en'			=> 'mao gang qu',
		],
		'440904'	=> [
			'zh-cn'			=> '电白区',
			'en'			=> 'dian bai qu',
		],
		'440921'	=> [
			'zh-cn'			=> '信宜县',
			'en'			=> 'xin yi xian',
		],
		'440922'	=> [
			'zh-cn'			=> '高州县',
			'en'			=> 'gao zhou xian',
		],
		'440923'	=> [
			'zh-cn'			=> '电白县',
			'en'			=> 'dian bai xian',
		],
		'440924'	=> [
			'zh-cn'			=> '化州县',
			'en'			=> 'hua zhou xian',
		],
		'440981'	=> [
			'zh-cn'			=> '高州市',
			'en'			=> 'gao zhou shi',
		],
		'440982'	=> [
			'zh-cn'			=> '化州市',
			'en'			=> 'hua zhou shi',
		],
		'440983'	=> [
			'zh-cn'			=> '信宜市',
			'en'			=> 'xin yi shi',
		],
		'441000'	=> [
			'zh-cn'			=> '海口市',
			'en'			=> 'hai kou shi',
		],
		'441002'	=> [
			'zh-cn'			=> '新华区',
			'en'			=> 'xin hua qu',
		],
		'441003'	=> [
			'zh-cn'			=> '立新区',
			'en'			=> 'li xin qu',
		],
		'441004'	=> [
			'zh-cn'			=> '东方红区',
			'en'			=> 'dong fang hong qu',
		],
		'441005'	=> [
			'zh-cn'			=> '秀英区',
			'en'			=> 'xiu ying qu',
		],
		'441100'	=> [
			'zh-cn'			=> '三亚市',
			'en'			=> 'san ya shi',
		],
		'441200'	=> [
			'zh-cn'			=> '肇庆市',
			'en'			=> 'zhao qing shi',
		],
		'441202'	=> [
			'zh-cn'			=> '端州区',
			'en'			=> 'duan zhou qu',
		],
		'441203'	=> [
			'zh-cn'			=> '鼎湖区',
			'en'			=> 'ding hu qu',
		],
		'441204'	=> [
			'zh-cn'			=> '高要区',
			'en'			=> 'gao yao qu',
		],
		'441221'	=> [
			'zh-cn'			=> '高要县',
			'en'			=> 'gao yao xian',
		],
		'441222'	=> [
			'zh-cn'			=> '四会县',
			'en'			=> 'si hui xian',
		],
		'441223'	=> [
			'zh-cn'			=> '广宁县',
			'en'			=> 'guang ning xian',
		],
		'441224'	=> [
			'zh-cn'			=> '怀集县',
			'en'			=> 'huai ji xian',
		],
		'441225'	=> [
			'zh-cn'			=> '封开县',
			'en'			=> 'feng kai xian',
		],
		'441226'	=> [
			'zh-cn'			=> '德庆县',
			'en'			=> 'de qing xian',
		],
		'441227'	=> [
			'zh-cn'			=> '云浮县',
			'en'			=> 'yun fu xian',
		],
		'441228'	=> [
			'zh-cn'			=> '新兴县',
			'en'			=> 'xin xing xian',
		],
		'441229'	=> [
			'zh-cn'			=> '郁南县',
			'en'			=> 'yu nan xian',
		],
		'441230'	=> [
			'zh-cn'			=> '罗定县',
			'en'			=> 'luo ding xian',
		],
		'441283'	=> [
			'zh-cn'			=> '高要市',
			'en'			=> 'gao yao shi',
		],
		'441284'	=> [
			'zh-cn'			=> '四会市',
			'en'			=> 'si hui shi',
		],
		'441300'	=> [
			'zh-cn'			=> '惠州市',
			'en'			=> 'hui zhou shi',
		],
		'441302'	=> [
			'zh-cn'			=> '惠城区',
			'en'			=> 'hui cheng qu',
		],
		'441303'	=> [
			'zh-cn'			=> '惠阳区',
			'en'			=> 'hui yang qu',
		],
		'441321'	=> [
			'zh-cn'			=> '惠阳县',
			'en'			=> 'hui yang xian',
		],
		'441322'	=> [
			'zh-cn'			=> '博罗县',
			'en'			=> 'bo luo xian',
		],
		'441323'	=> [
			'zh-cn'			=> '惠东县',
			'en'			=> 'hui dong xian',
		],
		'441324'	=> [
			'zh-cn'			=> '龙门县',
			'en'			=> 'long men xian',
		],
		'441381'	=> [
			'zh-cn'			=> '惠阳市',
			'en'			=> 'hui yang shi',
		],
		'441400'	=> [
			'zh-cn'			=> '梅州市',
			'en'			=> 'mei zhou shi',
		],
		'441402'	=> [
			'zh-cn'			=> '梅江区',
			'en'			=> 'mei jiang qu',
		],
		'441403'	=> [
			'zh-cn'			=> '梅县区',
			'en'			=> 'mei xian qu',
		],
		'441422'	=> [
			'zh-cn'			=> '大埔县',
			'en'			=> 'da bu xian',
		],
		'441423'	=> [
			'zh-cn'			=> '丰顺县',
			'en'			=> 'feng shun xian',
		],
		'441424'	=> [
			'zh-cn'			=> '五华县',
			'en'			=> 'wu hua xian',
		],
		'441425'	=> [
			'zh-cn'			=> '兴宁县',
			'en'			=> 'xing ning xian',
		],
		'441426'	=> [
			'zh-cn'			=> '平远县',
			'en'			=> 'ping yuan xian',
		],
		'441427'	=> [
			'zh-cn'			=> '蕉岭县',
			'en'			=> 'jiao ling xian',
		],
		'441481'	=> [
			'zh-cn'			=> '兴宁市',
			'en'			=> 'xing ning shi',
		],
		'441500'	=> [
			'zh-cn'			=> '汕尾市',
			'en'			=> 'shan wei shi',
		],
		'441521'	=> [
			'zh-cn'			=> '海丰县',
			'en'			=> 'hai feng xian',
		],
		'441522'	=> [
			'zh-cn'			=> '陆丰县',
			'en'			=> 'lu feng xian',
		],
		'441523'	=> [
			'zh-cn'			=> '陆河县',
			'en'			=> 'lu he xian',
		],
		'441581'	=> [
			'zh-cn'			=> '陆丰市',
			'en'			=> 'lu feng shi',
		],
		'441600'	=> [
			'zh-cn'			=> '河源市',
			'en'			=> 'he yuan shi',
		],
		'441602'	=> [
			'zh-cn'			=> '源城区',
			'en'			=> 'yuan cheng qu',
		],
		'441621'	=> [
			'zh-cn'			=> '紫金县',
			'en'			=> 'zi jin xian',
		],
		'441622'	=> [
			'zh-cn'			=> '龙川县',
			'en'			=> 'long chuan xian',
		],
		'441623'	=> [
			'zh-cn'			=> '连平县',
			'en'			=> 'lian ping xian',
		],
		'441624'	=> [
			'zh-cn'			=> '和平县',
			'en'			=> 'he ping xian',
		],
		'441625'	=> [
			'zh-cn'			=> '东源县',
			'en'			=> 'dong yuan xian',
		],
		'441700'	=> [
			'zh-cn'			=> '阳江市',
			'en'			=> 'yang jiang shi',
		],
		'441702'	=> [
			'zh-cn'			=> '江城区',
			'en'			=> 'jiang cheng qu',
		],
		'441703'	=> [
			'zh-cn'			=> '阳东区',
			'en'			=> 'yang dong qu',
		],
		'441704'	=> [
			'zh-cn'			=> '阳东区',
			'en'			=> 'yang dong qu',
		],
		'441721'	=> [
			'zh-cn'			=> '阳西县',
			'en'			=> 'yang xi xian',
		],
		'441722'	=> [
			'zh-cn'			=> '阳春县',
			'en'			=> 'yang chun xian',
		],
		'441723'	=> [
			'zh-cn'			=> '阳东县',
			'en'			=> 'yang dong xian',
		],
		'441781'	=> [
			'zh-cn'			=> '阳春市',
			'en'			=> 'yang chun shi',
		],
		'441800'	=> [
			'zh-cn'			=> '清远市',
			'en'			=> 'qing yuan shi',
		],
		'441802'	=> [
			'zh-cn'			=> '清城区',
			'en'			=> 'qing cheng qu',
		],
		'441803'	=> [
			'zh-cn'			=> '清新区',
			'en'			=> 'qing xin qu',
		],
		'441811'	=> [
			'zh-cn'			=> '清郊区',
			'en'			=> 'qing jiao qu',
		],
		'441821'	=> [
			'zh-cn'			=> '佛冈县',
			'en'			=> 'fo gang xian',
		],
		'441822'	=> [
			'zh-cn'			=> '英德县',
			'en'			=> 'ying de xian',
		],
		'441823'	=> [
			'zh-cn'			=> '阳山县',
			'en'			=> 'yang shan xian',
		],
		'441825'	=> [
			'zh-cn'			=> '连山壮族瑶族自治县',
			'en'			=> 'lian shan zhuang zu yao zu zi zhi xian',
		],
		'441826'	=> [
			'zh-cn'			=> '连南瑶族自治县',
			'en'			=> 'lian nan yao zu zi zhi xian',
		],
		'441827'	=> [
			'zh-cn'			=> '清新县',
			'en'			=> 'qing xin xian',
		],
		'441881'	=> [
			'zh-cn'			=> '英德市',
			'en'			=> 'ying de shi',
		],
		'441882'	=> [
			'zh-cn'			=> '连州市',
			'en'			=> 'lian zhou shi',
		],
		'441900'	=> [
			'zh-cn'			=> '东莞市',
			'en'			=> 'dong guan shi',
		],
		'442000'	=> [
			'zh-cn'			=> '中山市',
			'en'			=> 'zhong shan shi',
		],
		'442100'	=> [
			'zh-cn'			=> '海南行政区',
			'en'			=> 'hai nan xing zheng qu',
		],
		'442101'	=> [
			'zh-cn'			=> '海口市',
			'en'			=> 'hai kou shi',
		],
		'442121'	=> [
			'zh-cn'			=> '琼山县',
			'en'			=> 'qiong shan xian',
		],
		'442122'	=> [
			'zh-cn'			=> '文昌县',
			'en'			=> 'wen chang xian',
		],
		'442123'	=> [
			'zh-cn'			=> '琼海县',
			'en'			=> 'qiong hai xian',
		],
		'442124'	=> [
			'zh-cn'			=> '万宁县',
			'en'			=> 'wan ning xian',
		],
		'442125'	=> [
			'zh-cn'			=> '定安县',
			'en'			=> 'ding an xian',
		],
		'442126'	=> [
			'zh-cn'			=> '屯昌县',
			'en'			=> 'tun chang xian',
		],
		'442127'	=> [
			'zh-cn'			=> '澄迈县',
			'en'			=> 'cheng mai xian',
		],
		'442128'	=> [
			'zh-cn'			=> '临高县',
			'en'			=> 'lin gao xian',
		],
		'442200'	=> [
			'zh-cn'			=> '海南黎族苗族自治州',
			'en'			=> 'hai nan li zu miao zu zi zhi zhou',
		],
		'442201'	=> [
			'zh-cn'			=> '三亚市',
			'en'			=> 'san ya shi',
		],
		'442202'	=> [
			'zh-cn'			=> '通什市',
			'en'			=> 'tong shi shi',
		],
		'442222'	=> [
			'zh-cn'			=> '东方黎族自治县',
			'en'			=> 'dong fang li zu zi zhi xian',
		],
		'442223'	=> [
			'zh-cn'			=> '乐东黎族自治县',
			'en'			=> 'le dong li zu zi zhi xian',
		],
		'442224'	=> [
			'zh-cn'			=> '琼中黎族苗族自治县',
			'en'			=> 'qiong zhong li zu miao zu zi zhi xian',
		],
		'442225'	=> [
			'zh-cn'			=> '保亭黎族苗族自治县',
			'en'			=> 'bao ting li zu miao zu zi zhi xian',
		],
		'442226'	=> [
			'zh-cn'			=> '陵水黎族自治县',
			'en'			=> 'ling shui li zu zi zhi xian',
		],
		'442227'	=> [
			'zh-cn'			=> '白沙黎族自治县',
			'en'			=> 'bai sha li zu zi zhi xian',
		],
		'442228'	=> [
			'zh-cn'			=> '昌江黎族自治县',
			'en'			=> 'chang jiang li zu zi zhi xian',
		],
		'442300'	=> [
			'zh-cn'			=> '韶关地区',
			'en'			=> 'shao guan di qu',
		],
		'442322'	=> [
			'zh-cn'			=> '始兴县',
			'en'			=> 'shi xing xian',
		],
		'442323'	=> [
			'zh-cn'			=> '南雄县',
			'en'			=> 'nan xiong xian',
		],
		'442324'	=> [
			'zh-cn'			=> '仁化县',
			'en'			=> 'ren hua xian',
		],
		'442325'	=> [
			'zh-cn'			=> '乐昌县',
			'en'			=> 'le chang xian',
		],
		'442327'	=> [
			'zh-cn'			=> '阳山县',
			'en'			=> 'yang shan xian',
		],
		'442328'	=> [
			'zh-cn'			=> '英德县',
			'en'			=> 'ying de xian',
		],
		'442329'	=> [
			'zh-cn'			=> '翁源县',
			'en'			=> 'weng yuan xian',
		],
		'442330'	=> [
			'zh-cn'			=> '连山壮族瑶族自治县',
			'en'			=> 'lian shan zhuang zu yao zu zi zhi xian',
		],
		'442331'	=> [
			'zh-cn'			=> '连南瑶族自治县',
			'en'			=> 'lian nan yao zu zi zhi xian',
		],
		'442332'	=> [
			'zh-cn'			=> '乳源瑶族自治县',
			'en'			=> 'ru yuan yao zu zi zhi xian',
		],
		'442333'	=> [
			'zh-cn'			=> '清远县',
			'en'			=> 'qing yuan xian',
		],
		'442334'	=> [
			'zh-cn'			=> '佛冈县',
			'en'			=> 'fo gang xian',
		],
		'442400'	=> [
			'zh-cn'			=> '梅县地区',
			'en'			=> 'mei xian di qu',
		],
		'442401'	=> [
			'zh-cn'			=> '梅县市',
			'en'			=> 'mei xian shi',
		],
		'442422'	=> [
			'zh-cn'			=> '大埔县',
			'en'			=> 'da bu xian',
		],
		'442423'	=> [
			'zh-cn'			=> '丰顺县',
			'en'			=> 'feng shun xian',
		],
		'442424'	=> [
			'zh-cn'			=> '五华县',
			'en'			=> 'wu hua xian',
		],
		'442425'	=> [
			'zh-cn'			=> '兴宁县',
			'en'			=> 'xing ning xian',
		],
		'442426'	=> [
			'zh-cn'			=> '平远县',
			'en'			=> 'ping yuan xian',
		],
		'442427'	=> [
			'zh-cn'			=> '蕉岭县',
			'en'			=> 'jiao ling xian',
		],
		'442500'	=> [
			'zh-cn'			=> '惠阳地区',
			'en'			=> 'hui yang di qu',
		],
		'442501'	=> [
			'zh-cn'			=> '惠州市',
			'en'			=> 'hui zhou shi',
		],
		'442502'	=> [
			'zh-cn'			=> '东莞市',
			'en'			=> 'dong guan shi',
		],
		'442521'	=> [
			'zh-cn'			=> '惠阳县',
			'en'			=> 'hui yang xian',
		],
		'442522'	=> [
			'zh-cn'			=> '紫金县',
			'en'			=> 'zi jin xian',
		],
		'442523'	=> [
			'zh-cn'			=> '和平县',
			'en'			=> 'he ping xian',
		],
		'442524'	=> [
			'zh-cn'			=> '连平县',
			'en'			=> 'lian ping xian',
		],
		'442525'	=> [
			'zh-cn'			=> '河源县',
			'en'			=> 'he yuan xian',
		],
		'442526'	=> [
			'zh-cn'			=> '博罗县',
			'en'			=> 'bo luo xian',
		],
		'442527'	=> [
			'zh-cn'			=> '东莞县',
			'en'			=> 'dong guan xian',
		],
		'442528'	=> [
			'zh-cn'			=> '惠东县',
			'en'			=> 'hui dong xian',
		],
		'442529'	=> [
			'zh-cn'			=> '龙川县',
			'en'			=> 'long chuan xian',
		],
		'442530'	=> [
			'zh-cn'			=> '陆丰县',
			'en'			=> 'lu feng xian',
		],
		'442531'	=> [
			'zh-cn'			=> '海丰县',
			'en'			=> 'hai feng xian',
		],
		'442600'	=> [
			'zh-cn'			=> '佛山地区',
			'en'			=> 'fo shan di qu',
		],
		'442621'	=> [
			'zh-cn'			=> '鹤山县',
			'en'			=> 'he shan xian',
		],
		'442622'	=> [
			'zh-cn'			=> '南海县',
			'en'			=> 'nan hai xian',
		],
		'442623'	=> [
			'zh-cn'			=> '顺德县',
			'en'			=> 'shun de xian',
		],
		'442624'	=> [
			'zh-cn'			=> '高明县',
			'en'			=> 'gao ming xian',
		],
		'442625'	=> [
			'zh-cn'			=> '新会县',
			'en'			=> 'xin hui xian',
		],
		'442626'	=> [
			'zh-cn'			=> '台山县',
			'en'			=> 'tai shan xian',
		],
		'442627'	=> [
			'zh-cn'			=> '恩平县',
			'en'			=> 'en ping xian',
		],
		'442628'	=> [
			'zh-cn'			=> '开平县',
			'en'			=> 'kai ping xian',
		],
		'442629'	=> [
			'zh-cn'			=> '斗门县',
			'en'			=> 'dou men xian',
		],
		'442630'	=> [
			'zh-cn'			=> '中山县',
			'en'			=> 'zhong shan xian',
		],
		'442631'	=> [
			'zh-cn'			=> '三水县',
			'en'			=> 'san shui xian',
		],
		'442700'	=> [
			'zh-cn'			=> '汕头地区',
			'en'			=> 'shan tou di qu',
		],
		'442701'	=> [
			'zh-cn'			=> '潮州市',
			'en'			=> 'chao zhou shi',
		],
		'442721'	=> [
			'zh-cn'			=> '澄海县',
			'en'			=> 'cheng hai xian',
		],
		'442722'	=> [
			'zh-cn'			=> '饶平县',
			'en'			=> 'rao ping xian',
		],
		'442723'	=> [
			'zh-cn'			=> '南澳县',
			'en'			=> 'nan ao xian',
		],
		'442724'	=> [
			'zh-cn'			=> '潮阳县',
			'en'			=> 'chao yang xian',
		],
		'442725'	=> [
			'zh-cn'			=> '揭阳县',
			'en'			=> 'jie yang xian',
		],
		'442726'	=> [
			'zh-cn'			=> '揭西县',
			'en'			=> 'jie xi xian',
		],
		'442727'	=> [
			'zh-cn'			=> '普宁县',
			'en'			=> 'pu ning xian',
		],
		'442728'	=> [
			'zh-cn'			=> '惠来县',
			'en'			=> 'hui lai xian',
		],
		'442729'	=> [
			'zh-cn'			=> '陆丰县',
			'en'			=> 'lu feng xian',
		],
		'442730'	=> [
			'zh-cn'			=> '海丰县',
			'en'			=> 'hai feng xian',
		],
		'442731'	=> [
			'zh-cn'			=> '潮安县',
			'en'			=> 'chao an xian',
		],
		'442800'	=> [
			'zh-cn'			=> '肇庆地区',
			'en'			=> 'zhao qing di qu',
		],
		'442801'	=> [
			'zh-cn'			=> '肇庆市',
			'en'			=> 'zhao qing shi',
		],
		'442821'	=> [
			'zh-cn'			=> '高要县',
			'en'			=> 'gao yao xian',
		],
		'442822'	=> [
			'zh-cn'			=> '四会县',
			'en'			=> 'si hui xian',
		],
		'442823'	=> [
			'zh-cn'			=> '广宁县',
			'en'			=> 'guang ning xian',
		],
		'442824'	=> [
			'zh-cn'			=> '怀集县',
			'en'			=> 'huai ji xian',
		],
		'442825'	=> [
			'zh-cn'			=> '封开县',
			'en'			=> 'feng kai xian',
		],
		'442826'	=> [
			'zh-cn'			=> '德庆县',
			'en'			=> 'de qing xian',
		],
		'442827'	=> [
			'zh-cn'			=> '云浮县',
			'en'			=> 'yun fu xian',
		],
		'442828'	=> [
			'zh-cn'			=> '新兴县',
			'en'			=> 'xin xing xian',
		],
		'442829'	=> [
			'zh-cn'			=> '郁南县',
			'en'			=> 'yu nan xian',
		],
		'442830'	=> [
			'zh-cn'			=> '罗定县',
			'en'			=> 'luo ding xian',
		],
		'442900'	=> [
			'zh-cn'			=> '湛江地区',
			'en'			=> 'zhan jiang di qu',
		],
		'442921'	=> [
			'zh-cn'			=> '吴川县',
			'en'			=> 'wu chuan xian',
		],
		'442922'	=> [
			'zh-cn'			=> '廉江县',
			'en'			=> 'lian jiang xian',
		],
		'442923'	=> [
			'zh-cn'			=> '遂溪县',
			'en'			=> 'sui xi xian',
		],
		'442924'	=> [
			'zh-cn'			=> '海康县',
			'en'			=> 'hai kang xian',
		],
		'442925'	=> [
			'zh-cn'			=> '徐闻县',
			'en'			=> 'xu wen xian',
		],
		'442926'	=> [
			'zh-cn'			=> '阳江县',
			'en'			=> 'yang jiang xian',
		],
		'442927'	=> [
			'zh-cn'			=> '阳春县',
			'en'			=> 'yang chun xian',
		],
		'442928'	=> [
			'zh-cn'			=> '信宜县',
			'en'			=> 'xin yi xian',
		],
		'442929'	=> [
			'zh-cn'			=> '高州县',
			'en'			=> 'gao zhou xian',
		],
		'442930'	=> [
			'zh-cn'			=> '电白县',
			'en'			=> 'dian bai xian',
		],
		'442931'	=> [
			'zh-cn'			=> '化州县',
			'en'			=> 'hua zhou xian',
		],
		'445100'	=> [
			'zh-cn'			=> '潮州市',
			'en'			=> 'chao zhou shi',
		],
		'445102'	=> [
			'zh-cn'			=> '湘桥区',
			'en'			=> 'xiang qiao qu',
		],
		'445103'	=> [
			'zh-cn'			=> '潮安区',
			'en'			=> 'chao an qu',
		],
		'445121'	=> [
			'zh-cn'			=> '潮安县',
			'en'			=> 'chao an xian',
		],
		'445122'	=> [
			'zh-cn'			=> '饶平县',
			'en'			=> 'rao ping xian',
		],
		'445200'	=> [
			'zh-cn'			=> '揭阳市',
			'en'			=> 'jie yang shi',
		],
		'445202'	=> [
			'zh-cn'			=> '榕城区',
			'en'			=> 'rong cheng qu',
		],
		'445203'	=> [
			'zh-cn'			=> '揭东区',
			'en'			=> 'jie dong qu',
		],
		'445221'	=> [
			'zh-cn'			=> '揭东县',
			'en'			=> 'jie dong xian',
		],
		'445222'	=> [
			'zh-cn'			=> '揭西县',
			'en'			=> 'jie xi xian',
		],
		'445223'	=> [
			'zh-cn'			=> '普宁县',
			'en'			=> 'pu ning xian',
		],
		'445224'	=> [
			'zh-cn'			=> '惠来县',
			'en'			=> 'hui lai xian',
		],
		'445281'	=> [
			'zh-cn'			=> '普宁市',
			'en'			=> 'pu ning shi',
		],
		'445300'	=> [
			'zh-cn'			=> '云浮市',
			'en'			=> 'yun fu shi',
		],
		'445302'	=> [
			'zh-cn'			=> '云城区',
			'en'			=> 'yun cheng qu',
		],
		'445303'	=> [
			'zh-cn'			=> '云安区',
			'en'			=> 'yun an qu',
		],
		'445321'	=> [
			'zh-cn'			=> '新兴县',
			'en'			=> 'xin xing xian',
		],
		'445322'	=> [
			'zh-cn'			=> '郁南县',
			'en'			=> 'yu nan xian',
		],
		'445323'	=> [
			'zh-cn'			=> '云安县',
			'en'			=> 'yun an xian',
		],
		'445381'	=> [
			'zh-cn'			=> '罗定市',
			'en'			=> 'luo ding shi',
		],
		'449001'	=> [
			'zh-cn'			=> '顺德市',
			'en'			=> 'shun de shi',
		],
		'449002'	=> [
			'zh-cn'			=> '台山市',
			'en'			=> 'tai shan shi',
		],
		'449003'	=> [
			'zh-cn'			=> '番禺市',
			'en'			=> 'pan yu shi',
		],
		'449004'	=> [
			'zh-cn'			=> '南海市',
			'en'			=> 'nan hai shi',
		],
		'449005'	=> [
			'zh-cn'			=> '云浮市',
			'en'			=> 'yun fu shi',
		],
		'449006'	=> [
			'zh-cn'			=> '新会市',
			'en'			=> 'xin hui shi',
		],
		'449007'	=> [
			'zh-cn'			=> '开平市',
			'en'			=> 'kai ping shi',
		],
		'449008'	=> [
			'zh-cn'			=> '三水市',
			'en'			=> 'san shui shi',
		],
		'449009'	=> [
			'zh-cn'			=> '普宁市',
			'en'			=> 'pu ning shi',
		],
		'449010'	=> [
			'zh-cn'			=> '罗定市',
			'en'			=> 'luo ding shi',
		],
		'449011'	=> [
			'zh-cn'			=> '潮阳市',
			'en'			=> 'chao yang shi',
		],
		'449012'	=> [
			'zh-cn'			=> '高州市',
			'en'			=> 'gao zhou shi',
		],
		'449013'	=> [
			'zh-cn'			=> '花都市',
			'en'			=> 'hua du shi',
		],
		'449014'	=> [
			'zh-cn'			=> '高要市',
			'en'			=> 'gao yao shi',
		],
		'449015'	=> [
			'zh-cn'			=> '鹤山市',
			'en'			=> 'he shan shi',
		],
		'449016'	=> [
			'zh-cn'			=> '四会市',
			'en'			=> 'si hui shi',
		],
		'449017'	=> [
			'zh-cn'			=> '增城市',
			'en'			=> 'zeng cheng shi',
		],
		'449018'	=> [
			'zh-cn'			=> '廉江市',
			'en'			=> 'lian jiang shi',
		],
		'449019'	=> [
			'zh-cn'			=> '英德市',
			'en'			=> 'ying de shi',
		],
		'449020'	=> [
			'zh-cn'			=> '恩平市',
			'en'			=> 'en ping shi',
		],
		'449021'	=> [
			'zh-cn'			=> '从化市',
			'en'			=> 'cong hua shi',
		],
		'449022'	=> [
			'zh-cn'			=> '澄海市',
			'en'			=> 'cheng hai shi',
		],
		'449023'	=> [
			'zh-cn'			=> '高明市',
			'en'			=> 'gao ming shi',
		],
		'449024'	=> [
			'zh-cn'			=> '连州市',
			'en'			=> 'lian zhou shi',
		],
		'449025'	=> [
			'zh-cn'			=> '雷州市',
			'en'			=> 'lei zhou shi',
		],
		'449026'	=> [
			'zh-cn'			=> '乐昌市',
			'en'			=> 'le chang shi',
		],
		'449027'	=> [
			'zh-cn'			=> '阳春市',
			'en'			=> 'yang chun shi',
		],
		'449028'	=> [
			'zh-cn'			=> '惠阳市',
			'en'			=> 'hui yang shi',
		],
		'449029'	=> [
			'zh-cn'			=> '吴川市',
			'en'			=> 'wu chuan shi',
		],
		'449030'	=> [
			'zh-cn'			=> '兴宁市',
			'en'			=> 'xing ning shi',
		],
		'449031'	=> [
			'zh-cn'			=> '化州市',
			'en'			=> 'hua zhou shi',
		],
		'450000'	=> [
			'zh-cn'			=> '广西壮族自治区',
			'en'			=> 'guang xi zhuang zu zi zhi qu',
		],
		'450100'	=> [
			'zh-cn'			=> '南宁市',
			'en'			=> 'nan ning shi',
		],
		'450102'	=> [
			'zh-cn'			=> '兴宁区',
			'en'			=> 'xing ning qu',
		],
		'450103'	=> [
			'zh-cn'			=> '青秀区',
			'en'			=> 'qing xiu qu',
		],
		'450104'	=> [
			'zh-cn'			=> '城北区',
			'en'			=> 'cheng bei qu',
		],
		'450105'	=> [
			'zh-cn'			=> '江南区',
			'en'			=> 'jiang nan qu',
		],
		'450106'	=> [
			'zh-cn'			=> '永新区',
			'en'			=> 'yong xin qu',
		],
		'450107'	=> [
			'zh-cn'			=> '西乡塘区',
			'en'			=> 'xi xiang tang qu',
		],
		'450108'	=> [
			'zh-cn'			=> '良庆区',
			'en'			=> 'liang qing qu',
		],
		'450109'	=> [
			'zh-cn'			=> '邕宁区',
			'en'			=> 'yong ning qu',
		],
		'450110'	=> [
			'zh-cn'			=> '武鸣区',
			'en'			=> 'wu ming qu',
		],
		'450121'	=> [
			'zh-cn'			=> '邕宁县',
			'en'			=> 'yong ning xian',
		],
		'450122'	=> [
			'zh-cn'			=> '武鸣县',
			'en'			=> 'wu ming xian',
		],
		'450123'	=> [
			'zh-cn'			=> '隆安县',
			'en'			=> 'long an xian',
		],
		'450124'	=> [
			'zh-cn'			=> '马山县',
			'en'			=> 'ma shan xian',
		],
		'450125'	=> [
			'zh-cn'			=> '上林县',
			'en'			=> 'shang lin xian',
		],
		'450126'	=> [
			'zh-cn'			=> '宾阳县',
			'en'			=> 'bin yang xian',
		],
		'450200'	=> [
			'zh-cn'			=> '柳州市',
			'en'			=> 'liu zhou shi',
		],
		'450202'	=> [
			'zh-cn'			=> '城中区',
			'en'			=> 'cheng zhong qu',
		],
		'450203'	=> [
			'zh-cn'			=> '鱼峰区',
			'en'			=> 'yu feng qu',
		],
		'450204'	=> [
			'zh-cn'			=> '柳南区',
			'en'			=> 'liu nan qu',
		],
		'450205'	=> [
			'zh-cn'			=> '柳北区',
			'en'			=> 'liu bei qu',
		],
		'450206'	=> [
			'zh-cn'			=> '柳江区',
			'en'			=> 'liu jiang qu',
		],
		'450221'	=> [
			'zh-cn'			=> '柳江县',
			'en'			=> 'liu jiang xian',
		],
		'450222'	=> [
			'zh-cn'			=> '柳城县',
			'en'			=> 'liu cheng xian',
		],
		'450223'	=> [
			'zh-cn'			=> '鹿寨县',
			'en'			=> 'lu zhai xian',
		],
		'450224'	=> [
			'zh-cn'			=> '融安县',
			'en'			=> 'rong an xian',
		],
		'450225'	=> [
			'zh-cn'			=> '融水苗族自治县',
			'en'			=> 'rong shui miao zu zi zhi xian',
		],
		'450226'	=> [
			'zh-cn'			=> '三江侗族自治县',
			'en'			=> 'san jiang dong zu zi zhi xian',
		],
		'450300'	=> [
			'zh-cn'			=> '桂林市',
			'en'			=> 'gui lin shi',
		],
		'450302'	=> [
			'zh-cn'			=> '秀峰区',
			'en'			=> 'xiu feng qu',
		],
		'450303'	=> [
			'zh-cn'			=> '叠彩区',
			'en'			=> 'die cai qu',
		],
		'450304'	=> [
			'zh-cn'			=> '象山区',
			'en'			=> 'xiang shan qu',
		],
		'450305'	=> [
			'zh-cn'			=> '七星区',
			'en'			=> 'qi xing qu',
		],
		'450306'	=> [
			'zh-cn'			=> '市郊区',
			'en'			=> 'shi jiao qu',
		],
		'450311'	=> [
			'zh-cn'			=> '雁山区',
			'en'			=> 'yan shan qu',
		],
		'450312'	=> [
			'zh-cn'			=> '临桂区',
			'en'			=> 'lin gui qu',
		],
		'450321'	=> [
			'zh-cn'			=> '阳朔县',
			'en'			=> 'yang shuo xian',
		],
		'450322'	=> [
			'zh-cn'			=> '临桂县',
			'en'			=> 'lin gui xian',
		],
		'450323'	=> [
			'zh-cn'			=> '灵川县',
			'en'			=> 'ling chuan xian',
		],
		'450324'	=> [
			'zh-cn'			=> '全州县',
			'en'			=> 'quan zhou xian',
		],
		'450325'	=> [
			'zh-cn'			=> '兴安县',
			'en'			=> 'xing an xian',
		],
		'450326'	=> [
			'zh-cn'			=> '永福县',
			'en'			=> 'yong fu xian',
		],
		'450327'	=> [
			'zh-cn'			=> '灌阳县',
			'en'			=> 'guan yang xian',
		],
		'450328'	=> [
			'zh-cn'			=> '龙胜各族自治县',
			'en'			=> 'long sheng ge zu zi zhi xian',
		],
		'450329'	=> [
			'zh-cn'			=> '资源县',
			'en'			=> 'zi yuan xian',
		],
		'450330'	=> [
			'zh-cn'			=> '平乐县',
			'en'			=> 'ping le xian',
		],
		'450331'	=> [
			'zh-cn'			=> '荔浦县',
			'en'			=> 'li pu xian',
		],
		'450332'	=> [
			'zh-cn'			=> '恭城瑶族自治县',
			'en'			=> 'gong cheng yao zu zi zhi xian',
		],
		'450400'	=> [
			'zh-cn'			=> '梧州市',
			'en'			=> 'wu zhou shi',
		],
		'450402'	=> [
			'zh-cn'			=> '白云区',
			'en'			=> 'bai yun qu',
		],
		'450403'	=> [
			'zh-cn'			=> '万秀区',
			'en'			=> 'wan xiu qu',
		],
		'450404'	=> [
			'zh-cn'			=> '蝶山区',
			'en'			=> 'die shan qu',
		],
		'450405'	=> [
			'zh-cn'			=> '长洲区',
			'en'			=> 'chang zhou qu',
		],
		'450406'	=> [
			'zh-cn'			=> '龙圩区',
			'en'			=> 'long wei qu',
		],
		'450421'	=> [
			'zh-cn'			=> '苍梧县',
			'en'			=> 'cang wu xian',
		],
		'450423'	=> [
			'zh-cn'			=> '蒙山县',
			'en'			=> 'meng shan xian',
		],
		'450481'	=> [
			'zh-cn'			=> '岑溪市',
			'en'			=> 'cen xi shi',
		],
		'450500'	=> [
			'zh-cn'			=> '北海市',
			'en'			=> 'bei hai shi',
		],
		'450502'	=> [
			'zh-cn'			=> '海城区',
			'en'			=> 'hai cheng qu',
		],
		'450503'	=> [
			'zh-cn'			=> '银海区',
			'en'			=> 'yin hai qu',
		],
		'450512'	=> [
			'zh-cn'			=> '铁山港区',
			'en'			=> 'tie shan gang qu',
		],
		'450521'	=> [
			'zh-cn'			=> '合浦县',
			'en'			=> 'he pu xian',
		],
		'450600'	=> [
			'zh-cn'			=> '防城港市',
			'en'			=> 'fang cheng gang shi',
		],
		'450602'	=> [
			'zh-cn'			=> '港口区',
			'en'			=> 'gang kou qu',
		],
		'450603'	=> [
			'zh-cn'			=> '防城区',
			'en'			=> 'fang cheng qu',
		],
		'450621'	=> [
			'zh-cn'			=> '上思县',
			'en'			=> 'shang si xian',
		],
		'450681'	=> [
			'zh-cn'			=> '东兴市',
			'en'			=> 'dong xing shi',
		],
		'450700'	=> [
			'zh-cn'			=> '钦州市',
			'en'			=> 'qin zhou shi',
		],
		'450702'	=> [
			'zh-cn'			=> '钦南区',
			'en'			=> 'qin nan qu',
		],
		'450703'	=> [
			'zh-cn'			=> '钦北区',
			'en'			=> 'qin bei qu',
		],
		'450721'	=> [
			'zh-cn'			=> '灵山县',
			'en'			=> 'ling shan xian',
		],
		'450722'	=> [
			'zh-cn'			=> '浦北县',
			'en'			=> 'pu bei xian',
		],
		'450800'	=> [
			'zh-cn'			=> '贵港市',
			'en'			=> 'gui gang shi',
		],
		'450802'	=> [
			'zh-cn'			=> '港北区',
			'en'			=> 'gang bei qu',
		],
		'450803'	=> [
			'zh-cn'			=> '港南区',
			'en'			=> 'gang nan qu',
		],
		'450804'	=> [
			'zh-cn'			=> '覃塘区',
			'en'			=> 'tan tang qu',
		],
		'450821'	=> [
			'zh-cn'			=> '平南县',
			'en'			=> 'ping nan xian',
		],
		'450881'	=> [
			'zh-cn'			=> '桂平市',
			'en'			=> 'gui ping shi',
		],
		'450900'	=> [
			'zh-cn'			=> '玉林市',
			'en'			=> 'yu lin shi',
		],
		'450902'	=> [
			'zh-cn'			=> '玉州区',
			'en'			=> 'yu zhou qu',
		],
		'450903'	=> [
			'zh-cn'			=> '福绵区',
			'en'			=> 'fu mian qu',
		],
		'450922'	=> [
			'zh-cn'			=> '陆川县',
			'en'			=> 'lu chuan xian',
		],
		'450923'	=> [
			'zh-cn'			=> '博白县',
			'en'			=> 'bo bai xian',
		],
		'450924'	=> [
			'zh-cn'			=> '兴业县',
			'en'			=> 'xing ye xian',
		],
		'450981'	=> [
			'zh-cn'			=> '北流市',
			'en'			=> 'bei liu shi',
		],
		'451000'	=> [
			'zh-cn'			=> '百色市',
			'en'			=> 'bo se shi',
		],
		'451002'	=> [
			'zh-cn'			=> '右江区',
			'en'			=> 'you jiang qu',
		],
		'451021'	=> [
			'zh-cn'			=> '田阳县',
			'en'			=> 'tian yang xian',
		],
		'451022'	=> [
			'zh-cn'			=> '田东县',
			'en'			=> 'tian dong xian',
		],
		'451023'	=> [
			'zh-cn'			=> '平果县',
			'en'			=> 'ping guo xian',
		],
		'451024'	=> [
			'zh-cn'			=> '德保县',
			'en'			=> 'de bao xian',
		],
		'451025'	=> [
			'zh-cn'			=> '靖西县',
			'en'			=> 'jing xi xian',
		],
		'451026'	=> [
			'zh-cn'			=> '那坡县',
			'en'			=> 'na po xian',
		],
		'451027'	=> [
			'zh-cn'			=> '凌云县',
			'en'			=> 'ling yun xian',
		],
		'451028'	=> [
			'zh-cn'			=> '乐业县',
			'en'			=> 'le ye xian',
		],
		'451029'	=> [
			'zh-cn'			=> '田林县',
			'en'			=> 'tian lin xian',
		],
		'451030'	=> [
			'zh-cn'			=> '西林县',
			'en'			=> 'xi lin xian',
		],
		'451031'	=> [
			'zh-cn'			=> '隆林各族自治县',
			'en'			=> 'long lin ge zu zi zhi xian',
		],
		'451081'	=> [
			'zh-cn'			=> '靖西市',
			'en'			=> 'jing xi shi',
		],
		'451100'	=> [
			'zh-cn'			=> '贺州市',
			'en'			=> 'he zhou shi',
		],
		'451102'	=> [
			'zh-cn'			=> '八步区',
			'en'			=> 'ba bu qu',
		],
		'451103'	=> [
			'zh-cn'			=> '平桂区',
			'en'			=> 'ping gui qu',
		],
		'451121'	=> [
			'zh-cn'			=> '昭平县',
			'en'			=> 'zhao ping xian',
		],
		'451122'	=> [
			'zh-cn'			=> '钟山县',
			'en'			=> 'zhong shan xian',
		],
		'451123'	=> [
			'zh-cn'			=> '富川瑶族自治县',
			'en'			=> 'fu chuan yao zu zi zhi xian',
		],
		'451200'	=> [
			'zh-cn'			=> '河池市',
			'en'			=> 'he chi shi',
		],
		'451202'	=> [
			'zh-cn'			=> '金城江区',
			'en'			=> 'jin cheng jiang qu',
		],
		'451203'	=> [
			'zh-cn'			=> '宜州区',
			'en'			=> 'yi zhou qu',
		],
		'451221'	=> [
			'zh-cn'			=> '南丹县',
			'en'			=> 'nan dan xian',
		],
		'451222'	=> [
			'zh-cn'			=> '天峨县',
			'en'			=> 'tian e xian',
		],
		'451223'	=> [
			'zh-cn'			=> '凤山县',
			'en'			=> 'feng shan xian',
		],
		'451224'	=> [
			'zh-cn'			=> '东兰县',
			'en'			=> 'dong lan xian',
		],
		'451225'	=> [
			'zh-cn'			=> '罗城仫佬族自治县',
			'en'			=> 'luo cheng mu lao zu zi zhi xian',
		],
		'451226'	=> [
			'zh-cn'			=> '环江毛南族自治县',
			'en'			=> 'huan jiang mao nan zu zi zhi xian',
		],
		'451227'	=> [
			'zh-cn'			=> '巴马瑶族自治县',
			'en'			=> 'ba ma yao zu zi zhi xian',
		],
		'451228'	=> [
			'zh-cn'			=> '都安瑶族自治县',
			'en'			=> 'dou an yao zu zi zhi xian',
		],
		'451229'	=> [
			'zh-cn'			=> '大化瑶族自治县',
			'en'			=> 'da hua yao zu zi zhi xian',
		],
		'451281'	=> [
			'zh-cn'			=> '宜州市',
			'en'			=> 'yi zhou shi',
		],
		'451300'	=> [
			'zh-cn'			=> '来宾市',
			'en'			=> 'lai bin shi',
		],
		'451302'	=> [
			'zh-cn'			=> '兴宾区',
			'en'			=> 'xing bin qu',
		],
		'451321'	=> [
			'zh-cn'			=> '忻城县',
			'en'			=> 'xin cheng xian',
		],
		'451322'	=> [
			'zh-cn'			=> '象州县',
			'en'			=> 'xiang zhou xian',
		],
		'451323'	=> [
			'zh-cn'			=> '武宣县',
			'en'			=> 'wu xuan xian',
		],
		'451324'	=> [
			'zh-cn'			=> '金秀瑶族自治县',
			'en'			=> 'jin xiu yao zu zi zhi xian',
		],
		'451381'	=> [
			'zh-cn'			=> '合山市',
			'en'			=> 'he shan shi',
		],
		'451400'	=> [
			'zh-cn'			=> '崇左市',
			'en'			=> 'chong zuo shi',
		],
		'451402'	=> [
			'zh-cn'			=> '江州区',
			'en'			=> 'jiang zhou qu',
		],
		'451421'	=> [
			'zh-cn'			=> '扶绥县',
			'en'			=> 'fu sui xian',
		],
		'451422'	=> [
			'zh-cn'			=> '宁明县',
			'en'			=> 'ning ming xian',
		],
		'451423'	=> [
			'zh-cn'			=> '龙州县',
			'en'			=> 'long zhou xian',
		],
		'451424'	=> [
			'zh-cn'			=> '大新县',
			'en'			=> 'da xin xian',
		],
		'451425'	=> [
			'zh-cn'			=> '天等县',
			'en'			=> 'tian deng xian',
		],
		'451481'	=> [
			'zh-cn'			=> '凭祥市',
			'en'			=> 'ping xiang shi',
		],
		'452100'	=> [
			'zh-cn'			=> '南宁地区',
			'en'			=> 'nan ning di qu',
		],
		'452101'	=> [
			'zh-cn'			=> '凭祥市',
			'en'			=> 'ping xiang shi',
		],
		'452121'	=> [
			'zh-cn'			=> '邕宁县',
			'en'			=> 'yong ning xian',
		],
		'452123'	=> [
			'zh-cn'			=> '宾阳县',
			'en'			=> 'bin yang xian',
		],
		'452124'	=> [
			'zh-cn'			=> '上林县',
			'en'			=> 'shang lin xian',
		],
		'452125'	=> [
			'zh-cn'			=> '武鸣县',
			'en'			=> 'wu ming xian',
		],
		'452126'	=> [
			'zh-cn'			=> '隆安县',
			'en'			=> 'long an xian',
		],
		'452127'	=> [
			'zh-cn'			=> '马山县',
			'en'			=> 'ma shan xian',
		],
		'452128'	=> [
			'zh-cn'			=> '扶绥县',
			'en'			=> 'fu sui xian',
		],
		'452129'	=> [
			'zh-cn'			=> '崇左县',
			'en'			=> 'chong zuo xian',
		],
		'452130'	=> [
			'zh-cn'			=> '大新县',
			'en'			=> 'da xin xian',
		],
		'452131'	=> [
			'zh-cn'			=> '天等县',
			'en'			=> 'tian deng xian',
		],
		'452132'	=> [
			'zh-cn'			=> '宁明县',
			'en'			=> 'ning ming xian',
		],
		'452133'	=> [
			'zh-cn'			=> '龙州县',
			'en'			=> 'long zhou xian',
		],
		'452200'	=> [
			'zh-cn'			=> '柳州地区',
			'en'			=> 'liu zhou di qu',
		],
		'452201'	=> [
			'zh-cn'			=> '合山市',
			'en'			=> 'he shan shi',
		],
		'452221'	=> [
			'zh-cn'			=> '柳江县',
			'en'			=> 'liu jiang xian',
		],
		'452222'	=> [
			'zh-cn'			=> '柳城县',
			'en'			=> 'liu cheng xian',
		],
		'452223'	=> [
			'zh-cn'			=> '鹿寨县',
			'en'			=> 'lu zhai xian',
		],
		'452224'	=> [
			'zh-cn'			=> '象州县',
			'en'			=> 'xiang zhou xian',
		],
		'452225'	=> [
			'zh-cn'			=> '武宣县',
			'en'			=> 'wu xuan xian',
		],
		'452226'	=> [
			'zh-cn'			=> '来宾县',
			'en'			=> 'lai bin xian',
		],
		'452227'	=> [
			'zh-cn'			=> '融安县',
			'en'			=> 'rong an xian',
		],
		'452228'	=> [
			'zh-cn'			=> '三江侗族自治县',
			'en'			=> 'san jiang dong zu zi zhi xian',
		],
		'452229'	=> [
			'zh-cn'			=> '融水苗族自治县',
			'en'			=> 'rong shui miao zu zi zhi xian',
		],
		'452230'	=> [
			'zh-cn'			=> '金秀瑶族自治县',
			'en'			=> 'jin xiu yao zu zi zhi xian',
		],
		'452231'	=> [
			'zh-cn'			=> '忻城县',
			'en'			=> 'xin cheng xian',
		],
		'452300'	=> [
			'zh-cn'			=> '桂林地区',
			'en'			=> 'gui lin di qu',
		],
		'452321'	=> [
			'zh-cn'			=> '临桂县',
			'en'			=> 'lin gui xian',
		],
		'452322'	=> [
			'zh-cn'			=> '灵川县',
			'en'			=> 'ling chuan xian',
		],
		'452323'	=> [
			'zh-cn'			=> '全州县',
			'en'			=> 'quan zhou xian',
		],
		'452324'	=> [
			'zh-cn'			=> '兴安县',
			'en'			=> 'xing an xian',
		],
		'452325'	=> [
			'zh-cn'			=> '永福县',
			'en'			=> 'yong fu xian',
		],
		'452327'	=> [
			'zh-cn'			=> '灌阳县',
			'en'			=> 'guan yang xian',
		],
		'452328'	=> [
			'zh-cn'			=> '龙胜各族自治县',
			'en'			=> 'long sheng ge zu zi zhi xian',
		],
		'452329'	=> [
			'zh-cn'			=> '资源县',
			'en'			=> 'zi yuan xian',
		],
		'452330'	=> [
			'zh-cn'			=> '平乐县',
			'en'			=> 'ping le xian',
		],
		'452331'	=> [
			'zh-cn'			=> '荔浦县',
			'en'			=> 'li pu xian',
		],
		'452332'	=> [
			'zh-cn'			=> '恭城瑶族自治县',
			'en'			=> 'gong cheng yao zu zi zhi xian',
		],
		'452400'	=> [
			'zh-cn'			=> '贺州地区',
			'en'			=> 'he zhou di qu',
		],
		'452401'	=> [
			'zh-cn'			=> '岑溪市',
			'en'			=> 'cen xi shi',
		],
		'452402'	=> [
			'zh-cn'			=> '贺州市',
			'en'			=> 'he zhou shi',
		],
		'452421'	=> [
			'zh-cn'			=> '岑溪县',
			'en'			=> 'cen xi xian',
		],
		'452422'	=> [
			'zh-cn'			=> '苍梧县',
			'en'			=> 'cang wu xian',
		],
		'452424'	=> [
			'zh-cn'			=> '昭平县',
			'en'			=> 'zhao ping xian',
		],
		'452425'	=> [
			'zh-cn'			=> '蒙山县',
			'en'			=> 'meng shan xian',
		],
		'452427'	=> [
			'zh-cn'			=> '钟山县',
			'en'			=> 'zhong shan xian',
		],
		'452428'	=> [
			'zh-cn'			=> '富川瑶族自治县',
			'en'			=> 'fu chuan yao zu zi zhi xian',
		],
		'452500'	=> [
			'zh-cn'			=> '玉林地区',
			'en'			=> 'yu lin di qu',
		],
		'452501'	=> [
			'zh-cn'			=> '玉林市',
			'en'			=> 'yu lin shi',
		],
		'452502'	=> [
			'zh-cn'			=> '贵港市',
			'en'			=> 'gui gang shi',
		],
		'452503'	=> [
			'zh-cn'			=> '桂平市',
			'en'			=> 'gui ping shi',
		],
		'452504'	=> [
			'zh-cn'			=> '北流市',
			'en'			=> 'bei liu shi',
		],
		'452521'	=> [
			'zh-cn'			=> '玉林县',
			'en'			=> 'yu lin xian',
		],
		'452523'	=> [
			'zh-cn'			=> '桂平县',
			'en'			=> 'gui ping xian',
		],
		'452524'	=> [
			'zh-cn'			=> '平南县',
			'en'			=> 'ping nan xian',
		],
		'452526'	=> [
			'zh-cn'			=> '北流县',
			'en'			=> 'bei liu xian',
		],
		'452527'	=> [
			'zh-cn'			=> '陆川县',
			'en'			=> 'lu chuan xian',
		],
		'452528'	=> [
			'zh-cn'			=> '博白县',
			'en'			=> 'bo bai xian',
		],
		'452600'	=> [
			'zh-cn'			=> '百色地区',
			'en'			=> 'bo se di qu',
		],
		'452601'	=> [
			'zh-cn'			=> '百色市',
			'en'			=> 'bo se shi',
		],
		'452621'	=> [
			'zh-cn'			=> '百色县',
			'en'			=> 'bo se xian',
		],
		'452622'	=> [
			'zh-cn'			=> '田阳县',
			'en'			=> 'tian yang xian',
		],
		'452623'	=> [
			'zh-cn'			=> '田东县',
			'en'			=> 'tian dong xian',
		],
		'452624'	=> [
			'zh-cn'			=> '平果县',
			'en'			=> 'ping guo xian',
		],
		'452625'	=> [
			'zh-cn'			=> '德保县',
			'en'			=> 'de bao xian',
		],
		'452626'	=> [
			'zh-cn'			=> '靖西县',
			'en'			=> 'jing xi xian',
		],
		'452627'	=> [
			'zh-cn'			=> '那坡县',
			'en'			=> 'na po xian',
		],
		'452628'	=> [
			'zh-cn'			=> '凌云县',
			'en'			=> 'ling yun xian',
		],
		'452629'	=> [
			'zh-cn'			=> '乐业县',
			'en'			=> 'le ye xian',
		],
		'452630'	=> [
			'zh-cn'			=> '田林县',
			'en'			=> 'tian lin xian',
		],
		'452631'	=> [
			'zh-cn'			=> '隆林各族自治县',
			'en'			=> 'long lin ge zu zi zhi xian',
		],
		'452632'	=> [
			'zh-cn'			=> '西林县',
			'en'			=> 'xi lin xian',
		],
		'452700'	=> [
			'zh-cn'			=> '河池地区',
			'en'			=> 'he chi di qu',
		],
		'452701'	=> [
			'zh-cn'			=> '河池市',
			'en'			=> 'he chi shi',
		],
		'452702'	=> [
			'zh-cn'			=> '宜州市',
			'en'			=> 'yi zhou shi',
		],
		'452721'	=> [
			'zh-cn'			=> '河池县',
			'en'			=> 'he chi xian',
		],
		'452722'	=> [
			'zh-cn'			=> '宜山县',
			'en'			=> 'yi shan xian',
		],
		'452723'	=> [
			'zh-cn'			=> '罗城仫佬族自治县',
			'en'			=> 'luo cheng mu lao zu zi zhi xian',
		],
		'452724'	=> [
			'zh-cn'			=> '环江毛南族自治县',
			'en'			=> 'huan jiang mao nan zu zi zhi xian',
		],
		'452725'	=> [
			'zh-cn'			=> '南丹县',
			'en'			=> 'nan dan xian',
		],
		'452726'	=> [
			'zh-cn'			=> '天峨县',
			'en'			=> 'tian e xian',
		],
		'452727'	=> [
			'zh-cn'			=> '凤山县',
			'en'			=> 'feng shan xian',
		],
		'452728'	=> [
			'zh-cn'			=> '东兰县',
			'en'			=> 'dong lan xian',
		],
		'452729'	=> [
			'zh-cn'			=> '巴马瑶族自治县',
			'en'			=> 'ba ma yao zu zi zhi xian',
		],
		'452730'	=> [
			'zh-cn'			=> '都安瑶族自治县',
			'en'			=> 'dou an yao zu zi zhi xian',
		],
		'452731'	=> [
			'zh-cn'			=> '大化瑶族自治县',
			'en'			=> 'da hua yao zu zi zhi xian',
		],
		'452800'	=> [
			'zh-cn'			=> '钦州地区',
			'en'			=> 'qin zhou di qu',
		],
		'452801'	=> [
			'zh-cn'			=> '钦州市',
			'en'			=> 'qin zhou shi',
		],
		'452821'	=> [
			'zh-cn'			=> '上思县',
			'en'			=> 'shang si xian',
		],
		'452822'	=> [
			'zh-cn'			=> '防城各族自治县',
			'en'			=> 'fang cheng ge zu zi zhi xian',
		],
		'452823'	=> [
			'zh-cn'			=> '钦州县',
			'en'			=> 'qin zhou xian',
		],
		'452824'	=> [
			'zh-cn'			=> '灵山县',
			'en'			=> 'ling shan xian',
		],
		'452825'	=> [
			'zh-cn'			=> '合浦县',
			'en'			=> 'he pu xian',
		],
		'452826'	=> [
			'zh-cn'			=> '浦北县',
			'en'			=> 'pu bei xian',
		],
		'460000'	=> [
			'zh-cn'			=> '海南省',
			'en'			=> 'hai nan sheng',
		],
		'460001'	=> [
			'zh-cn'			=> '五指山市',
			'en'			=> 'wu zhi shan shi',
		],
		'460002'	=> [
			'zh-cn'			=> '琼海市',
			'en'			=> 'qiong hai shi',
		],
		'460003'	=> [
			'zh-cn'			=> '儋州市',
			'en'			=> 'dan zhou shi',
		],
		'460004'	=> [
			'zh-cn'			=> '琼山市',
			'en'			=> 'qiong shan shi',
		],
		'460005'	=> [
			'zh-cn'			=> '文昌市',
			'en'			=> 'wen chang shi',
		],
		'460006'	=> [
			'zh-cn'			=> '万宁市',
			'en'			=> 'wan ning shi',
		],
		'460007'	=> [
			'zh-cn'			=> '东方市',
			'en'			=> 'dong fang shi',
		],
		'460021'	=> [
			'zh-cn'			=> '琼山县',
			'en'			=> 'qiong shan xian',
		],
		'460022'	=> [
			'zh-cn'			=> '文昌县',
			'en'			=> 'wen chang xian',
		],
		'460023'	=> [
			'zh-cn'			=> '琼海县',
			'en'			=> 'qiong hai xian',
		],
		'460024'	=> [
			'zh-cn'			=> '万宁县',
			'en'			=> 'wan ning xian',
		],
		'460025'	=> [
			'zh-cn'			=> '定安县',
			'en'			=> 'ding an xian',
		],
		'460026'	=> [
			'zh-cn'			=> '屯昌县',
			'en'			=> 'tun chang xian',
		],
		'460027'	=> [
			'zh-cn'			=> '澄迈县',
			'en'			=> 'cheng mai xian',
		],
		'460028'	=> [
			'zh-cn'			=> '临高县',
			'en'			=> 'lin gao xian',
		],
		'460030'	=> [
			'zh-cn'			=> '白沙黎族自治县',
			'en'			=> 'bai sha li zu zi zhi xian',
		],
		'460031'	=> [
			'zh-cn'			=> '昌江黎族自治县',
			'en'			=> 'chang jiang li zu zi zhi xian',
		],
		'460032'	=> [
			'zh-cn'			=> '东方黎族自治县',
			'en'			=> 'dong fang li zu zi zhi xian',
		],
		'460033'	=> [
			'zh-cn'			=> '乐东黎族自治县',
			'en'			=> 'le dong li zu zi zhi xian',
		],
		'460034'	=> [
			'zh-cn'			=> '陵水黎族自治县',
			'en'			=> 'ling shui li zu zi zhi xian',
		],
		'460035'	=> [
			'zh-cn'			=> '保亭黎族苗族自治县',
			'en'			=> 'bao ting li zu miao zu zi zhi xian',
		],
		'460036'	=> [
			'zh-cn'			=> '琼中黎族苗族自治县',
			'en'			=> 'qiong zhong li zu miao zu zi zhi xian',
		],
		'460100'	=> [
			'zh-cn'			=> '海口市',
			'en'			=> 'hai kou shi',
		],
		'460102'	=> [
			'zh-cn'			=> '振东区',
			'en'			=> 'zhen dong qu',
		],
		'460103'	=> [
			'zh-cn'			=> '新华区',
			'en'			=> 'xin hua qu',
		],
		'460104'	=> [
			'zh-cn'			=> '秀英区',
			'en'			=> 'xiu ying qu',
		],
		'460105'	=> [
			'zh-cn'			=> '秀英区',
			'en'			=> 'xiu ying qu',
		],
		'460106'	=> [
			'zh-cn'			=> '龙华区',
			'en'			=> 'long hua qu',
		],
		'460107'	=> [
			'zh-cn'			=> '琼山区',
			'en'			=> 'qiong shan qu',
		],
		'460108'	=> [
			'zh-cn'			=> '美兰区',
			'en'			=> 'mei lan qu',
		],
		'460200'	=> [
			'zh-cn'			=> '三亚市',
			'en'			=> 'san ya shi',
		],
		'460202'	=> [
			'zh-cn'			=> '海棠区',
			'en'			=> 'hai tang qu',
		],
		'460203'	=> [
			'zh-cn'			=> '吉阳区',
			'en'			=> 'ji yang qu',
		],
		'460204'	=> [
			'zh-cn'			=> '天涯区',
			'en'			=> 'tian ya qu',
		],
		'460205'	=> [
			'zh-cn'			=> '崖州区',
			'en'			=> 'ya zhou qu',
		],
		'460300'	=> [
			'zh-cn'			=> '三沙市',
			'en'			=> 'san sha shi',
		],
		'460400'	=> [
			'zh-cn'			=> '儋州市',
			'en'			=> 'dan zhou shi',
		],
		'469001'	=> [
			'zh-cn'			=> '五指山市',
			'en'			=> 'wu zhi shan shi',
		],
		'469002'	=> [
			'zh-cn'			=> '琼海市',
			'en'			=> 'qiong hai shi',
		],
		'469003'	=> [
			'zh-cn'			=> '儋州市',
			'en'			=> 'dan zhou shi',
		],
		'469005'	=> [
			'zh-cn'			=> '文昌市',
			'en'			=> 'wen chang shi',
		],
		'469006'	=> [
			'zh-cn'			=> '万宁市',
			'en'			=> 'wan ning shi',
		],
		'469007'	=> [
			'zh-cn'			=> '东方市',
			'en'			=> 'dong fang shi',
		],
		'469021'	=> [
			'zh-cn'			=> '定安县',
			'en'			=> 'ding an xian',
		],
		'469022'	=> [
			'zh-cn'			=> '屯昌县',
			'en'			=> 'tun chang xian',
		],
		'469023'	=> [
			'zh-cn'			=> '澄迈县',
			'en'			=> 'cheng mai xian',
		],
		'469024'	=> [
			'zh-cn'			=> '临高县',
			'en'			=> 'lin gao xian',
		],
		'469025'	=> [
			'zh-cn'			=> '白沙黎族自治县',
			'en'			=> 'bai sha li zu zi zhi xian',
		],
		'469026'	=> [
			'zh-cn'			=> '昌江黎族自治县',
			'en'			=> 'chang jiang li zu zi zhi xian',
		],
		'469027'	=> [
			'zh-cn'			=> '乐东黎族自治县',
			'en'			=> 'le dong li zu zi zhi xian',
		],
		'469028'	=> [
			'zh-cn'			=> '陵水黎族自治县',
			'en'			=> 'ling shui li zu zi zhi xian',
		],
		'469029'	=> [
			'zh-cn'			=> '保亭黎族苗族自治县',
			'en'			=> 'bao ting li zu miao zu zi zhi xian',
		],
		'469030'	=> [
			'zh-cn'			=> '琼中黎族苗族自治县',
			'en'			=> 'qiong zhong li zu miao zu zi zhi xian',
		],
		'500000'	=> [
			'zh-cn'			=> '重庆市',
			'en'			=> 'chong qing shi',
		],
		'500101'	=> [
			'zh-cn'			=> '万州区',
			'en'			=> 'wan zhou qu',
		],
		'500102'	=> [
			'zh-cn'			=> '涪陵区',
			'en'			=> 'fu ling qu',
		],
		'500103'	=> [
			'zh-cn'			=> '渝中区',
			'en'			=> 'yu zhong qu',
		],
		'500104'	=> [
			'zh-cn'			=> '大渡口区',
			'en'			=> 'da du kou qu',
		],
		'500105'	=> [
			'zh-cn'			=> '江北区',
			'en'			=> 'jiang bei qu',
		],
		'500106'	=> [
			'zh-cn'			=> '沙坪坝区',
			'en'			=> 'sha ping ba qu',
		],
		'500107'	=> [
			'zh-cn'			=> '九龙坡区',
			'en'			=> 'jiu long po qu',
		],
		'500108'	=> [
			'zh-cn'			=> '南岸区',
			'en'			=> 'nan an qu',
		],
		'500109'	=> [
			'zh-cn'			=> '北碚区',
			'en'			=> 'bei bei qu',
		],
		'500110'	=> [
			'zh-cn'			=> '綦江区',
			'en'			=> 'qi jiang qu',
		],
		'500111'	=> [
			'zh-cn'			=> '大足区',
			'en'			=> 'da zu qu',
		],
		'500112'	=> [
			'zh-cn'			=> '渝北区',
			'en'			=> 'yu bei qu',
		],
		'500113'	=> [
			'zh-cn'			=> '巴南区',
			'en'			=> 'ba nan qu',
		],
		'500114'	=> [
			'zh-cn'			=> '黔江区',
			'en'			=> 'qian jiang qu',
		],
		'500115'	=> [
			'zh-cn'			=> '长寿区',
			'en'			=> 'chang shou qu',
		],
		'500116'	=> [
			'zh-cn'			=> '江津区',
			'en'			=> 'jiang jin qu',
		],
		'500117'	=> [
			'zh-cn'			=> '合川区',
			'en'			=> 'he chuan qu',
		],
		'500118'	=> [
			'zh-cn'			=> '永川区',
			'en'			=> 'yong chuan qu',
		],
		'500119'	=> [
			'zh-cn'			=> '南川区',
			'en'			=> 'nan chuan qu',
		],
		'500120'	=> [
			'zh-cn'			=> '璧山区',
			'en'			=> 'bi shan qu',
		],
		'500151'	=> [
			'zh-cn'			=> '铜梁区',
			'en'			=> 'tong liang qu',
		],
		'500152'	=> [
			'zh-cn'			=> '潼南区',
			'en'			=> 'tong nan qu',
		],
		'500153'	=> [
			'zh-cn'			=> '荣昌区',
			'en'			=> 'rong chang qu',
		],
		'500154'	=> [
			'zh-cn'			=> '开州区',
			'en'			=> 'kai zhou qu',
		],
		'500155'	=> [
			'zh-cn'			=> '梁平区',
			'en'			=> 'liang ping qu',
		],
		'500156'	=> [
			'zh-cn'			=> '武隆区',
			'en'			=> 'wu long qu',
		],
		'500181'	=> [
			'zh-cn'			=> '江津市',
			'en'			=> 'jiang jin shi',
		],
		'500182'	=> [
			'zh-cn'			=> '合川市',
			'en'			=> 'he chuan shi',
		],
		'500183'	=> [
			'zh-cn'			=> '永川市',
			'en'			=> 'yong chuan shi',
		],
		'500184'	=> [
			'zh-cn'			=> '南川市',
			'en'			=> 'nan chuan shi',
		],
		'500221'	=> [
			'zh-cn'			=> '长寿县',
			'en'			=> 'chang shou xian',
		],
		'500222'	=> [
			'zh-cn'			=> '綦江县',
			'en'			=> 'qi jiang xian',
		],
		'500223'	=> [
			'zh-cn'			=> '潼南县',
			'en'			=> 'tong nan xian',
		],
		'500224'	=> [
			'zh-cn'			=> '铜梁县',
			'en'			=> 'tong liang xian',
		],
		'500225'	=> [
			'zh-cn'			=> '大足县',
			'en'			=> 'da zu xian',
		],
		'500226'	=> [
			'zh-cn'			=> '荣昌县',
			'en'			=> 'rong chang xian',
		],
		'500227'	=> [
			'zh-cn'			=> '璧山县',
			'en'			=> 'bi shan xian',
		],
		'500228'	=> [
			'zh-cn'			=> '梁平县',
			'en'			=> 'liang ping xian',
		],
		'500229'	=> [
			'zh-cn'			=> '城口县',
			'en'			=> 'cheng kou xian',
		],
		'500230'	=> [
			'zh-cn'			=> '丰都县',
			'en'			=> 'feng du xian',
		],
		'500231'	=> [
			'zh-cn'			=> '垫江县',
			'en'			=> 'dian jiang xian',
		],
		'500232'	=> [
			'zh-cn'			=> '武隆县',
			'en'			=> 'wu long xian',
		],
		'500235'	=> [
			'zh-cn'			=> '云阳县',
			'en'			=> 'yun yang xian',
		],
		'500236'	=> [
			'zh-cn'			=> '奉节县',
			'en'			=> 'feng jie xian',
		],
		'500237'	=> [
			'zh-cn'			=> '巫山县',
			'en'			=> 'wu shan xian',
		],
		'500238'	=> [
			'zh-cn'			=> '巫溪县',
			'en'			=> 'wu xi xian',
		],
		'500239'	=> [
			'zh-cn'			=> '黔江土家族苗族自治县',
			'en'			=> 'qian jiang tu jia zu miao zu zi zhi xian',
		],
		'500240'	=> [
			'zh-cn'			=> '石柱土家族自治县',
			'en'			=> 'shi zhu tu jia zu zi zhi xian',
		],
		'500241'	=> [
			'zh-cn'			=> '秀山土家族苗族自治县',
			'en'			=> 'xiu shan tu jia zu miao zu zi zhi xian',
		],
		'500242'	=> [
			'zh-cn'			=> '酉阳土家族苗族自治县',
			'en'			=> 'you yang tu jia zu miao zu zi zhi xian',
		],
		'500243'	=> [
			'zh-cn'			=> '彭水苗族土家族自治县',
			'en'			=> 'peng shui miao zu tu jia zu zi zhi xian',
		],
		'510000'	=> [
			'zh-cn'			=> '四川省',
			'en'			=> 'si chuan sheng',
		],
		'510100'	=> [
			'zh-cn'			=> '成都市',
			'en'			=> 'cheng du shi',
		],
		'510102'	=> [
			'zh-cn'			=> '东城区',
			'en'			=> 'dong cheng qu',
		],
		'510103'	=> [
			'zh-cn'			=> '西城区',
			'en'			=> 'xi cheng qu',
		],
		'510104'	=> [
			'zh-cn'			=> '锦江区',
			'en'			=> 'jin jiang qu',
		],
		'510105'	=> [
			'zh-cn'			=> '青羊区',
			'en'			=> 'qing yang qu',
		],
		'510106'	=> [
			'zh-cn'			=> '金牛区',
			'en'			=> 'jin niu qu',
		],
		'510107'	=> [
			'zh-cn'			=> '武侯区',
			'en'			=> 'wu hou qu',
		],
		'510108'	=> [
			'zh-cn'			=> '成华区',
			'en'			=> 'cheng hua qu',
		],
		'510111'	=> [
			'zh-cn'			=> '金牛区',
			'en'			=> 'jin niu qu',
		],
		'510112'	=> [
			'zh-cn'			=> '龙泉驿区',
			'en'			=> 'long quan yi qu',
		],
		'510113'	=> [
			'zh-cn'			=> '青白江区',
			'en'			=> 'qing bai jiang qu',
		],
		'510114'	=> [
			'zh-cn'			=> '新都区',
			'en'			=> 'xin du qu',
		],
		'510115'	=> [
			'zh-cn'			=> '温江区',
			'en'			=> 'wen jiang qu',
		],
		'510116'	=> [
			'zh-cn'			=> '双流区',
			'en'			=> 'shuang liu qu',
		],
		'510117'	=> [
			'zh-cn'			=> '郫都区',
			'en'			=> 'pi dou qu',
		],
		'510121'	=> [
			'zh-cn'			=> '金堂县',
			'en'			=> 'jin tang xian',
		],
		'510122'	=> [
			'zh-cn'			=> '双流县',
			'en'			=> 'shuang liu xian',
		],
		'510123'	=> [
			'zh-cn'			=> '温江县',
			'en'			=> 'wen jiang xian',
		],
		'510125'	=> [
			'zh-cn'			=> '新都县',
			'en'			=> 'xin du xian',
		],
		'510128'	=> [
			'zh-cn'			=> '崇庆县',
			'en'			=> 'chong qing xian',
		],
		'510129'	=> [
			'zh-cn'			=> '大邑县',
			'en'			=> 'da yi xian',
		],
		'510130'	=> [
			'zh-cn'			=> '邛崃县',
			'en'			=> 'qiong lai xian',
		],
		'510131'	=> [
			'zh-cn'			=> '蒲江县',
			'en'			=> 'pu jiang xian',
		],
		'510132'	=> [
			'zh-cn'			=> '新津县',
			'en'			=> 'xin jin xian',
		],
		'510181'	=> [
			'zh-cn'			=> '都江堰市',
			'en'			=> 'du jiang yan shi',
		],
		'510182'	=> [
			'zh-cn'			=> '彭州市',
			'en'			=> 'peng zhou shi',
		],
		'510183'	=> [
			'zh-cn'			=> '邛崃市',
			'en'			=> 'qiong lai shi',
		],
		'510184'	=> [
			'zh-cn'			=> '崇州市',
			'en'			=> 'chong zhou shi',
		],
		'510185'	=> [
			'zh-cn'			=> '简阳市',
			'en'			=> 'jian yang shi',
		],
		'510200'	=> [
			'zh-cn'			=> '重庆市',
			'en'			=> 'chong qing shi',
		],
		'510202'	=> [
			'zh-cn'			=> '渝中区',
			'en'			=> 'yu zhong qu',
		],
		'510203'	=> [
			'zh-cn'			=> '大渡口区',
			'en'			=> 'da du kou qu',
		],
		'510211'	=> [
			'zh-cn'			=> '江北区',
			'en'			=> 'jiang bei qu',
		],
		'510212'	=> [
			'zh-cn'			=> '沙坪坝区',
			'en'			=> 'sha ping ba qu',
		],
		'510213'	=> [
			'zh-cn'			=> '九龙坡区',
			'en'			=> 'jiu long po qu',
		],
		'510214'	=> [
			'zh-cn'			=> '南岸区',
			'en'			=> 'nan an qu',
		],
		'510215'	=> [
			'zh-cn'			=> '北碚区',
			'en'			=> 'bei bei qu',
		],
		'510216'	=> [
			'zh-cn'			=> '万盛区',
			'en'			=> 'wan sheng qu',
		],
		'510217'	=> [
			'zh-cn'			=> '双桥区',
			'en'			=> 'shuang qiao qu',
		],
		'510219'	=> [
			'zh-cn'			=> '巴南区',
			'en'			=> 'ba nan qu',
		],
		'510221'	=> [
			'zh-cn'			=> '长寿县',
			'en'			=> 'chang shou xian',
		],
		'510223'	=> [
			'zh-cn'			=> '綦江县',
			'en'			=> 'qi jiang xian',
		],
		'510224'	=> [
			'zh-cn'			=> '江北县',
			'en'			=> 'jiang bei xian',
		],
		'510225'	=> [
			'zh-cn'			=> '江津县',
			'en'			=> 'jiang jin xian',
		],
		'510226'	=> [
			'zh-cn'			=> '合川县',
			'en'			=> 'he chuan xian',
		],
		'510227'	=> [
			'zh-cn'			=> '潼南县',
			'en'			=> 'tong nan xian',
		],
		'510228'	=> [
			'zh-cn'			=> '铜梁县',
			'en'			=> 'tong liang xian',
		],
		'510229'	=> [
			'zh-cn'			=> '永川县',
			'en'			=> 'yong chuan xian',
		],
		'510230'	=> [
			'zh-cn'			=> '大足县',
			'en'			=> 'da zu xian',
		],
		'510231'	=> [
			'zh-cn'			=> '荣昌县',
			'en'			=> 'rong chang xian',
		],
		'510232'	=> [
			'zh-cn'			=> '璧山县',
			'en'			=> 'bi shan xian',
		],
		'510281'	=> [
			'zh-cn'			=> '永川市',
			'en'			=> 'yong chuan shi',
		],
		'510282'	=> [
			'zh-cn'			=> '合川市',
			'en'			=> 'he chuan shi',
		],
		'510283'	=> [
			'zh-cn'			=> '江津市',
			'en'			=> 'jiang jin shi',
		],
		'510300'	=> [
			'zh-cn'			=> '自贡市',
			'en'			=> 'zi gong shi',
		],
		'510302'	=> [
			'zh-cn'			=> '自流井区',
			'en'			=> 'zi liu jing qu',
		],
		'510303'	=> [
			'zh-cn'			=> '贡井区',
			'en'			=> 'gong jing qu',
		],
		'510304'	=> [
			'zh-cn'			=> '大安区',
			'en'			=> 'da an qu',
		],
		'510311'	=> [
			'zh-cn'			=> '沿滩区',
			'en'			=> 'yan tan qu',
		],
		'510322'	=> [
			'zh-cn'			=> '富顺县',
			'en'			=> 'fu shun xian',
		],
		'510400'	=> [
			'zh-cn'			=> '攀枝花市',
			'en'			=> 'pan zhi hua shi',
		],
		'510404'	=> [
			'zh-cn'			=> '仁和区',
			'en'			=> 'ren he qu',
		],
		'510411'	=> [
			'zh-cn'			=> '仁和区',
			'en'			=> 'ren he qu',
		],
		'510421'	=> [
			'zh-cn'			=> '米易县',
			'en'			=> 'mi yi xian',
		],
		'510422'	=> [
			'zh-cn'			=> '盐边县',
			'en'			=> 'yan bian xian',
		],
		'510500'	=> [
			'zh-cn'			=> '泸州市',
			'en'			=> 'lu zhou shi',
		],
		'510502'	=> [
			'zh-cn'			=> '江阳区',
			'en'			=> 'jiang yang qu',
		],
		'510503'	=> [
			'zh-cn'			=> '纳溪区',
			'en'			=> 'na xi qu',
		],
		'510504'	=> [
			'zh-cn'			=> '龙马潭区',
			'en'			=> 'long ma tan qu',
		],
		'510522'	=> [
			'zh-cn'			=> '合江县',
			'en'			=> 'he jiang xian',
		],
		'510523'	=> [
			'zh-cn'			=> '纳溪县',
			'en'			=> 'na xi xian',
		],
		'510524'	=> [
			'zh-cn'			=> '叙永县',
			'en'			=> 'xu yong xian',
		],
		'510525'	=> [
			'zh-cn'			=> '古蔺县',
			'en'			=> 'gu lin xian',
		],
		'510600'	=> [
			'zh-cn'			=> '德阳市',
			'en'			=> 'de yang shi',
		],
		'510602'	=> [
			'zh-cn'			=> '市中区',
			'en'			=> 'shi zhong qu',
		],
		'510603'	=> [
			'zh-cn'			=> '旌阳区',
			'en'			=> 'jing yang qu',
		],
		'510621'	=> [
			'zh-cn'			=> '德阳县',
			'en'			=> 'de yang xian',
		],
		'510622'	=> [
			'zh-cn'			=> '绵竹县',
			'en'			=> 'mian zhu xian',
		],
		'510623'	=> [
			'zh-cn'			=> '中江县',
			'en'			=> 'zhong jiang xian',
		],
		'510624'	=> [
			'zh-cn'			=> '广汉县',
			'en'			=> 'guang han xian',
		],
		'510625'	=> [
			'zh-cn'			=> '什邡县',
			'en'			=> 'shi fang xian',
		],
		'510626'	=> [
			'zh-cn'			=> '罗江县',
			'en'			=> 'luo jiang xian',
		],
		'510681'	=> [
			'zh-cn'			=> '广汉市',
			'en'			=> 'guang han shi',
		],
		'510682'	=> [
			'zh-cn'			=> '什邡市',
			'en'			=> 'shi fang shi',
		],
		'510683'	=> [
			'zh-cn'			=> '绵竹市',
			'en'			=> 'mian zhu shi',
		],
		'510700'	=> [
			'zh-cn'			=> '绵阳市',
			'en'			=> 'mian yang shi',
		],
		'510702'	=> [
			'zh-cn'			=> '市中区',
			'en'			=> 'shi zhong qu',
		],
		'510703'	=> [
			'zh-cn'			=> '涪城区',
			'en'			=> 'fu cheng qu',
		],
		'510704'	=> [
			'zh-cn'			=> '游仙区',
			'en'			=> 'you xian qu',
		],
		'510705'	=> [
			'zh-cn'			=> '安州区',
			'en'			=> 'an zhou qu',
		],
		'510721'	=> [
			'zh-cn'			=> '江油县',
			'en'			=> 'jiang you xian',
		],
		'510722'	=> [
			'zh-cn'			=> '三台县',
			'en'			=> 'san tai xian',
		],
		'510723'	=> [
			'zh-cn'			=> '盐亭县',
			'en'			=> 'yan ting xian',
		],
		'510725'	=> [
			'zh-cn'			=> '梓潼县',
			'en'			=> 'zi tong xian',
		],
		'510726'	=> [
			'zh-cn'			=> '北川羌族自治县',
			'en'			=> 'bei chuan qiang zu zi zhi xian',
		],
		'510727'	=> [
			'zh-cn'			=> '平武县',
			'en'			=> 'ping wu xian',
		],
		'510781'	=> [
			'zh-cn'			=> '江油市',
			'en'			=> 'jiang you shi',
		],
		'510800'	=> [
			'zh-cn'			=> '广元市',
			'en'			=> 'guang yuan shi',
		],
		'510802'	=> [
			'zh-cn'			=> '利州区',
			'en'			=> 'li zhou qu',
		],
		'510811'	=> [
			'zh-cn'			=> '昭化区',
			'en'			=> 'zhao hua qu',
		],
		'510812'	=> [
			'zh-cn'			=> '朝天区',
			'en'			=> 'chao tian qu',
		],
		'510821'	=> [
			'zh-cn'			=> '旺苍县',
			'en'			=> 'wang cang xian',
		],
		'510822'	=> [
			'zh-cn'			=> '青川县',
			'en'			=> 'qing chuan xian',
		],
		'510823'	=> [
			'zh-cn'			=> '剑阁县',
			'en'			=> 'jian ge xian',
		],
		'510824'	=> [
			'zh-cn'			=> '苍溪县',
			'en'			=> 'cang xi xian',
		],
		'510900'	=> [
			'zh-cn'			=> '遂宁市',
			'en'			=> 'sui ning shi',
		],
		'510902'	=> [
			'zh-cn'			=> '市中区',
			'en'			=> 'shi zhong qu',
		],
		'510903'	=> [
			'zh-cn'			=> '船山区',
			'en'			=> 'chuan shan qu',
		],
		'510904'	=> [
			'zh-cn'			=> '安居区',
			'en'			=> 'an ju qu',
		],
		'510921'	=> [
			'zh-cn'			=> '蓬溪县',
			'en'			=> 'peng xi xian',
		],
		'510922'	=> [
			'zh-cn'			=> '射洪县',
			'en'			=> 'she hong xian',
		],
		'510923'	=> [
			'zh-cn'			=> '大英县',
			'en'			=> 'da ying xian',
		],
		'511000'	=> [
			'zh-cn'			=> '内江市',
			'en'			=> 'nei jiang shi',
		],
		'511002'	=> [
			'zh-cn'			=> '市中区',
			'en'			=> 'shi zhong qu',
		],
		'511011'	=> [
			'zh-cn'			=> '东兴区',
			'en'			=> 'dong xing qu',
		],
		'511021'	=> [
			'zh-cn'			=> '内江县',
			'en'			=> 'nei jiang xian',
		],
		'511022'	=> [
			'zh-cn'			=> '乐至县',
			'en'			=> 'le zhi xian',
		],
		'511023'	=> [
			'zh-cn'			=> '安岳县',
			'en'			=> 'an yue xian',
		],
		'511024'	=> [
			'zh-cn'			=> '威远县',
			'en'			=> 'wei yuan xian',
		],
		'511025'	=> [
			'zh-cn'			=> '资中县',
			'en'			=> 'zi zhong xian',
		],
		'511026'	=> [
			'zh-cn'			=> '资阳县',
			'en'			=> 'zi yang xian',
		],
		'511027'	=> [
			'zh-cn'			=> '简阳县',
			'en'			=> 'jian yang xian',
		],
		'511028'	=> [
			'zh-cn'			=> '隆昌县',
			'en'			=> 'long chang xian',
		],
		'511081'	=> [
			'zh-cn'			=> '资阳市',
			'en'			=> 'zi yang shi',
		],
		'511082'	=> [
			'zh-cn'			=> '简阳市',
			'en'			=> 'jian yang shi',
		],
		'511100'	=> [
			'zh-cn'			=> '乐山市',
			'en'			=> 'le shan shi',
		],
		'511102'	=> [
			'zh-cn'			=> '市中区',
			'en'			=> 'shi zhong qu',
		],
		'511103'	=> [
			'zh-cn'			=> '沙湾区',
			'en'			=> 'sha wan qu',
		],
		'511104'	=> [
			'zh-cn'			=> '金口河区',
			'en'			=> 'jin kou he qu',
		],
		'511111'	=> [
			'zh-cn'			=> '沙湾区',
			'en'			=> 'sha wan qu',
		],
		'511112'	=> [
			'zh-cn'			=> '五通桥区',
			'en'			=> 'wu tong qiao qu',
		],
		'511113'	=> [
			'zh-cn'			=> '金口河区',
			'en'			=> 'jin kou he qu',
		],
		'511121'	=> [
			'zh-cn'			=> '仁寿县',
			'en'			=> 'ren shou xian',
		],
		'511122'	=> [
			'zh-cn'			=> '眉山县',
			'en'			=> 'mei shan xian',
		],
		'511123'	=> [
			'zh-cn'			=> '犍为县',
			'en'			=> 'qian wei xian',
		],
		'511124'	=> [
			'zh-cn'			=> '井研县',
			'en'			=> 'jing yan xian',
		],
		'511125'	=> [
			'zh-cn'			=> '峨眉县',
			'en'			=> 'e mei xian',
		],
		'511126'	=> [
			'zh-cn'			=> '夹江县',
			'en'			=> 'jia jiang xian',
		],
		'511127'	=> [
			'zh-cn'			=> '洪雅县',
			'en'			=> 'hong ya xian',
		],
		'511128'	=> [
			'zh-cn'			=> '彭山县',
			'en'			=> 'peng shan xian',
		],
		'511129'	=> [
			'zh-cn'			=> '沐川县',
			'en'			=> 'mu chuan xian',
		],
		'511130'	=> [
			'zh-cn'			=> '青神县',
			'en'			=> 'qing shen xian',
		],
		'511131'	=> [
			'zh-cn'			=> '丹棱县',
			'en'			=> 'dan leng xian',
		],
		'511132'	=> [
			'zh-cn'			=> '峨边彝族自治县',
			'en'			=> 'e bian yi zu zi zhi xian',
		],
		'511133'	=> [
			'zh-cn'			=> '马边彝族自治县',
			'en'			=> 'ma bian yi zu zi zhi xian',
		],
		'511181'	=> [
			'zh-cn'			=> '峨眉山市',
			'en'			=> 'e mei shan shi',
		],
		'511200'	=> [
			'zh-cn'			=> '万县市',
			'en'			=> 'wan xian shi',
		],
		'511202'	=> [
			'zh-cn'			=> '龙宝区',
			'en'			=> 'long bao qu',
		],
		'511203'	=> [
			'zh-cn'			=> '天城区',
			'en'			=> 'tian cheng qu',
		],
		'511204'	=> [
			'zh-cn'			=> '五桥区',
			'en'			=> 'wu qiao qu',
		],
		'511223'	=> [
			'zh-cn'			=> '梁平县',
			'en'			=> 'liang ping xian',
		],
		'511224'	=> [
			'zh-cn'			=> '云阳县',
			'en'			=> 'yun yang xian',
		],
		'511225'	=> [
			'zh-cn'			=> '奉节县',
			'en'			=> 'feng jie xian',
		],
		'511226'	=> [
			'zh-cn'			=> '巫山县',
			'en'			=> 'wu shan xian',
		],
		'511227'	=> [
			'zh-cn'			=> '巫溪县',
			'en'			=> 'wu xi xian',
		],
		'511228'	=> [
			'zh-cn'			=> '城口县',
			'en'			=> 'cheng kou xian',
		],
		'511300'	=> [
			'zh-cn'			=> '南充市',
			'en'			=> 'nan chong shi',
		],
		'511302'	=> [
			'zh-cn'			=> '顺庆区',
			'en'			=> 'shun qing qu',
		],
		'511303'	=> [
			'zh-cn'			=> '高坪区',
			'en'			=> 'gao ping qu',
		],
		'511304'	=> [
			'zh-cn'			=> '嘉陵区',
			'en'			=> 'jia ling qu',
		],
		'511321'	=> [
			'zh-cn'			=> '南部县',
			'en'			=> 'nan bu xian',
		],
		'511322'	=> [
			'zh-cn'			=> '营山县',
			'en'			=> 'ying shan xian',
		],
		'511323'	=> [
			'zh-cn'			=> '蓬安县',
			'en'			=> 'peng an xian',
		],
		'511324'	=> [
			'zh-cn'			=> '仪陇县',
			'en'			=> 'yi long xian',
		],
		'511325'	=> [
			'zh-cn'			=> '西充县',
			'en'			=> 'xi chong xian',
		],
		'511381'	=> [
			'zh-cn'			=> '阆中市',
			'en'			=> 'lang zhong shi',
		],
		'511400'	=> [
			'zh-cn'			=> '眉山市',
			'en'			=> 'mei shan shi',
		],
		'511402'	=> [
			'zh-cn'			=> '东坡区',
			'en'			=> 'dong po qu',
		],
		'511403'	=> [
			'zh-cn'			=> '彭山区',
			'en'			=> 'peng shan qu',
		],
		'511421'	=> [
			'zh-cn'			=> '仁寿县',
			'en'			=> 'ren shou xian',
		],
		'511422'	=> [
			'zh-cn'			=> '彭山县',
			'en'			=> 'peng shan xian',
		],
		'511423'	=> [
			'zh-cn'			=> '洪雅县',
			'en'			=> 'hong ya xian',
		],
		'511424'	=> [
			'zh-cn'			=> '丹棱县',
			'en'			=> 'dan leng xian',
		],
		'511425'	=> [
			'zh-cn'			=> '青神县',
			'en'			=> 'qing shen xian',
		],
		'511500'	=> [
			'zh-cn'			=> '宜宾市',
			'en'			=> 'yi bin shi',
		],
		'511502'	=> [
			'zh-cn'			=> '翠屏区',
			'en'			=> 'cui ping qu',
		],
		'511503'	=> [
			'zh-cn'			=> '南溪区',
			'en'			=> 'nan xi qu',
		],
		'511521'	=> [
			'zh-cn'			=> '宜宾县',
			'en'			=> 'yi bin xian',
		],
		'511522'	=> [
			'zh-cn'			=> '南溪县',
			'en'			=> 'nan xi xian',
		],
		'511523'	=> [
			'zh-cn'			=> '江安县',
			'en'			=> 'jiang an xian',
		],
		'511524'	=> [
			'zh-cn'			=> '长宁县',
			'en'			=> 'chang ning xian',
		],
		'511527'	=> [
			'zh-cn'			=> '筠连县',
			'en'			=> 'jun lian xian',
		],
		'511528'	=> [
			'zh-cn'			=> '兴文县',
			'en'			=> 'xing wen xian',
		],
		'511529'	=> [
			'zh-cn'			=> '屏山县',
			'en'			=> 'ping shan xian',
		],
		'511600'	=> [
			'zh-cn'			=> '广安市',
			'en'			=> 'guang an shi',
		],
		'511602'	=> [
			'zh-cn'			=> '广安区',
			'en'			=> 'guang an qu',
		],
		'511603'	=> [
			'zh-cn'			=> '前锋区',
			'en'			=> 'qian feng qu',
		],
		'511621'	=> [
			'zh-cn'			=> '岳池县',
			'en'			=> 'yue chi xian',
		],
		'511622'	=> [
			'zh-cn'			=> '武胜县',
			'en'			=> 'wu sheng xian',
		],
		'511623'	=> [
			'zh-cn'			=> '邻水县',
			'en'			=> 'lin shui xian',
		],
		'511681'	=> [
			'zh-cn'			=> '华蓥市',
			'en'			=> 'hua ying shi',
		],
		'511700'	=> [
			'zh-cn'			=> '达州市',
			'en'			=> 'da zhou shi',
		],
		'511702'	=> [
			'zh-cn'			=> '通川区',
			'en'			=> 'tong chuan qu',
		],
		'511703'	=> [
			'zh-cn'			=> '达川区',
			'en'			=> 'da chuan qu',
		],
		'511722'	=> [
			'zh-cn'			=> '宣汉县',
			'en'			=> 'xuan han xian',
		],
		'511723'	=> [
			'zh-cn'			=> '开江县',
			'en'			=> 'kai jiang xian',
		],
		'511724'	=> [
			'zh-cn'			=> '大竹县',
			'en'			=> 'da zhu xian',
		],
		'511781'	=> [
			'zh-cn'			=> '万源市',
			'en'			=> 'wan yuan shi',
		],
		'511800'	=> [
			'zh-cn'			=> '雅安市',
			'en'			=> 'ya an shi',
		],
		'511802'	=> [
			'zh-cn'			=> '雨城区',
			'en'			=> 'yu cheng qu',
		],
		'511803'	=> [
			'zh-cn'			=> '名山区',
			'en'			=> 'ming shan qu',
		],
		'511821'	=> [
			'zh-cn'			=> '名山县',
			'en'			=> 'ming shan xian',
		],
		'511822'	=> [
			'zh-cn'			=> '荥经县',
			'en'			=> 'ying jing xian',
		],
		'511823'	=> [
			'zh-cn'			=> '汉源县',
			'en'			=> 'han yuan xian',
		],
		'511824'	=> [
			'zh-cn'			=> '石棉县',
			'en'			=> 'shi mian xian',
		],
		'511825'	=> [
			'zh-cn'			=> '天全县',
			'en'			=> 'tian quan xian',
		],
		'511826'	=> [
			'zh-cn'			=> '芦山县',
			'en'			=> 'lu shan xian',
		],
		'511827'	=> [
			'zh-cn'			=> '宝兴县',
			'en'			=> 'bao xing xian',
		],
		'511900'	=> [
			'zh-cn'			=> '巴中市',
			'en'			=> 'ba zhong shi',
		],
		'511902'	=> [
			'zh-cn'			=> '巴州区',
			'en'			=> 'ba zhou qu',
		],
		'511903'	=> [
			'zh-cn'			=> '恩阳区',
			'en'			=> 'en yang qu',
		],
		'511921'	=> [
			'zh-cn'			=> '通江县',
			'en'			=> 'tong jiang xian',
		],
		'511922'	=> [
			'zh-cn'			=> '南江县',
			'en'			=> 'nan jiang xian',
		],
		'511923'	=> [
			'zh-cn'			=> '平昌县',
			'en'			=> 'ping chang xian',
		],
		'512000'	=> [
			'zh-cn'			=> '资阳市',
			'en'			=> 'zi yang shi',
		],
		'512002'	=> [
			'zh-cn'			=> '雁江区',
			'en'			=> 'yan jiang qu',
		],
		'512021'	=> [
			'zh-cn'			=> '安岳县',
			'en'			=> 'an yue xian',
		],
		'512022'	=> [
			'zh-cn'			=> '乐至县',
			'en'			=> 'le zhi xian',
		],
		'512081'	=> [
			'zh-cn'			=> '简阳市',
			'en'			=> 'jian yang shi',
		],
		'512100'	=> [
			'zh-cn'			=> '温江地区',
			'en'			=> 'wen jiang di qu',
		],
		'512121'	=> [
			'zh-cn'			=> '广汉县',
			'en'			=> 'guang han xian',
		],
		'512122'	=> [
			'zh-cn'			=> '什邡县',
			'en'			=> 'shi fang xian',
		],
		'512123'	=> [
			'zh-cn'			=> '温江县',
			'en'			=> 'wen jiang xian',
		],
		'512125'	=> [
			'zh-cn'			=> '新都县',
			'en'			=> 'xin du xian',
		],
		'512128'	=> [
			'zh-cn'			=> '崇庆县',
			'en'			=> 'chong qing xian',
		],
		'512129'	=> [
			'zh-cn'			=> '大邑县',
			'en'			=> 'da yi xian',
		],
		'512130'	=> [
			'zh-cn'			=> '邛崃县',
			'en'			=> 'qiong lai xian',
		],
		'512131'	=> [
			'zh-cn'			=> '蒲江县',
			'en'			=> 'pu jiang xian',
		],
		'512132'	=> [
			'zh-cn'			=> '新津县',
			'en'			=> 'xin jin xian',
		],
		'512200'	=> [
			'zh-cn'			=> '万县地区',
			'en'			=> 'wan xian di qu',
		],
		'512201'	=> [
			'zh-cn'			=> '万县市',
			'en'			=> 'wan xian shi',
		],
		'512224'	=> [
			'zh-cn'			=> '梁平县',
			'en'			=> 'liang ping xian',
		],
		'512225'	=> [
			'zh-cn'			=> '云阳县',
			'en'			=> 'yun yang xian',
		],
		'512226'	=> [
			'zh-cn'			=> '奉节县',
			'en'			=> 'feng jie xian',
		],
		'512227'	=> [
			'zh-cn'			=> '巫山县',
			'en'			=> 'wu shan xian',
		],
		'512228'	=> [
			'zh-cn'			=> '巫溪县',
			'en'			=> 'wu xi xian',
		],
		'512229'	=> [
			'zh-cn'			=> '城口县',
			'en'			=> 'cheng kou xian',
		],
		'512300'	=> [
			'zh-cn'			=> '涪陵地区',
			'en'			=> 'fu ling di qu',
		],
		'512301'	=> [
			'zh-cn'			=> '涪陵市',
			'en'			=> 'fu ling shi',
		],
		'512302'	=> [
			'zh-cn'			=> '南川市',
			'en'			=> 'nan chuan shi',
		],
		'512321'	=> [
			'zh-cn'			=> '涪陵县',
			'en'			=> 'fu ling xian',
		],
		'512322'	=> [
			'zh-cn'			=> '垫江县',
			'en'			=> 'dian jiang xian',
		],
		'512323'	=> [
			'zh-cn'			=> '南川县',
			'en'			=> 'nan chuan xian',
		],
		'512324'	=> [
			'zh-cn'			=> '丰都县',
			'en'			=> 'feng du xian',
		],
		'512325'	=> [
			'zh-cn'			=> '石柱土家族自治县',
			'en'			=> 'shi zhu tu jia zu zi zhi xian',
		],
		'512326'	=> [
			'zh-cn'			=> '武隆县',
			'en'			=> 'wu long xian',
		],
		'512327'	=> [
			'zh-cn'			=> '彭水苗族土家族自治县',
			'en'			=> 'peng shui miao zu tu jia zu zi zhi xian',
		],
		'512328'	=> [
			'zh-cn'			=> '黔江土家族苗族自治县',
			'en'			=> 'qian jiang tu jia zu miao zu zi zhi xian',
		],
		'512329'	=> [
			'zh-cn'			=> '酉阳土家族苗族自治县',
			'en'			=> 'you yang tu jia zu miao zu zi zhi xian',
		],
		'512330'	=> [
			'zh-cn'			=> '秀山土家族苗族自治县',
			'en'			=> 'xiu shan tu jia zu miao zu zi zhi xian',
		],
		'512400'	=> [
			'zh-cn'			=> '内江地区',
			'en'			=> 'nei jiang di qu',
		],
		'512401'	=> [
			'zh-cn'			=> '内江市',
			'en'			=> 'nei jiang shi',
		],
		'512421'	=> [
			'zh-cn'			=> '内江县',
			'en'			=> 'nei jiang xian',
		],
		'512422'	=> [
			'zh-cn'			=> '资中县',
			'en'			=> 'zi zhong xian',
		],
		'512423'	=> [
			'zh-cn'			=> '资阳县',
			'en'			=> 'zi yang xian',
		],
		'512424'	=> [
			'zh-cn'			=> '简阳县',
			'en'			=> 'jian yang xian',
		],
		'512425'	=> [
			'zh-cn'			=> '威远县',
			'en'			=> 'wei yuan xian',
		],
		'512426'	=> [
			'zh-cn'			=> '隆昌县',
			'en'			=> 'long chang xian',
		],
		'512427'	=> [
			'zh-cn'			=> '安岳县',
			'en'			=> 'an yue xian',
		],
		'512428'	=> [
			'zh-cn'			=> '乐至县',
			'en'			=> 'le zhi xian',
		],
		'512500'	=> [
			'zh-cn'			=> '宜宾地区',
			'en'			=> 'yi bin di qu',
		],
		'512501'	=> [
			'zh-cn'			=> '宜宾市',
			'en'			=> 'yi bin shi',
		],
		'512502'	=> [
			'zh-cn'			=> '泸州市',
			'en'			=> 'lu zhou shi',
		],
		'512522'	=> [
			'zh-cn'			=> '富顺县',
			'en'			=> 'fu shun xian',
		],
		'512523'	=> [
			'zh-cn'			=> '纳溪县',
			'en'			=> 'na xi xian',
		],
		'512524'	=> [
			'zh-cn'			=> '合江县',
			'en'			=> 'he jiang xian',
		],
		'512525'	=> [
			'zh-cn'			=> '叙永县',
			'en'			=> 'xu yong xian',
		],
		'512526'	=> [
			'zh-cn'			=> '古蔺县',
			'en'			=> 'gu lin xian',
		],
		'512527'	=> [
			'zh-cn'			=> '宜宾县',
			'en'			=> 'yi bin xian',
		],
		'512528'	=> [
			'zh-cn'			=> '南溪县',
			'en'			=> 'nan xi xian',
		],
		'512529'	=> [
			'zh-cn'			=> '江安县',
			'en'			=> 'jiang an xian',
		],
		'512530'	=> [
			'zh-cn'			=> '长宁县',
			'en'			=> 'chang ning xian',
		],
		'512532'	=> [
			'zh-cn'			=> '筠连县',
			'en'			=> 'jun lian xian',
		],
		'512534'	=> [
			'zh-cn'			=> '兴文县',
			'en'			=> 'xing wen xian',
		],
		'512535'	=> [
			'zh-cn'			=> '屏山县',
			'en'			=> 'ping shan xian',
		],
		'512600'	=> [
			'zh-cn'			=> '乐山地区',
			'en'			=> 'le shan di qu',
		],
		'512601'	=> [
			'zh-cn'			=> '乐山市',
			'en'			=> 'le shan shi',
		],
		'512621'	=> [
			'zh-cn'			=> '仁寿县',
			'en'			=> 'ren shou xian',
		],
		'512622'	=> [
			'zh-cn'			=> '眉山县',
			'en'			=> 'mei shan xian',
		],
		'512623'	=> [
			'zh-cn'			=> '犍为县',
			'en'			=> 'qian wei xian',
		],
		'512624'	=> [
			'zh-cn'			=> '井研县',
			'en'			=> 'jing yan xian',
		],
		'512625'	=> [
			'zh-cn'			=> '峨眉县',
			'en'			=> 'e mei xian',
		],
		'512626'	=> [
			'zh-cn'			=> '夹江县',
			'en'			=> 'jia jiang xian',
		],
		'512627'	=> [
			'zh-cn'			=> '洪雅县',
			'en'			=> 'hong ya xian',
		],
		'512628'	=> [
			'zh-cn'			=> '彭山县',
			'en'			=> 'peng shan xian',
		],
		'512629'	=> [
			'zh-cn'			=> '沐川县',
			'en'			=> 'mu chuan xian',
		],
		'512630'	=> [
			'zh-cn'			=> '青神县',
			'en'			=> 'qing shen xian',
		],
		'512631'	=> [
			'zh-cn'			=> '丹棱县',
			'en'			=> 'dan leng xian',
		],
		'512632'	=> [
			'zh-cn'			=> '峨边彝族自治县',
			'en'			=> 'e bian yi zu zi zhi xian',
		],
		'512633'	=> [
			'zh-cn'			=> '马边彝族自治县',
			'en'			=> 'ma bian yi zu zi zhi xian',
		],
		'512634'	=> [
			'zh-cn'			=> '金口河工农区',
			'en'			=> 'jin kou he gong nong qu',
		],
		'512700'	=> [
			'zh-cn'			=> '永川地区',
			'en'			=> 'yong chuan di qu',
		],
		'512721'	=> [
			'zh-cn'			=> '江津县',
			'en'			=> 'jiang jin xian',
		],
		'512722'	=> [
			'zh-cn'			=> '合川县',
			'en'			=> 'he chuan xian',
		],
		'512723'	=> [
			'zh-cn'			=> '潼南县',
			'en'			=> 'tong nan xian',
		],
		'512724'	=> [
			'zh-cn'			=> '铜梁县',
			'en'			=> 'tong liang xian',
		],
		'512725'	=> [
			'zh-cn'			=> '永川县',
			'en'			=> 'yong chuan xian',
		],
		'512726'	=> [
			'zh-cn'			=> '大足县',
			'en'			=> 'da zu xian',
		],
		'512727'	=> [
			'zh-cn'			=> '荣昌县',
			'en'			=> 'rong chang xian',
		],
		'512728'	=> [
			'zh-cn'			=> '璧山县',
			'en'			=> 'bi shan xian',
		],
		'512800'	=> [
			'zh-cn'			=> '绵阳地区',
			'en'			=> 'mian yang di qu',
		],
		'512801'	=> [
			'zh-cn'			=> '绵阳市',
			'en'			=> 'mian yang shi',
		],
		'512802'	=> [
			'zh-cn'			=> '市中区',
			'en'			=> 'shi zhong qu',
		],
		'512821'	=> [
			'zh-cn'			=> '德阳县',
			'en'			=> 'de yang xian',
		],
		'512822'	=> [
			'zh-cn'			=> '绵竹县',
			'en'			=> 'mian zhu xian',
		],
		'512824'	=> [
			'zh-cn'			=> '江油县',
			'en'			=> 'jiang you xian',
		],
		'512825'	=> [
			'zh-cn'			=> '梓潼县',
			'en'			=> 'zi tong xian',
		],
		'512826'	=> [
			'zh-cn'			=> '剑阁县',
			'en'			=> 'jian ge xian',
		],
		'512827'	=> [
			'zh-cn'			=> '广元县',
			'en'			=> 'guang yuan xian',
		],
		'512828'	=> [
			'zh-cn'			=> '旺苍县',
			'en'			=> 'wang cang xian',
		],
		'512829'	=> [
			'zh-cn'			=> '青川县',
			'en'			=> 'qing chuan xian',
		],
		'512830'	=> [
			'zh-cn'			=> '平武县',
			'en'			=> 'ping wu xian',
		],
		'512831'	=> [
			'zh-cn'			=> '北川县',
			'en'			=> 'bei chuan xian',
		],
		'512832'	=> [
			'zh-cn'			=> '遂宁县',
			'en'			=> 'sui ning xian',
		],
		'512833'	=> [
			'zh-cn'			=> '三台县',
			'en'			=> 'san tai xian',
		],
		'512834'	=> [
			'zh-cn'			=> '中江县',
			'en'			=> 'zhong jiang xian',
		],
		'512835'	=> [
			'zh-cn'			=> '蓬溪县',
			'en'			=> 'peng xi xian',
		],
		'512836'	=> [
			'zh-cn'			=> '射洪县',
			'en'			=> 'she hong xian',
		],
		'512837'	=> [
			'zh-cn'			=> '盐亭县',
			'en'			=> 'yan ting xian',
		],
		'512900'	=> [
			'zh-cn'			=> '南充地区',
			'en'			=> 'nan chong di qu',
		],
		'512901'	=> [
			'zh-cn'			=> '南充市',
			'en'			=> 'nan chong shi',
		],
		'512902'	=> [
			'zh-cn'			=> '华蓥市',
			'en'			=> 'hua ying shi',
		],
		'512903'	=> [
			'zh-cn'			=> '阆中市',
			'en'			=> 'lang zhong shi',
		],
		'512921'	=> [
			'zh-cn'			=> '南充县',
			'en'			=> 'nan chong xian',
		],
		'512922'	=> [
			'zh-cn'			=> '南部县',
			'en'			=> 'nan bu xian',
		],
		'512923'	=> [
			'zh-cn'			=> '岳池县',
			'en'			=> 'yue chi xian',
		],
		'512924'	=> [
			'zh-cn'			=> '营山县',
			'en'			=> 'ying shan xian',
		],
		'512925'	=> [
			'zh-cn'			=> '广安县',
			'en'			=> 'guang an xian',
		],
		'512926'	=> [
			'zh-cn'			=> '蓬安县',
			'en'			=> 'peng an xian',
		],
		'512927'	=> [
			'zh-cn'			=> '仪陇县',
			'en'			=> 'yi long xian',
		],
		'512928'	=> [
			'zh-cn'			=> '武胜县',
			'en'			=> 'wu sheng xian',
		],
		'512929'	=> [
			'zh-cn'			=> '西充县',
			'en'			=> 'xi chong xian',
		],
		'512930'	=> [
			'zh-cn'			=> '阆中县',
			'en'			=> 'lang zhong xian',
		],
		'512931'	=> [
			'zh-cn'			=> '苍溪县',
			'en'			=> 'cang xi xian',
		],
		'512932'	=> [
			'zh-cn'			=> '华云工农区',
			'en'			=> 'hua yun gong nong qu',
		],
		'513000'	=> [
			'zh-cn'			=> '达川地区',
			'en'			=> 'da chuan di qu',
		],
		'513001'	=> [
			'zh-cn'			=> '达川市',
			'en'			=> 'da chuan shi',
		],
		'513002'	=> [
			'zh-cn'			=> '万源市',
			'en'			=> 'wan yuan shi',
		],
		'513022'	=> [
			'zh-cn'			=> '宣汉县',
			'en'			=> 'xuan han xian',
		],
		'513023'	=> [
			'zh-cn'			=> '开江县',
			'en'			=> 'kai jiang xian',
		],
		'513024'	=> [
			'zh-cn'			=> '万源县',
			'en'			=> 'wan yuan xian',
		],
		'513025'	=> [
			'zh-cn'			=> '通江县',
			'en'			=> 'tong jiang xian',
		],
		'513026'	=> [
			'zh-cn'			=> '南江县',
			'en'			=> 'nan jiang xian',
		],
		'513027'	=> [
			'zh-cn'			=> '巴中县',
			'en'			=> 'ba zhong xian',
		],
		'513028'	=> [
			'zh-cn'			=> '平昌县',
			'en'			=> 'ping chang xian',
		],
		'513029'	=> [
			'zh-cn'			=> '大竹县',
			'en'			=> 'da zhu xian',
		],
		'513031'	=> [
			'zh-cn'			=> '邻水县',
			'en'			=> 'lin shui xian',
		],
		'513032'	=> [
			'zh-cn'			=> '白沙工农区',
			'en'			=> 'bai sha gong nong qu',
		],
		'513100'	=> [
			'zh-cn'			=> '雅安地区',
			'en'			=> 'ya an di qu',
		],
		'513101'	=> [
			'zh-cn'			=> '雅安市',
			'en'			=> 'ya an shi',
		],
		'513121'	=> [
			'zh-cn'			=> '雅安县',
			'en'			=> 'ya an xian',
		],
		'513122'	=> [
			'zh-cn'			=> '名山县',
			'en'			=> 'ming shan xian',
		],
		'513123'	=> [
			'zh-cn'			=> '荥经县',
			'en'			=> 'ying jing xian',
		],
		'513124'	=> [
			'zh-cn'			=> '汉源县',
			'en'			=> 'han yuan xian',
		],
		'513125'	=> [
			'zh-cn'			=> '石棉县',
			'en'			=> 'shi mian xian',
		],
		'513126'	=> [
			'zh-cn'			=> '天全县',
			'en'			=> 'tian quan xian',
		],
		'513127'	=> [
			'zh-cn'			=> '芦山县',
			'en'			=> 'lu shan xian',
		],
		'513128'	=> [
			'zh-cn'			=> '宝兴县',
			'en'			=> 'bao xing xian',
		],
		'513200'	=> [
			'zh-cn'			=> '阿坝藏族羌族自治州',
			'en'			=> 'a ba zang zu qiang zu zi zhi zhou',
		],
		'513201'	=> [
			'zh-cn'			=> '马尔康市',
			'en'			=> 'ma er kang shi',
		],
		'513221'	=> [
			'zh-cn'			=> '汶川县',
			'en'			=> 'wen chuan xian',
		],
		'513223'	=> [
			'zh-cn'			=> '茂汶羌族自治县',
			'en'			=> 'mao wen qiang zu zi zhi xian',
		],
		'513224'	=> [
			'zh-cn'			=> '松潘县',
			'en'			=> 'song pan xian',
		],
		'513225'	=> [
			'zh-cn'			=> '九寨沟县',
			'en'			=> 'jiu zhai gou xian',
		],
		'513226'	=> [
			'zh-cn'			=> '金川县',
			'en'			=> 'jin chuan xian',
		],
		'513227'	=> [
			'zh-cn'			=> '小金县',
			'en'			=> 'xiao jin xian',
		],
		'513228'	=> [
			'zh-cn'			=> '黑水县',
			'en'			=> 'hei shui xian',
		],
		'513229'	=> [
			'zh-cn'			=> '马尔康县',
			'en'			=> 'ma er kang xian',
		],
		'513230'	=> [
			'zh-cn'			=> '壤塘县',
			'en'			=> 'rang tang xian',
		],
		'513231'	=> [
			'zh-cn'			=> '阿坝县',
			'en'			=> 'a ba xian',
		],
		'513232'	=> [
			'zh-cn'			=> '若尔盖县',
			'en'			=> 'ruo er gai xian',
		],
		'513233'	=> [
			'zh-cn'			=> '红原县',
			'en'			=> 'hong yuan xian',
		],
		'513300'	=> [
			'zh-cn'			=> '甘孜藏族自治州',
			'en'			=> 'gan zi zang zu zi zhi zhou',
		],
		'513301'	=> [
			'zh-cn'			=> '康定市',
			'en'			=> 'kang ding shi',
		],
		'513321'	=> [
			'zh-cn'			=> '康定县',
			'en'			=> 'kang ding xian',
		],
		'513322'	=> [
			'zh-cn'			=> '泸定县',
			'en'			=> 'lu ding xian',
		],
		'513323'	=> [
			'zh-cn'			=> '丹巴县',
			'en'			=> 'dan ba xian',
		],
		'513324'	=> [
			'zh-cn'			=> '九龙县',
			'en'			=> 'jiu long xian',
		],
		'513325'	=> [
			'zh-cn'			=> '雅江县',
			'en'			=> 'ya jiang xian',
		],
		'513326'	=> [
			'zh-cn'			=> '道孚县',
			'en'			=> 'dao fu xian',
		],
		'513327'	=> [
			'zh-cn'			=> '炉霍县',
			'en'			=> 'lu huo xian',
		],
		'513328'	=> [
			'zh-cn'			=> '甘孜县',
			'en'			=> 'gan zi xian',
		],
		'513329'	=> [
			'zh-cn'			=> '新龙县',
			'en'			=> 'xin long xian',
		],
		'513330'	=> [
			'zh-cn'			=> '德格县',
			'en'			=> 'de ge xian',
		],
		'513331'	=> [
			'zh-cn'			=> '白玉县',
			'en'			=> 'bai yu xian',
		],
		'513332'	=> [
			'zh-cn'			=> '石渠县',
			'en'			=> 'shi qu xian',
		],
		'513333'	=> [
			'zh-cn'			=> '色达县',
			'en'			=> 'shai da xian',
		],
		'513334'	=> [
			'zh-cn'			=> '理塘县',
			'en'			=> 'li tang xian',
		],
		'513335'	=> [
			'zh-cn'			=> '巴塘县',
			'en'			=> 'ba tang xian',
		],
		'513336'	=> [
			'zh-cn'			=> '乡城县',
			'en'			=> 'xiang cheng xian',
		],
		'513337'	=> [
			'zh-cn'			=> '稻城县',
			'en'			=> 'dao cheng xian',
		],
		'513338'	=> [
			'zh-cn'			=> '得荣县',
			'en'			=> 'de rong xian',
		],
		'513400'	=> [
			'zh-cn'			=> '凉山彝族自治州',
			'en'			=> 'liang shan yi zu zi zhi zhou',
		],
		'513401'	=> [
			'zh-cn'			=> '西昌市',
			'en'			=> 'xi chang shi',
		],
		'513421'	=> [
			'zh-cn'			=> '西昌县',
			'en'			=> 'xi chang xian',
		],
		'513422'	=> [
			'zh-cn'			=> '木里藏族自治县',
			'en'			=> 'mu li zang zu zi zhi xian',
		],
		'513423'	=> [
			'zh-cn'			=> '盐源县',
			'en'			=> 'yan yuan xian',
		],
		'513424'	=> [
			'zh-cn'			=> '德昌县',
			'en'			=> 'de chang xian',
		],
		'513425'	=> [
			'zh-cn'			=> '会理县',
			'en'			=> 'hui li xian',
		],
		'513426'	=> [
			'zh-cn'			=> '会东县',
			'en'			=> 'hui dong xian',
		],
		'513427'	=> [
			'zh-cn'			=> '宁南县',
			'en'			=> 'ning nan xian',
		],
		'513428'	=> [
			'zh-cn'			=> '普格县',
			'en'			=> 'pu ge xian',
		],
		'513429'	=> [
			'zh-cn'			=> '布拖县',
			'en'			=> 'bu tuo xian',
		],
		'513430'	=> [
			'zh-cn'			=> '金阳县',
			'en'			=> 'jin yang xian',
		],
		'513431'	=> [
			'zh-cn'			=> '昭觉县',
			'en'			=> 'zhao jue xian',
		],
		'513432'	=> [
			'zh-cn'			=> '喜德县',
			'en'			=> 'xi de xian',
		],
		'513433'	=> [
			'zh-cn'			=> '冕宁县',
			'en'			=> 'mian ning xian',
		],
		'513434'	=> [
			'zh-cn'			=> '越西县',
			'en'			=> 'yue xi xian',
		],
		'513435'	=> [
			'zh-cn'			=> '甘洛县',
			'en'			=> 'gan luo xian',
		],
		'513436'	=> [
			'zh-cn'			=> '美姑县',
			'en'			=> 'mei gu xian',
		],
		'513437'	=> [
			'zh-cn'			=> '雷波县',
			'en'			=> 'lei bo xian',
		],
		'513500'	=> [
			'zh-cn'			=> '黔江地区',
			'en'			=> 'qian jiang di qu',
		],
		'513521'	=> [
			'zh-cn'			=> '石柱土家族自治县',
			'en'			=> 'shi zhu tu jia zu zi zhi xian',
		],
		'513522'	=> [
			'zh-cn'			=> '秀山土家族苗族自治县',
			'en'			=> 'xiu shan tu jia zu miao zu zi zhi xian',
		],
		'513523'	=> [
			'zh-cn'			=> '黔江土家族苗族自治县',
			'en'			=> 'qian jiang tu jia zu miao zu zi zhi xian',
		],
		'513524'	=> [
			'zh-cn'			=> '酉阳土家族苗族自治县',
			'en'			=> 'you yang tu jia zu miao zu zi zhi xian',
		],
		'513525'	=> [
			'zh-cn'			=> '彭水苗族土家族自治县',
			'en'			=> 'peng shui miao zu tu jia zu zi zhi xian',
		],
		'513600'	=> [
			'zh-cn'			=> '广安地区',
			'en'			=> 'guang an di qu',
		],
		'513601'	=> [
			'zh-cn'			=> '华蓥市',
			'en'			=> 'hua ying shi',
		],
		'513621'	=> [
			'zh-cn'			=> '岳池县',
			'en'			=> 'yue chi xian',
		],
		'513622'	=> [
			'zh-cn'			=> '广安县',
			'en'			=> 'guang an xian',
		],
		'513623'	=> [
			'zh-cn'			=> '武胜县',
			'en'			=> 'wu sheng xian',
		],
		'513624'	=> [
			'zh-cn'			=> '邻水县',
			'en'			=> 'lin shui xian',
		],
		'513700'	=> [
			'zh-cn'			=> '巴中地区',
			'en'			=> 'ba zhong di qu',
		],
		'513701'	=> [
			'zh-cn'			=> '巴中市',
			'en'			=> 'ba zhong shi',
		],
		'513721'	=> [
			'zh-cn'			=> '通江县',
			'en'			=> 'tong jiang xian',
		],
		'513722'	=> [
			'zh-cn'			=> '南江县',
			'en'			=> 'nan jiang xian',
		],
		'513723'	=> [
			'zh-cn'			=> '平昌县',
			'en'			=> 'ping chang xian',
		],
		'513800'	=> [
			'zh-cn'			=> '眉山地区',
			'en'			=> 'mei shan di qu',
		],
		'513821'	=> [
			'zh-cn'			=> '眉山县',
			'en'			=> 'mei shan xian',
		],
		'513822'	=> [
			'zh-cn'			=> '仁寿县',
			'en'			=> 'ren shou xian',
		],
		'513823'	=> [
			'zh-cn'			=> '彭山县',
			'en'			=> 'peng shan xian',
		],
		'513824'	=> [
			'zh-cn'			=> '洪雅县',
			'en'			=> 'hong ya xian',
		],
		'513825'	=> [
			'zh-cn'			=> '丹棱县',
			'en'			=> 'dan leng xian',
		],
		'513826'	=> [
			'zh-cn'			=> '青神县',
			'en'			=> 'qing shen xian',
		],
		'513900'	=> [
			'zh-cn'			=> '资阳地区',
			'en'			=> 'zi yang di qu',
		],
		'513901'	=> [
			'zh-cn'			=> '资阳市',
			'en'			=> 'zi yang shi',
		],
		'513902'	=> [
			'zh-cn'			=> '简阳市',
			'en'			=> 'jian yang shi',
		],
		'513921'	=> [
			'zh-cn'			=> '安岳县',
			'en'			=> 'an yue xian',
		],
		'513922'	=> [
			'zh-cn'			=> '乐至县',
			'en'			=> 'le zhi xian',
		],
		'517000'	=> [
			'zh-cn'			=> '涪陵市',
			'en'			=> 'fu ling shi',
		],
		'517002'	=> [
			'zh-cn'			=> '枳城区',
			'en'			=> 'zhi cheng qu',
		],
		'517003'	=> [
			'zh-cn'			=> '李渡区',
			'en'			=> 'li du qu',
		],
		'517021'	=> [
			'zh-cn'			=> '垫江县',
			'en'			=> 'dian jiang xian',
		],
		'517022'	=> [
			'zh-cn'			=> '丰都县',
			'en'			=> 'feng du xian',
		],
		'517023'	=> [
			'zh-cn'			=> '武隆县',
			'en'			=> 'wu long xian',
		],
		'517081'	=> [
			'zh-cn'			=> '南川市',
			'en'			=> 'nan chuan shi',
		],
		'519001'	=> [
			'zh-cn'			=> '广汉市',
			'en'			=> 'guang han shi',
		],
		'519002'	=> [
			'zh-cn'			=> '江油市',
			'en'			=> 'jiang you shi',
		],
		'519003'	=> [
			'zh-cn'			=> '都江堰市',
			'en'			=> 'du jiang yan shi',
		],
		'519004'	=> [
			'zh-cn'			=> '峨眉山市',
			'en'			=> 'e mei shan shi',
		],
		'519005'	=> [
			'zh-cn'			=> '永川市',
			'en'			=> 'yong chuan shi',
		],
		'519006'	=> [
			'zh-cn'			=> '合川市',
			'en'			=> 'he chuan shi',
		],
		'519007'	=> [
			'zh-cn'			=> '江津市',
			'en'			=> 'jiang jin shi',
		],
		'519008'	=> [
			'zh-cn'			=> '阆中市',
			'en'			=> 'lang zhong shi',
		],
		'519009'	=> [
			'zh-cn'			=> '资阳市',
			'en'			=> 'zi yang shi',
		],
		'519010'	=> [
			'zh-cn'			=> '彭州市',
			'en'			=> 'peng zhou shi',
		],
		'519011'	=> [
			'zh-cn'			=> '简阳市',
			'en'			=> 'jian yang shi',
		],
		'519012'	=> [
			'zh-cn'			=> '邛崃市',
			'en'			=> 'qiong lai shi',
		],
		'519013'	=> [
			'zh-cn'			=> '崇州市',
			'en'			=> 'chong zhou shi',
		],
		'520000'	=> [
			'zh-cn'			=> '贵州省',
			'en'			=> 'gui zhou sheng',
		],
		'520100'	=> [
			'zh-cn'			=> '贵阳市',
			'en'			=> 'gui yang shi',
		],
		'520102'	=> [
			'zh-cn'			=> '南明区',
			'en'			=> 'nan ming qu',
		],
		'520103'	=> [
			'zh-cn'			=> '云岩区',
			'en'			=> 'yun yan qu',
		],
		'520111'	=> [
			'zh-cn'			=> '花溪区',
			'en'			=> 'hua xi qu',
		],
		'520112'	=> [
			'zh-cn'			=> '乌当区',
			'en'			=> 'wu dang qu',
		],
		'520113'	=> [
			'zh-cn'			=> '白云区',
			'en'			=> 'bai yun qu',
		],
		'520114'	=> [
			'zh-cn'			=> '小河区',
			'en'			=> 'xiao he qu',
		],
		'520115'	=> [
			'zh-cn'			=> '观山湖区',
			'en'			=> 'guan shan hu qu',
		],
		'520121'	=> [
			'zh-cn'			=> '开阳县',
			'en'			=> 'kai yang xian',
		],
		'520122'	=> [
			'zh-cn'			=> '息烽县',
			'en'			=> 'xi feng xian',
		],
		'520123'	=> [
			'zh-cn'			=> '修文县',
			'en'			=> 'xiu wen xian',
		],
		'520181'	=> [
			'zh-cn'			=> '清镇市',
			'en'			=> 'qing zhen shi',
		],
		'520200'	=> [
			'zh-cn'			=> '六盘水市',
			'en'			=> 'liu pan shui shi',
		],
		'520201'	=> [
			'zh-cn'			=> '钟山区',
			'en'			=> 'zhong shan qu',
		],
		'520202'	=> [
			'zh-cn'			=> '盘县特区',
			'en'			=> 'pan xian te qu',
		],
		'520203'	=> [
			'zh-cn'			=> '六枝特区',
			'en'			=> 'lu zhi te qu',
		],
		'520221'	=> [
			'zh-cn'			=> '水城县',
			'en'			=> 'shui cheng xian',
		],
		'520300'	=> [
			'zh-cn'			=> '遵义市',
			'en'			=> 'zun yi shi',
		],
		'520302'	=> [
			'zh-cn'			=> '红花岗区',
			'en'			=> 'hong hua gang qu',
		],
		'520303'	=> [
			'zh-cn'			=> '汇川区',
			'en'			=> 'hui chuan qu',
		],
		'520304'	=> [
			'zh-cn'			=> '播州区',
			'en'			=> 'bo zhou qu',
		],
		'520321'	=> [
			'zh-cn'			=> '遵义县',
			'en'			=> 'zun yi xian',
		],
		'520322'	=> [
			'zh-cn'			=> '桐梓县',
			'en'			=> 'tong zi xian',
		],
		'520323'	=> [
			'zh-cn'			=> '绥阳县',
			'en'			=> 'sui yang xian',
		],
		'520324'	=> [
			'zh-cn'			=> '正安县',
			'en'			=> 'zheng an xian',
		],
		'520325'	=> [
			'zh-cn'			=> '道真仡佬族苗族自治县',
			'en'			=> 'dao zhen ge lao zu miao zu zi zhi xian',
		],
		'520326'	=> [
			'zh-cn'			=> '务川仡佬族苗族自治县',
			'en'			=> 'wu chuan ge lao zu miao zu zi zhi xian',
		],
		'520327'	=> [
			'zh-cn'			=> '凤冈县',
			'en'			=> 'feng gang xian',
		],
		'520328'	=> [
			'zh-cn'			=> '湄潭县',
			'en'			=> 'mei tan xian',
		],
		'520329'	=> [
			'zh-cn'			=> '余庆县',
			'en'			=> 'yu qing xian',
		],
		'520330'	=> [
			'zh-cn'			=> '习水县',
			'en'			=> 'xi shui xian',
		],
		'520381'	=> [
			'zh-cn'			=> '赤水市',
			'en'			=> 'chi shui shi',
		],
		'520382'	=> [
			'zh-cn'			=> '仁怀市',
			'en'			=> 'ren huai shi',
		],
		'520400'	=> [
			'zh-cn'			=> '安顺市',
			'en'			=> 'an shun shi',
		],
		'520402'	=> [
			'zh-cn'			=> '西秀区',
			'en'			=> 'xi xiu qu',
		],
		'520403'	=> [
			'zh-cn'			=> '平坝区',
			'en'			=> 'ping ba qu',
		],
		'520421'	=> [
			'zh-cn'			=> '平坝县',
			'en'			=> 'ping ba xian',
		],
		'520422'	=> [
			'zh-cn'			=> '普定县',
			'en'			=> 'pu ding xian',
		],
		'520423'	=> [
			'zh-cn'			=> '镇宁布依族苗族自治县',
			'en'			=> 'zhen ning bu yi zu miao zu zi zhi xian',
		],
		'520424'	=> [
			'zh-cn'			=> '关岭布依族苗族自治县',
			'en'			=> 'guan ling bu yi zu miao zu zi zhi xian',
		],
		'520425'	=> [
			'zh-cn'			=> '紫云苗族布依族自治县',
			'en'			=> 'zi yun miao zu bu yi zu zi zhi xian',
		],
		'520500'	=> [
			'zh-cn'			=> '毕节市',
			'en'			=> 'bi jie shi',
		],
		'520502'	=> [
			'zh-cn'			=> '七星关区',
			'en'			=> 'qi xing guan qu',
		],
		'520521'	=> [
			'zh-cn'			=> '大方县',
			'en'			=> 'da fang xian',
		],
		'520522'	=> [
			'zh-cn'			=> '黔西县',
			'en'			=> 'qian xi xian',
		],
		'520523'	=> [
			'zh-cn'			=> '金沙县',
			'en'			=> 'jin sha xian',
		],
		'520524'	=> [
			'zh-cn'			=> '织金县',
			'en'			=> 'zhi jin xian',
		],
		'520525'	=> [
			'zh-cn'			=> '纳雍县',
			'en'			=> 'na yong xian',
		],
		'520526'	=> [
			'zh-cn'			=> '威宁彝族回族苗族自治县',
			'en'			=> 'wei ning yi zu hui zu miao zu zi zhi xian',
		],
		'520527'	=> [
			'zh-cn'			=> '赫章县',
			'en'			=> 'he zhang xian',
		],
		'520600'	=> [
			'zh-cn'			=> '铜仁市',
			'en'			=> 'tong ren shi',
		],
		'520602'	=> [
			'zh-cn'			=> '碧江区',
			'en'			=> 'bi jiang qu',
		],
		'520603'	=> [
			'zh-cn'			=> '万山区',
			'en'			=> 'wan shan qu',
		],
		'520621'	=> [
			'zh-cn'			=> '江口县',
			'en'			=> 'jiang kou xian',
		],
		'520622'	=> [
			'zh-cn'			=> '玉屏侗族自治县',
			'en'			=> 'yu ping dong zu zi zhi xian',
		],
		'520623'	=> [
			'zh-cn'			=> '石阡县',
			'en'			=> 'shi qian xian',
		],
		'520624'	=> [
			'zh-cn'			=> '思南县',
			'en'			=> 'si nan xian',
		],
		'520625'	=> [
			'zh-cn'			=> '印江土家族苗族自治县',
			'en'			=> 'yin jiang tu jia zu miao zu zi zhi xian',
		],
		'520626'	=> [
			'zh-cn'			=> '德江县',
			'en'			=> 'de jiang xian',
		],
		'520627'	=> [
			'zh-cn'			=> '沿河土家族自治县',
			'en'			=> 'yan he tu jia zu zi zhi xian',
		],
		'520628'	=> [
			'zh-cn'			=> '松桃苗族自治县',
			'en'			=> 'song tao miao zu zi zhi xian',
		],
		'522100'	=> [
			'zh-cn'			=> '遵义地区',
			'en'			=> 'zun yi di qu',
		],
		'522101'	=> [
			'zh-cn'			=> '遵义市',
			'en'			=> 'zun yi shi',
		],
		'522102'	=> [
			'zh-cn'			=> '赤水市',
			'en'			=> 'chi shui shi',
		],
		'522103'	=> [
			'zh-cn'			=> '仁怀市',
			'en'			=> 'ren huai shi',
		],
		'522121'	=> [
			'zh-cn'			=> '遵义县',
			'en'			=> 'zun yi xian',
		],
		'522122'	=> [
			'zh-cn'			=> '桐梓县',
			'en'			=> 'tong zi xian',
		],
		'522123'	=> [
			'zh-cn'			=> '绥阳县',
			'en'			=> 'sui yang xian',
		],
		'522124'	=> [
			'zh-cn'			=> '正安县',
			'en'			=> 'zheng an xian',
		],
		'522125'	=> [
			'zh-cn'			=> '道真仡佬族苗族自治县',
			'en'			=> 'dao zhen ge lao zu miao zu zi zhi xian',
		],
		'522126'	=> [
			'zh-cn'			=> '务川仡佬族苗族自治县',
			'en'			=> 'wu chuan ge lao zu miao zu zi zhi xian',
		],
		'522127'	=> [
			'zh-cn'			=> '凤冈县',
			'en'			=> 'feng gang xian',
		],
		'522128'	=> [
			'zh-cn'			=> '湄潭县',
			'en'			=> 'mei tan xian',
		],
		'522129'	=> [
			'zh-cn'			=> '余庆县',
			'en'			=> 'yu qing xian',
		],
		'522130'	=> [
			'zh-cn'			=> '仁怀县',
			'en'			=> 'ren huai xian',
		],
		'522131'	=> [
			'zh-cn'			=> '赤水县',
			'en'			=> 'chi shui xian',
		],
		'522132'	=> [
			'zh-cn'			=> '习水县',
			'en'			=> 'xi shui xian',
		],
		'522200'	=> [
			'zh-cn'			=> '铜仁地区',
			'en'			=> 'tong ren di qu',
		],
		'522201'	=> [
			'zh-cn'			=> '铜仁市',
			'en'			=> 'tong ren shi',
		],
		'522221'	=> [
			'zh-cn'			=> '铜仁县',
			'en'			=> 'tong ren xian',
		],
		'522222'	=> [
			'zh-cn'			=> '江口县',
			'en'			=> 'jiang kou xian',
		],
		'522223'	=> [
			'zh-cn'			=> '玉屏侗族自治县',
			'en'			=> 'yu ping dong zu zi zhi xian',
		],
		'522224'	=> [
			'zh-cn'			=> '石阡县',
			'en'			=> 'shi qian xian',
		],
		'522225'	=> [
			'zh-cn'			=> '思南县',
			'en'			=> 'si nan xian',
		],
		'522226'	=> [
			'zh-cn'			=> '印江土家族苗族自治县',
			'en'			=> 'yin jiang tu jia zu miao zu zi zhi xian',
		],
		'522227'	=> [
			'zh-cn'			=> '德江县',
			'en'			=> 'de jiang xian',
		],
		'522228'	=> [
			'zh-cn'			=> '沿河土家族自治县',
			'en'			=> 'yan he tu jia zu zi zhi xian',
		],
		'522229'	=> [
			'zh-cn'			=> '松桃苗族自治县',
			'en'			=> 'song tao miao zu zi zhi xian',
		],
		'522230'	=> [
			'zh-cn'			=> '万山特区',
			'en'			=> 'wan shan te qu',
		],
		'522300'	=> [
			'zh-cn'			=> '黔西南布依族苗族自治州',
			'en'			=> 'qian xi nan bu yi zu miao zu zi zhi zhou',
		],
		'522301'	=> [
			'zh-cn'			=> '兴义市',
			'en'			=> 'xing yi shi',
		],
		'522321'	=> [
			'zh-cn'			=> '兴义县',
			'en'			=> 'xing yi xian',
		],
		'522322'	=> [
			'zh-cn'			=> '兴仁县',
			'en'			=> 'xing ren xian',
		],
		'522323'	=> [
			'zh-cn'			=> '普安县',
			'en'			=> 'pu an xian',
		],
		'522324'	=> [
			'zh-cn'			=> '晴隆县',
			'en'			=> 'qing long xian',
		],
		'522325'	=> [
			'zh-cn'			=> '贞丰县',
			'en'			=> 'zhen feng xian',
		],
		'522326'	=> [
			'zh-cn'			=> '望谟县',
			'en'			=> 'wang mo xian',
		],
		'522327'	=> [
			'zh-cn'			=> '册亨县',
			'en'			=> 'ce heng xian',
		],
		'522328'	=> [
			'zh-cn'			=> '安龙县',
			'en'			=> 'an long xian',
		],
		'522400'	=> [
			'zh-cn'			=> '毕节地区',
			'en'			=> 'bi jie di qu',
		],
		'522401'	=> [
			'zh-cn'			=> '毕节市',
			'en'			=> 'bi jie shi',
		],
		'522421'	=> [
			'zh-cn'			=> '毕节县',
			'en'			=> 'bi jie xian',
		],
		'522422'	=> [
			'zh-cn'			=> '大方县',
			'en'			=> 'da fang xian',
		],
		'522423'	=> [
			'zh-cn'			=> '黔西县',
			'en'			=> 'qian xi xian',
		],
		'522424'	=> [
			'zh-cn'			=> '金沙县',
			'en'			=> 'jin sha xian',
		],
		'522425'	=> [
			'zh-cn'			=> '织金县',
			'en'			=> 'zhi jin xian',
		],
		'522426'	=> [
			'zh-cn'			=> '纳雍县',
			'en'			=> 'na yong xian',
		],
		'522427'	=> [
			'zh-cn'			=> '威宁彝族回族苗族自治县',
			'en'			=> 'wei ning yi zu hui zu miao zu zi zhi xian',
		],
		'522428'	=> [
			'zh-cn'			=> '赫章县',
			'en'			=> 'he zhang xian',
		],
		'522500'	=> [
			'zh-cn'			=> '安顺地区',
			'en'			=> 'an shun di qu',
		],
		'522501'	=> [
			'zh-cn'			=> '安顺市',
			'en'			=> 'an shun shi',
		],
		'522502'	=> [
			'zh-cn'			=> '清镇市',
			'en'			=> 'qing zhen shi',
		],
		'522521'	=> [
			'zh-cn'			=> '安顺县',
			'en'			=> 'an shun xian',
		],
		'522522'	=> [
			'zh-cn'			=> '开阳县',
			'en'			=> 'kai yang xian',
		],
		'522523'	=> [
			'zh-cn'			=> '息烽县',
			'en'			=> 'xi feng xian',
		],
		'522524'	=> [
			'zh-cn'			=> '修文县',
			'en'			=> 'xiu wen xian',
		],
		'522525'	=> [
			'zh-cn'			=> '清镇县',
			'en'			=> 'qing zhen xian',
		],
		'522526'	=> [
			'zh-cn'			=> '平坝县',
			'en'			=> 'ping ba xian',
		],
		'522527'	=> [
			'zh-cn'			=> '普定县',
			'en'			=> 'pu ding xian',
		],
		'522528'	=> [
			'zh-cn'			=> '关岭布依族苗族自治县',
			'en'			=> 'guan ling bu yi zu miao zu zi zhi xian',
		],
		'522529'	=> [
			'zh-cn'			=> '镇宁布依族苗族自治县',
			'en'			=> 'zhen ning bu yi zu miao zu zi zhi xian',
		],
		'522530'	=> [
			'zh-cn'			=> '紫云苗族布依族自治县',
			'en'			=> 'zi yun miao zu bu yi zu zi zhi xian',
		],
		'522600'	=> [
			'zh-cn'			=> '黔东南苗族侗族自治州',
			'en'			=> 'qian dong nan miao zu dong zu zi zhi zhou',
		],
		'522601'	=> [
			'zh-cn'			=> '凯里市',
			'en'			=> 'kai li shi',
		],
		'522621'	=> [
			'zh-cn'			=> '凯里县',
			'en'			=> 'kai li xian',
		],
		'522622'	=> [
			'zh-cn'			=> '黄平县',
			'en'			=> 'huang ping xian',
		],
		'522623'	=> [
			'zh-cn'			=> '施秉县',
			'en'			=> 'shi bing xian',
		],
		'522624'	=> [
			'zh-cn'			=> '三穗县',
			'en'			=> 'san sui xian',
		],
		'522625'	=> [
			'zh-cn'			=> '镇远县',
			'en'			=> 'zhen yuan xian',
		],
		'522626'	=> [
			'zh-cn'			=> '岑巩县',
			'en'			=> 'cen gong xian',
		],
		'522627'	=> [
			'zh-cn'			=> '天柱县',
			'en'			=> 'tian zhu xian',
		],
		'522628'	=> [
			'zh-cn'			=> '锦屏县',
			'en'			=> 'jin ping xian',
		],
		'522629'	=> [
			'zh-cn'			=> '剑河县',
			'en'			=> 'jian he xian',
		],
		'522630'	=> [
			'zh-cn'			=> '台江县',
			'en'			=> 'tai jiang xian',
		],
		'522631'	=> [
			'zh-cn'			=> '黎平县',
			'en'			=> 'li ping xian',
		],
		'522632'	=> [
			'zh-cn'			=> '榕江县',
			'en'			=> 'rong jiang xian',
		],
		'522633'	=> [
			'zh-cn'			=> '从江县',
			'en'			=> 'cong jiang xian',
		],
		'522634'	=> [
			'zh-cn'			=> '雷山县',
			'en'			=> 'lei shan xian',
		],
		'522635'	=> [
			'zh-cn'			=> '麻江县',
			'en'			=> 'ma jiang xian',
		],
		'522636'	=> [
			'zh-cn'			=> '丹寨县',
			'en'			=> 'dan zhai xian',
		],
		'522700'	=> [
			'zh-cn'			=> '黔南布依族苗族自治州',
			'en'			=> 'qian nan bu yi zu miao zu zi zhi zhou',
		],
		'522701'	=> [
			'zh-cn'			=> '都匀市',
			'en'			=> 'du yun shi',
		],
		'522702'	=> [
			'zh-cn'			=> '福泉市',
			'en'			=> 'fu quan shi',
		],
		'522721'	=> [
			'zh-cn'			=> '都匀县',
			'en'			=> 'du yun xian',
		],
		'522722'	=> [
			'zh-cn'			=> '荔波县',
			'en'			=> 'li bo xian',
		],
		'522723'	=> [
			'zh-cn'			=> '贵定县',
			'en'			=> 'gui ding xian',
		],
		'522724'	=> [
			'zh-cn'			=> '福泉县',
			'en'			=> 'fu quan xian',
		],
		'522725'	=> [
			'zh-cn'			=> '瓮安县',
			'en'			=> 'weng an xian',
		],
		'522726'	=> [
			'zh-cn'			=> '独山县',
			'en'			=> 'du shan xian',
		],
		'522727'	=> [
			'zh-cn'			=> '平塘县',
			'en'			=> 'ping tang xian',
		],
		'522728'	=> [
			'zh-cn'			=> '罗甸县',
			'en'			=> 'luo dian xian',
		],
		'522729'	=> [
			'zh-cn'			=> '长顺县',
			'en'			=> 'chang shun xian',
		],
		'522730'	=> [
			'zh-cn'			=> '龙里县',
			'en'			=> 'long li xian',
		],
		'522731'	=> [
			'zh-cn'			=> '惠水县',
			'en'			=> 'hui shui xian',
		],
		'522732'	=> [
			'zh-cn'			=> '三都水族自治县',
			'en'			=> 'san dou shui zu zi zhi xian',
		],
		'530000'	=> [
			'zh-cn'			=> '云南省',
			'en'			=> 'yun nan sheng',
		],
		'530100'	=> [
			'zh-cn'			=> '昆明市',
			'en'			=> 'kun ming shi',
		],
		'530102'	=> [
			'zh-cn'			=> '五华区',
			'en'			=> 'wu hua qu',
		],
		'530103'	=> [
			'zh-cn'			=> '盘龙区',
			'en'			=> 'pan long qu',
		],
		'530111'	=> [
			'zh-cn'			=> '官渡区',
			'en'			=> 'guan du qu',
		],
		'530112'	=> [
			'zh-cn'			=> '西山区',
			'en'			=> 'xi shan qu',
		],
		'530113'	=> [
			'zh-cn'			=> '东川区',
			'en'			=> 'dong chuan qu',
		],
		'530114'	=> [
			'zh-cn'			=> '呈贡区',
			'en'			=> 'cheng gong qu',
		],
		'530115'	=> [
			'zh-cn'			=> '晋宁区',
			'en'			=> 'jin ning qu',
		],
		'530121'	=> [
			'zh-cn'			=> '呈贡县',
			'en'			=> 'cheng gong xian',
		],
		'530122'	=> [
			'zh-cn'			=> '晋宁县',
			'en'			=> 'jin ning xian',
		],
		'530123'	=> [
			'zh-cn'			=> '安宁县',
			'en'			=> 'an ning xian',
		],
		'530124'	=> [
			'zh-cn'			=> '富民县',
			'en'			=> 'fu min xian',
		],
		'530125'	=> [
			'zh-cn'			=> '宜良县',
			'en'			=> 'yi liang xian',
		],
		'530126'	=> [
			'zh-cn'			=> '石林彝族自治县',
			'en'			=> 'shi lin yi zu zi zhi xian',
		],
		'530127'	=> [
			'zh-cn'			=> '嵩明县',
			'en'			=> 'song ming xian',
		],
		'530128'	=> [
			'zh-cn'			=> '禄劝彝族苗族自治县',
			'en'			=> 'lu quan yi zu miao zu zi zhi xian',
		],
		'530129'	=> [
			'zh-cn'			=> '寻甸回族彝族自治县',
			'en'			=> 'xun dian hui zu yi zu zi zhi xian',
		],
		'530181'	=> [
			'zh-cn'			=> '安宁市',
			'en'			=> 'an ning shi',
		],
		'530200'	=> [
			'zh-cn'			=> '东川市',
			'en'			=> 'dong chuan shi',
		],
		'530300'	=> [
			'zh-cn'			=> '曲靖市',
			'en'			=> 'qu jing shi',
		],
		'530302'	=> [
			'zh-cn'			=> '麒麟区',
			'en'			=> 'qi lin qu',
		],
		'530303'	=> [
			'zh-cn'			=> '沾益区',
			'en'			=> 'zhan yi qu',
		],
		'530321'	=> [
			'zh-cn'			=> '马龙县',
			'en'			=> 'ma long xian',
		],
		'530322'	=> [
			'zh-cn'			=> '陆良县',
			'en'			=> 'lu liang xian',
		],
		'530323'	=> [
			'zh-cn'			=> '师宗县',
			'en'			=> 'shi zong xian',
		],
		'530324'	=> [
			'zh-cn'			=> '罗平县',
			'en'			=> 'luo ping xian',
		],
		'530325'	=> [
			'zh-cn'			=> '富源县',
			'en'			=> 'fu yuan xian',
		],
		'530326'	=> [
			'zh-cn'			=> '会泽县',
			'en'			=> 'hui ze xian',
		],
		'530327'	=> [
			'zh-cn'			=> '寻甸回族彝族自治县',
			'en'			=> 'xun dian hui zu yi zu zi zhi xian',
		],
		'530328'	=> [
			'zh-cn'			=> '沾益县',
			'en'			=> 'zhan yi xian',
		],
		'530381'	=> [
			'zh-cn'			=> '宣威市',
			'en'			=> 'xuan wei shi',
		],
		'530400'	=> [
			'zh-cn'			=> '玉溪市',
			'en'			=> 'yu xi shi',
		],
		'530402'	=> [
			'zh-cn'			=> '红塔区',
			'en'			=> 'hong ta qu',
		],
		'530403'	=> [
			'zh-cn'			=> '江川区',
			'en'			=> 'jiang chuan qu',
		],
		'530421'	=> [
			'zh-cn'			=> '江川县',
			'en'			=> 'jiang chuan xian',
		],
		'530422'	=> [
			'zh-cn'			=> '澄江县',
			'en'			=> 'cheng jiang xian',
		],
		'530423'	=> [
			'zh-cn'			=> '通海县',
			'en'			=> 'tong hai xian',
		],
		'530424'	=> [
			'zh-cn'			=> '华宁县',
			'en'			=> 'hua ning xian',
		],
		'530425'	=> [
			'zh-cn'			=> '易门县',
			'en'			=> 'yi men xian',
		],
		'530426'	=> [
			'zh-cn'			=> '峨山彝族自治县',
			'en'			=> 'e shan yi zu zi zhi xian',
		],
		'530427'	=> [
			'zh-cn'			=> '新平彝族傣族自治县',
			'en'			=> 'xin ping yi zu dai zu zi zhi xian',
		],
		'530428'	=> [
			'zh-cn'			=> '元江哈尼族彝族傣族自治县',
			'en'			=> 'yuan jiang ha ni zu yi zu dai zu zi zhi xian',
		],
		'530500'	=> [
			'zh-cn'			=> '保山市',
			'en'			=> 'bao shan shi',
		],
		'530502'	=> [
			'zh-cn'			=> '隆阳区',
			'en'			=> 'long yang qu',
		],
		'530521'	=> [
			'zh-cn'			=> '施甸县',
			'en'			=> 'shi dian xian',
		],
		'530522'	=> [
			'zh-cn'			=> '腾冲县',
			'en'			=> 'teng chong xian',
		],
		'530523'	=> [
			'zh-cn'			=> '龙陵县',
			'en'			=> 'long ling xian',
		],
		'530524'	=> [
			'zh-cn'			=> '昌宁县',
			'en'			=> 'chang ning xian',
		],
		'530581'	=> [
			'zh-cn'			=> '腾冲市',
			'en'			=> 'teng chong shi',
		],
		'530600'	=> [
			'zh-cn'			=> '昭通市',
			'en'			=> 'zhao tong shi',
		],
		'530602'	=> [
			'zh-cn'			=> '昭阳区',
			'en'			=> 'zhao yang qu',
		],
		'530621'	=> [
			'zh-cn'			=> '鲁甸县',
			'en'			=> 'lu dian xian',
		],
		'530622'	=> [
			'zh-cn'			=> '巧家县',
			'en'			=> 'qiao jia xian',
		],
		'530623'	=> [
			'zh-cn'			=> '盐津县',
			'en'			=> 'yan jin xian',
		],
		'530624'	=> [
			'zh-cn'			=> '大关县',
			'en'			=> 'da guan xian',
		],
		'530625'	=> [
			'zh-cn'			=> '永善县',
			'en'			=> 'yong shan xian',
		],
		'530626'	=> [
			'zh-cn'			=> '绥江县',
			'en'			=> 'sui jiang xian',
		],
		'530627'	=> [
			'zh-cn'			=> '镇雄县',
			'en'			=> 'zhen xiong xian',
		],
		'530628'	=> [
			'zh-cn'			=> '彝良县',
			'en'			=> 'yi liang xian',
		],
		'530629'	=> [
			'zh-cn'			=> '威信县',
			'en'			=> 'wei xin xian',
		],
		'530630'	=> [
			'zh-cn'			=> '水富县',
			'en'			=> 'shui fu xian',
		],
		'530700'	=> [
			'zh-cn'			=> '丽江市',
			'en'			=> 'li jiang shi',
		],
		'530702'	=> [
			'zh-cn'			=> '古城区',
			'en'			=> 'gu cheng qu',
		],
		'530721'	=> [
			'zh-cn'			=> '玉龙纳西族自治县',
			'en'			=> 'yu long na xi zu zi zhi xian',
		],
		'530722'	=> [
			'zh-cn'			=> '永胜县',
			'en'			=> 'yong sheng xian',
		],
		'530723'	=> [
			'zh-cn'			=> '华坪县',
			'en'			=> 'hua ping xian',
		],
		'530724'	=> [
			'zh-cn'			=> '宁蒗彝族自治县',
			'en'			=> 'ning lang yi zu zi zhi xian',
		],
		'530800'	=> [
			'zh-cn'			=> '普洱市',
			'en'			=> 'pu er shi',
		],
		'530802'	=> [
			'zh-cn'			=> '思茅区',
			'en'			=> 'si mao qu',
		],
		'530821'	=> [
			'zh-cn'			=> '宁洱哈尼族彝族自治县',
			'en'			=> 'ning er ha ni zu yi zu zi zhi xian',
		],
		'530822'	=> [
			'zh-cn'			=> '墨江哈尼族自治县',
			'en'			=> 'mo jiang ha ni zu zi zhi xian',
		],
		'530823'	=> [
			'zh-cn'			=> '景东彝族自治县',
			'en'			=> 'jing dong yi zu zi zhi xian',
		],
		'530824'	=> [
			'zh-cn'			=> '景谷傣族彝族自治县',
			'en'			=> 'jing gu dai zu yi zu zi zhi xian',
		],
		'530825'	=> [
			'zh-cn'			=> '镇沅彝族哈尼族拉祜族自治县',
			'en'			=> 'zhen yuan yi zu ha ni zu la hu zu zi zhi xian',
		],
		'530826'	=> [
			'zh-cn'			=> '江城哈尼族彝族自治县',
			'en'			=> 'jiang cheng ha ni zu yi zu zi zhi xian',
		],
		'530827'	=> [
			'zh-cn'			=> '孟连傣族拉祜族佤族自治县',
			'en'			=> 'meng lian dai zu la hu zu wa zu zi zhi xian',
		],
		'530828'	=> [
			'zh-cn'			=> '澜沧拉祜族自治县',
			'en'			=> 'lan cang la hu zu zi zhi xian',
		],
		'530829'	=> [
			'zh-cn'			=> '西盟佤族自治县',
			'en'			=> 'xi meng wa zu zi zhi xian',
		],
		'530900'	=> [
			'zh-cn'			=> '临沧市',
			'en'			=> 'lin cang shi',
		],
		'530902'	=> [
			'zh-cn'			=> '临翔区',
			'en'			=> 'lin xiang qu',
		],
		'530921'	=> [
			'zh-cn'			=> '凤庆县',
			'en'			=> 'feng qing xian',
		],
		'530923'	=> [
			'zh-cn'			=> '永德县',
			'en'			=> 'yong de xian',
		],
		'530924'	=> [
			'zh-cn'			=> '镇康县',
			'en'			=> 'zhen kang xian',
		],
		'530925'	=> [
			'zh-cn'			=> '双江拉祜族佤族布朗族傣族自治县',
			'en'			=> 'shuang jiang la hu zu wa zu bu lang zu dai zu zi zhi xian',
		],
		'530926'	=> [
			'zh-cn'			=> '耿马傣族佤族自治县',
			'en'			=> 'geng ma dai zu wa zu zi zhi xian',
		],
		'530927'	=> [
			'zh-cn'			=> '沧源佤族自治县',
			'en'			=> 'cang yuan wa zu zi zhi xian',
		],
		'532100'	=> [
			'zh-cn'			=> '昭通地区',
			'en'			=> 'zhao tong di qu',
		],
		'532101'	=> [
			'zh-cn'			=> '邵通市',
			'en'			=> 'shao tong shi',
		],
		'532121'	=> [
			'zh-cn'			=> '邵通县',
			'en'			=> 'shao tong xian',
		],
		'532122'	=> [
			'zh-cn'			=> '鲁甸县',
			'en'			=> 'lu dian xian',
		],
		'532123'	=> [
			'zh-cn'			=> '巧家县',
			'en'			=> 'qiao jia xian',
		],
		'532124'	=> [
			'zh-cn'			=> '盐津县',
			'en'			=> 'yan jin xian',
		],
		'532125'	=> [
			'zh-cn'			=> '大关县',
			'en'			=> 'da guan xian',
		],
		'532126'	=> [
			'zh-cn'			=> '永善县',
			'en'			=> 'yong shan xian',
		],
		'532127'	=> [
			'zh-cn'			=> '绥江县',
			'en'			=> 'sui jiang xian',
		],
		'532128'	=> [
			'zh-cn'			=> '镇雄县',
			'en'			=> 'zhen xiong xian',
		],
		'532129'	=> [
			'zh-cn'			=> '彝良县',
			'en'			=> 'yi liang xian',
		],
		'532130'	=> [
			'zh-cn'			=> '威信县',
			'en'			=> 'wei xin xian',
		],
		'532131'	=> [
			'zh-cn'			=> '水富县',
			'en'			=> 'shui fu xian',
		],
		'532200'	=> [
			'zh-cn'			=> '曲靖地区',
			'en'			=> 'qu jing di qu',
		],
		'532201'	=> [
			'zh-cn'			=> '曲靖市',
			'en'			=> 'qu jing shi',
		],
		'532202'	=> [
			'zh-cn'			=> '宣威市',
			'en'			=> 'xuan wei shi',
		],
		'532221'	=> [
			'zh-cn'			=> '曲靖县',
			'en'			=> 'qu jing xian',
		],
		'532222'	=> [
			'zh-cn'			=> '宜良县',
			'en'			=> 'yi liang xian',
		],
		'532223'	=> [
			'zh-cn'			=> '马龙县',
			'en'			=> 'ma long xian',
		],
		'532224'	=> [
			'zh-cn'			=> '宣威县',
			'en'			=> 'xuan wei xian',
		],
		'532225'	=> [
			'zh-cn'			=> '富源县',
			'en'			=> 'fu yuan xian',
		],
		'532226'	=> [
			'zh-cn'			=> '罗平县',
			'en'			=> 'luo ping xian',
		],
		'532227'	=> [
			'zh-cn'			=> '师宗县',
			'en'			=> 'shi zong xian',
		],
		'532228'	=> [
			'zh-cn'			=> '陆良县',
			'en'			=> 'lu liang xian',
		],
		'532229'	=> [
			'zh-cn'			=> '路南彝族自治县',
			'en'			=> 'lu nan yi zu zi zhi xian',
		],
		'532230'	=> [
			'zh-cn'			=> '嵩明县',
			'en'			=> 'song ming xian',
		],
		'532231'	=> [
			'zh-cn'			=> '寻甸回族彝族自治县',
			'en'			=> 'xun dian hui zu yi zu zi zhi xian',
		],
		'532232'	=> [
			'zh-cn'			=> '沾益县',
			'en'			=> 'zhan yi xian',
		],
		'532233'	=> [
			'zh-cn'			=> '会泽县',
			'en'			=> 'hui ze xian',
		],
		'532300'	=> [
			'zh-cn'			=> '楚雄彝族自治州',
			'en'			=> 'chu xiong yi zu zi zhi zhou',
		],
		'532301'	=> [
			'zh-cn'			=> '楚雄市',
			'en'			=> 'chu xiong shi',
		],
		'532321'	=> [
			'zh-cn'			=> '楚雄县',
			'en'			=> 'chu xiong xian',
		],
		'532322'	=> [
			'zh-cn'			=> '双柏县',
			'en'			=> 'shuang bai xian',
		],
		'532323'	=> [
			'zh-cn'			=> '牟定县',
			'en'			=> 'mou ding xian',
		],
		'532324'	=> [
			'zh-cn'			=> '南华县',
			'en'			=> 'nan hua xian',
		],
		'532325'	=> [
			'zh-cn'			=> '姚安县',
			'en'			=> 'yao an xian',
		],
		'532326'	=> [
			'zh-cn'			=> '大姚县',
			'en'			=> 'da yao xian',
		],
		'532327'	=> [
			'zh-cn'			=> '永仁县',
			'en'			=> 'yong ren xian',
		],
		'532328'	=> [
			'zh-cn'			=> '元谋县',
			'en'			=> 'yuan mou xian',
		],
		'532329'	=> [
			'zh-cn'			=> '武定县',
			'en'			=> 'wu ding xian',
		],
		'532330'	=> [
			'zh-cn'			=> '禄劝县',
			'en'			=> 'lu quan xian',
		],
		'532331'	=> [
			'zh-cn'			=> '禄丰县',
			'en'			=> 'lu feng xian',
		],
		'532400'	=> [
			'zh-cn'			=> '玉溪地区',
			'en'			=> 'yu xi di qu',
		],
		'532401'	=> [
			'zh-cn'			=> '玉溪市',
			'en'			=> 'yu xi shi',
		],
		'532421'	=> [
			'zh-cn'			=> '玉溪县',
			'en'			=> 'yu xi xian',
		],
		'532422'	=> [
			'zh-cn'			=> '江川县',
			'en'			=> 'jiang chuan xian',
		],
		'532423'	=> [
			'zh-cn'			=> '澄江县',
			'en'			=> 'cheng jiang xian',
		],
		'532424'	=> [
			'zh-cn'			=> '通海县',
			'en'			=> 'tong hai xian',
		],
		'532425'	=> [
			'zh-cn'			=> '华宁县',
			'en'			=> 'hua ning xian',
		],
		'532426'	=> [
			'zh-cn'			=> '易门县',
			'en'			=> 'yi men xian',
		],
		'532427'	=> [
			'zh-cn'			=> '峨山彝族自治县',
			'en'			=> 'e shan yi zu zi zhi xian',
		],
		'532428'	=> [
			'zh-cn'			=> '新平彝族傣族自治县',
			'en'			=> 'xin ping yi zu dai zu zi zhi xian',
		],
		'532429'	=> [
			'zh-cn'			=> '元江哈尼族彝族傣族自治县',
			'en'			=> 'yuan jiang ha ni zu yi zu dai zu zi zhi xian',
		],
		'532500'	=> [
			'zh-cn'			=> '红河哈尼族彝族自治州',
			'en'			=> 'hong he ha ni zu yi zu zi zhi zhou',
		],
		'532501'	=> [
			'zh-cn'			=> '个旧市',
			'en'			=> 'ge jiu shi',
		],
		'532502'	=> [
			'zh-cn'			=> '开远市',
			'en'			=> 'kai yuan shi',
		],
		'532503'	=> [
			'zh-cn'			=> '蒙自市',
			'en'			=> 'meng zi shi',
		],
		'532504'	=> [
			'zh-cn'			=> '弥勒市',
			'en'			=> 'mi le shi',
		],
		'532522'	=> [
			'zh-cn'			=> '蒙自县',
			'en'			=> 'meng zi xian',
		],
		'532523'	=> [
			'zh-cn'			=> '屏边苗族自治县',
			'en'			=> 'ping bian miao zu zi zhi xian',
		],
		'532524'	=> [
			'zh-cn'			=> '建水县',
			'en'			=> 'jian shui xian',
		],
		'532525'	=> [
			'zh-cn'			=> '石屏县',
			'en'			=> 'shi ping xian',
		],
		'532526'	=> [
			'zh-cn'			=> '弥勒县',
			'en'			=> 'mi le xian',
		],
		'532527'	=> [
			'zh-cn'			=> '泸西县',
			'en'			=> 'lu xi xian',
		],
		'532528'	=> [
			'zh-cn'			=> '元阳县',
			'en'			=> 'yuan yang xian',
		],
		'532529'	=> [
			'zh-cn'			=> '红河县',
			'en'			=> 'hong he xian',
		],
		'532530'	=> [
			'zh-cn'			=> '金平苗族瑶族傣族自治县',
			'en'			=> 'jin ping miao zu yao zu dai zu zi zhi xian',
		],
		'532531'	=> [
			'zh-cn'			=> '绿春县',
			'en'			=> 'lv chun xian',
		],
		'532532'	=> [
			'zh-cn'			=> '河口瑶族自治县',
			'en'			=> 'he kou yao zu zi zhi xian',
		],
		'532600'	=> [
			'zh-cn'			=> '文山壮族苗族自治州',
			'en'			=> 'wen shan zhuang zu miao zu zi zhi zhou',
		],
		'532601'	=> [
			'zh-cn'			=> '文山市',
			'en'			=> 'wen shan shi',
		],
		'532621'	=> [
			'zh-cn'			=> '文山县',
			'en'			=> 'wen shan xian',
		],
		'532622'	=> [
			'zh-cn'			=> '砚山县',
			'en'			=> 'yan shan xian',
		],
		'532623'	=> [
			'zh-cn'			=> '西畴县',
			'en'			=> 'xi chou xian',
		],
		'532624'	=> [
			'zh-cn'			=> '麻栗坡县',
			'en'			=> 'ma li po xian',
		],
		'532625'	=> [
			'zh-cn'			=> '马关县',
			'en'			=> 'ma guan xian',
		],
		'532626'	=> [
			'zh-cn'			=> '丘北县',
			'en'			=> 'qiu bei xian',
		],
		'532627'	=> [
			'zh-cn'			=> '广南县',
			'en'			=> 'guang nan xian',
		],
		'532628'	=> [
			'zh-cn'			=> '富宁县',
			'en'			=> 'fu ning xian',
		],
		'532700'	=> [
			'zh-cn'			=> '思茅地区',
			'en'			=> 'si mao di qu',
		],
		'532701'	=> [
			'zh-cn'			=> '思茅市',
			'en'			=> 'si mao shi',
		],
		'532721'	=> [
			'zh-cn'			=> '思茅县',
			'en'			=> 'si mao xian',
		],
		'532722'	=> [
			'zh-cn'			=> '普洱哈尼族彝族自治县',
			'en'			=> 'pu er ha ni zu yi zu zi zhi xian',
		],
		'532723'	=> [
			'zh-cn'			=> '墨江哈尼族自治县',
			'en'			=> 'mo jiang ha ni zu zi zhi xian',
		],
		'532724'	=> [
			'zh-cn'			=> '景东彝族自治县',
			'en'			=> 'jing dong yi zu zi zhi xian',
		],
		'532725'	=> [
			'zh-cn'			=> '景谷傣族彝族自治县',
			'en'			=> 'jing gu dai zu yi zu zi zhi xian',
		],
		'532726'	=> [
			'zh-cn'			=> '镇沅彝族哈尼族拉祜族自治县',
			'en'			=> 'zhen yuan yi zu ha ni zu la hu zu zi zhi xian',
		],
		'532727'	=> [
			'zh-cn'			=> '江城哈尼族彝族自治县',
			'en'			=> 'jiang cheng ha ni zu yi zu zi zhi xian',
		],
		'532728'	=> [
			'zh-cn'			=> '孟连傣族拉祜族佤族自治县',
			'en'			=> 'meng lian dai zu la hu zu wa zu zi zhi xian',
		],
		'532729'	=> [
			'zh-cn'			=> '澜沧拉祜族自治县',
			'en'			=> 'lan cang la hu zu zi zhi xian',
		],
		'532730'	=> [
			'zh-cn'			=> '西盟佤族自治县',
			'en'			=> 'xi meng wa zu zi zhi xian',
		],
		'532800'	=> [
			'zh-cn'			=> '西双版纳傣族自治州',
			'en'			=> 'xi shuang ban na dai zu zi zhi zhou',
		],
		'532801'	=> [
			'zh-cn'			=> '景洪市',
			'en'			=> 'jing hong shi',
		],
		'532821'	=> [
			'zh-cn'			=> '景洪县',
			'en'			=> 'jing hong xian',
		],
		'532822'	=> [
			'zh-cn'			=> '勐海县',
			'en'			=> 'meng hai xian',
		],
		'532823'	=> [
			'zh-cn'			=> '勐腊县',
			'en'			=> 'meng la xian',
		],
		'532900'	=> [
			'zh-cn'			=> '大理白族自治州',
			'en'			=> 'da li bai zu zi zhi zhou',
		],
		'532901'	=> [
			'zh-cn'			=> '大理市',
			'en'			=> 'da li shi',
		],
		'532921'	=> [
			'zh-cn'			=> '大理县',
			'en'			=> 'da li xian',
		],
		'532922'	=> [
			'zh-cn'			=> '漾濞彝族自治县',
			'en'			=> 'yang bi yi zu zi zhi xian',
		],
		'532923'	=> [
			'zh-cn'			=> '祥云县',
			'en'			=> 'xiang yun xian',
		],
		'532924'	=> [
			'zh-cn'			=> '宾川县',
			'en'			=> 'bin chuan xian',
		],
		'532925'	=> [
			'zh-cn'			=> '弥渡县',
			'en'			=> 'mi du xian',
		],
		'532926'	=> [
			'zh-cn'			=> '南涧彝族自治县',
			'en'			=> 'nan jian yi zu zi zhi xian',
		],
		'532927'	=> [
			'zh-cn'			=> '巍山彝族回族自治县',
			'en'			=> 'wei shan yi zu hui zu zi zhi xian',
		],
		'532928'	=> [
			'zh-cn'			=> '永平县',
			'en'			=> 'yong ping xian',
		],
		'532929'	=> [
			'zh-cn'			=> '云龙县',
			'en'			=> 'yun long xian',
		],
		'532930'	=> [
			'zh-cn'			=> '洱源县',
			'en'			=> 'er yuan xian',
		],
		'532931'	=> [
			'zh-cn'			=> '剑川县',
			'en'			=> 'jian chuan xian',
		],
		'532932'	=> [
			'zh-cn'			=> '鹤庆县',
			'en'			=> 'he qing xian',
		],
		'533000'	=> [
			'zh-cn'			=> '保山地区',
			'en'			=> 'bao shan di qu',
		],
		'533001'	=> [
			'zh-cn'			=> '保山市',
			'en'			=> 'bao shan shi',
		],
		'533021'	=> [
			'zh-cn'			=> '保山县',
			'en'			=> 'bao shan xian',
		],
		'533022'	=> [
			'zh-cn'			=> '施甸县',
			'en'			=> 'shi dian xian',
		],
		'533023'	=> [
			'zh-cn'			=> '腾冲县',
			'en'			=> 'teng chong xian',
		],
		'533024'	=> [
			'zh-cn'			=> '龙陵县',
			'en'			=> 'long ling xian',
		],
		'533025'	=> [
			'zh-cn'			=> '昌宁县',
			'en'			=> 'chang ning xian',
		],
		'533100'	=> [
			'zh-cn'			=> '德宏傣族景颇族自治州',
			'en'			=> 'de hong dai zu jing po zu zi zhi zhou',
		],
		'533101'	=> [
			'zh-cn'			=> '畹町市',
			'en'			=> 'wan ding shi',
		],
		'533102'	=> [
			'zh-cn'			=> '瑞丽市',
			'en'			=> 'rui li shi',
		],
		'533103'	=> [
			'zh-cn'			=> '潞西市',
			'en'			=> 'lu xi shi',
		],
		'533121'	=> [
			'zh-cn'			=> '潞西县',
			'en'			=> 'lu xi xian',
		],
		'533122'	=> [
			'zh-cn'			=> '梁河县',
			'en'			=> 'liang he xian',
		],
		'533123'	=> [
			'zh-cn'			=> '盈江县',
			'en'			=> 'ying jiang xian',
		],
		'533124'	=> [
			'zh-cn'			=> '陇川县',
			'en'			=> 'long chuan xian',
		],
		'533125'	=> [
			'zh-cn'			=> '瑞丽县',
			'en'			=> 'rui li xian',
		],
		'533126'	=> [
			'zh-cn'			=> '畹町镇',
			'en'			=> 'wan ding zhen',
		],
		'533200'	=> [
			'zh-cn'			=> '丽江地区',
			'en'			=> 'li jiang di qu',
		],
		'533221'	=> [
			'zh-cn'			=> '丽江纳西族自治县',
			'en'			=> 'li jiang na xi zu zi zhi xian',
		],
		'533222'	=> [
			'zh-cn'			=> '永胜县',
			'en'			=> 'yong sheng xian',
		],
		'533223'	=> [
			'zh-cn'			=> '华坪县',
			'en'			=> 'hua ping xian',
		],
		'533224'	=> [
			'zh-cn'			=> '宁蒗彝族自治县',
			'en'			=> 'ning lang yi zu zi zhi xian',
		],
		'533300'	=> [
			'zh-cn'			=> '怒江傈僳族自治州',
			'en'			=> 'nu jiang li su zu zi zhi zhou',
		],
		'533301'	=> [
			'zh-cn'			=> '泸水市',
			'en'			=> 'lu shui shi',
		],
		'533321'	=> [
			'zh-cn'			=> '泸水县',
			'en'			=> 'lu shui xian',
		],
		'533322'	=> [
			'zh-cn'			=> '碧江县',
			'en'			=> 'bi jiang xian',
		],
		'533323'	=> [
			'zh-cn'			=> '福贡县',
			'en'			=> 'fu gong xian',
		],
		'533324'	=> [
			'zh-cn'			=> '贡山独龙族怒族自治县',
			'en'			=> 'gong shan du long zu nu zu zi zhi xian',
		],
		'533325'	=> [
			'zh-cn'			=> '兰坪白族普米族自治县',
			'en'			=> 'lan ping bai zu pu mi zu zi zhi xian',
		],
		'533400'	=> [
			'zh-cn'			=> '迪庆藏族自治州',
			'en'			=> 'di qing zang zu zi zhi zhou',
		],
		'533401'	=> [
			'zh-cn'			=> '香格里拉市',
			'en'			=> 'xiang ge li la shi',
		],
		'533421'	=> [
			'zh-cn'			=> '香格里拉县',
			'en'			=> 'xiang ge li la xian',
		],
		'533422'	=> [
			'zh-cn'			=> '德钦县',
			'en'			=> 'de qin xian',
		],
		'533423'	=> [
			'zh-cn'			=> '维西傈僳族自治县',
			'en'			=> 'wei xi li su zu zi zhi xian',
		],
		'533500'	=> [
			'zh-cn'			=> '临沧地区',
			'en'			=> 'lin cang di qu',
		],
		'533521'	=> [
			'zh-cn'			=> '临沧县',
			'en'			=> 'lin cang xian',
		],
		'533522'	=> [
			'zh-cn'			=> '凤庆县',
			'en'			=> 'feng qing xian',
		],
		'533524'	=> [
			'zh-cn'			=> '永德县',
			'en'			=> 'yong de xian',
		],
		'533525'	=> [
			'zh-cn'			=> '镇康县',
			'en'			=> 'zhen kang xian',
		],
		'533526'	=> [
			'zh-cn'			=> '双江拉祜族佤族布朗族傣族自治县',
			'en'			=> 'shuang jiang la hu zu wa zu bu lang zu dai zu zi zhi xian',
		],
		'533527'	=> [
			'zh-cn'			=> '耿马傣族佤族自治县',
			'en'			=> 'geng ma dai zu wa zu zi zhi xian',
		],
		'533528'	=> [
			'zh-cn'			=> '沧源佤族自治县',
			'en'			=> 'cang yuan wa zu zi zhi xian',
		],
		'540000'	=> [
			'zh-cn'			=> '西藏自治区',
			'en'			=> 'xi zang zi zhi qu',
		],
		'540100'	=> [
			'zh-cn'			=> '拉萨市',
			'en'			=> 'la sa shi',
		],
		'540102'	=> [
			'zh-cn'			=> '城关区',
			'en'			=> 'cheng guan qu',
		],
		'540103'	=> [
			'zh-cn'			=> '堆龙德庆区',
			'en'			=> 'dui long de qing qu',
		],
		'540121'	=> [
			'zh-cn'			=> '林周县',
			'en'			=> 'lin zhou xian',
		],
		'540122'	=> [
			'zh-cn'			=> '当雄县',
			'en'			=> 'dang xiong xian',
		],
		'540123'	=> [
			'zh-cn'			=> '尼木县',
			'en'			=> 'ni mu xian',
		],
		'540124'	=> [
			'zh-cn'			=> '曲水县',
			'en'			=> 'qu shui xian',
		],
		'540125'	=> [
			'zh-cn'			=> '堆龙德庆县',
			'en'			=> 'dui long de qing xian',
		],
		'540126'	=> [
			'zh-cn'			=> '达孜县',
			'en'			=> 'da zi xian',
		],
		'540127'	=> [
			'zh-cn'			=> '墨竹工卡县',
			'en'			=> 'mo zhu gong ka xian',
		],
		'540128'	=> [
			'zh-cn'			=> '林芝县',
			'en'			=> 'lin zhi xian',
		],
		'540129'	=> [
			'zh-cn'			=> '工布江达县',
			'en'			=> 'gong bu jiang da xian',
		],
		'540130'	=> [
			'zh-cn'			=> '米林县',
			'en'			=> 'mi lin xian',
		],
		'540131'	=> [
			'zh-cn'			=> '墨脱县',
			'en'			=> 'mo tuo xian',
		],
		'540200'	=> [
			'zh-cn'			=> '日喀则市',
			'en'			=> 'ri ka ze shi',
		],
		'540202'	=> [
			'zh-cn'			=> '桑珠孜区',
			'en'			=> 'sang zhu zi qu',
		],
		'540221'	=> [
			'zh-cn'			=> '南木林县',
			'en'			=> 'nan mu lin xian',
		],
		'540222'	=> [
			'zh-cn'			=> '江孜县',
			'en'			=> 'jiang zi xian',
		],
		'540223'	=> [
			'zh-cn'			=> '定日县',
			'en'			=> 'ding ri xian',
		],
		'540224'	=> [
			'zh-cn'			=> '萨迦县',
			'en'			=> 'sa jia xian',
		],
		'540225'	=> [
			'zh-cn'			=> '拉孜县',
			'en'			=> 'la zi xian',
		],
		'540226'	=> [
			'zh-cn'			=> '昂仁县',
			'en'			=> 'ang ren xian',
		],
		'540227'	=> [
			'zh-cn'			=> '谢通门县',
			'en'			=> 'xie tong men xian',
		],
		'540228'	=> [
			'zh-cn'			=> '白朗县',
			'en'			=> 'bai lang xian',
		],
		'540229'	=> [
			'zh-cn'			=> '仁布县',
			'en'			=> 'ren bu xian',
		],
		'540230'	=> [
			'zh-cn'			=> '康马县',
			'en'			=> 'kang ma xian',
		],
		'540231'	=> [
			'zh-cn'			=> '定结县',
			'en'			=> 'ding jie xian',
		],
		'540232'	=> [
			'zh-cn'			=> '仲巴县',
			'en'			=> 'zhong ba xian',
		],
		'540233'	=> [
			'zh-cn'			=> '亚东县',
			'en'			=> 'ya dong xian',
		],
		'540234'	=> [
			'zh-cn'			=> '吉隆县',
			'en'			=> 'ji long xian',
		],
		'540235'	=> [
			'zh-cn'			=> '聂拉木县',
			'en'			=> 'nie la mu xian',
		],
		'540236'	=> [
			'zh-cn'			=> '萨嘎县',
			'en'			=> 'sa ga xian',
		],
		'540237'	=> [
			'zh-cn'			=> '岗巴县',
			'en'			=> 'gang ba xian',
		],
		'540300'	=> [
			'zh-cn'			=> '昌都市',
			'en'			=> 'chang du shi',
		],
		'540302'	=> [
			'zh-cn'			=> '卡若区',
			'en'			=> 'ka ruo qu',
		],
		'540321'	=> [
			'zh-cn'			=> '江达县',
			'en'			=> 'jiang da xian',
		],
		'540322'	=> [
			'zh-cn'			=> '贡觉县',
			'en'			=> 'gong jue xian',
		],
		'540323'	=> [
			'zh-cn'			=> '类乌齐县',
			'en'			=> 'lei wu qi xian',
		],
		'540324'	=> [
			'zh-cn'			=> '丁青县',
			'en'			=> 'ding qing xian',
		],
		'540325'	=> [
			'zh-cn'			=> '察雅县',
			'en'			=> 'cha ya xian',
		],
		'540326'	=> [
			'zh-cn'			=> '八宿县',
			'en'			=> 'ba su xian',
		],
		'540327'	=> [
			'zh-cn'			=> '左贡县',
			'en'			=> 'zuo gong xian',
		],
		'540328'	=> [
			'zh-cn'			=> '芒康县',
			'en'			=> 'mang kang xian',
		],
		'540329'	=> [
			'zh-cn'			=> '洛隆县',
			'en'			=> 'luo long xian',
		],
		'540330'	=> [
			'zh-cn'			=> '边坝县',
			'en'			=> 'bian ba xian',
		],
		'540400'	=> [
			'zh-cn'			=> '林芝市',
			'en'			=> 'lin zhi shi',
		],
		'540402'	=> [
			'zh-cn'			=> '巴宜区',
			'en'			=> 'ba yi qu',
		],
		'540421'	=> [
			'zh-cn'			=> '工布江达县',
			'en'			=> 'gong bu jiang da xian',
		],
		'540422'	=> [
			'zh-cn'			=> '米林县',
			'en'			=> 'mi lin xian',
		],
		'540423'	=> [
			'zh-cn'			=> '墨脱县',
			'en'			=> 'mo tuo xian',
		],
		'540424'	=> [
			'zh-cn'			=> '波密县',
			'en'			=> 'bo mi xian',
		],
		'540425'	=> [
			'zh-cn'			=> '察隅县',
			'en'			=> 'cha yu xian',
		],
		'540500'	=> [
			'zh-cn'			=> '山南市',
			'en'			=> 'shan nan shi',
		],
		'540502'	=> [
			'zh-cn'			=> '乃东区',
			'en'			=> 'nai dong qu',
		],
		'540521'	=> [
			'zh-cn'			=> '扎囊县',
			'en'			=> 'za nang xian',
		],
		'540522'	=> [
			'zh-cn'			=> '贡嘎县',
			'en'			=> 'gong ga xian',
		],
		'540523'	=> [
			'zh-cn'			=> '桑日县',
			'en'			=> 'sang ri xian',
		],
		'540524'	=> [
			'zh-cn'			=> '琼结县',
			'en'			=> 'qiong jie xian',
		],
		'540525'	=> [
			'zh-cn'			=> '曲松县',
			'en'			=> 'qu song xian',
		],
		'540526'	=> [
			'zh-cn'			=> '措美县',
			'en'			=> 'cuo mei xian',
		],
		'540527'	=> [
			'zh-cn'			=> '洛扎县',
			'en'			=> 'luo zha xian',
		],
		'540528'	=> [
			'zh-cn'			=> '加查县',
			'en'			=> 'jia cha xian',
		],
		'540529'	=> [
			'zh-cn'			=> '隆子县',
			'en'			=> 'long zi xian',
		],
		'540530'	=> [
			'zh-cn'			=> '错那县',
			'en'			=> 'cuo na xian',
		],
		'540531'	=> [
			'zh-cn'			=> '浪卡子县',
			'en'			=> 'lang ka zi xian',
		],
		'542100'	=> [
			'zh-cn'			=> '昌都地区',
			'en'			=> 'chang du di qu',
		],
		'542121'	=> [
			'zh-cn'			=> '昌都县',
			'en'			=> 'chang du xian',
		],
		'542122'	=> [
			'zh-cn'			=> '江达县',
			'en'			=> 'jiang da xian',
		],
		'542123'	=> [
			'zh-cn'			=> '贡觉县',
			'en'			=> 'gong jue xian',
		],
		'542124'	=> [
			'zh-cn'			=> '类乌齐县',
			'en'			=> 'lei wu qi xian',
		],
		'542125'	=> [
			'zh-cn'			=> '丁青县',
			'en'			=> 'ding qing xian',
		],
		'542126'	=> [
			'zh-cn'			=> '察雅县',
			'en'			=> 'cha ya xian',
		],
		'542127'	=> [
			'zh-cn'			=> '八宿县',
			'en'			=> 'ba su xian',
		],
		'542128'	=> [
			'zh-cn'			=> '左贡县',
			'en'			=> 'zuo gong xian',
		],
		'542129'	=> [
			'zh-cn'			=> '芒康县',
			'en'			=> 'mang kang xian',
		],
		'542130'	=> [
			'zh-cn'			=> '波密县',
			'en'			=> 'bo mi xian',
		],
		'542131'	=> [
			'zh-cn'			=> '察隅县',
			'en'			=> 'cha yu xian',
		],
		'542132'	=> [
			'zh-cn'			=> '洛隆县',
			'en'			=> 'luo long xian',
		],
		'542133'	=> [
			'zh-cn'			=> '边坝县',
			'en'			=> 'bian ba xian',
		],
		'542134'	=> [
			'zh-cn'			=> '盐井县',
			'en'			=> 'yan jing xian',
		],
		'542135'	=> [
			'zh-cn'			=> '碧土县',
			'en'			=> 'bi tu xian',
		],
		'542136'	=> [
			'zh-cn'			=> '妥坝县',
			'en'			=> 'tuo ba xian',
		],
		'542137'	=> [
			'zh-cn'			=> '生达县',
			'en'			=> 'sheng da xian',
		],
		'542200'	=> [
			'zh-cn'			=> '山南地区',
			'en'			=> 'shan nan di qu',
		],
		'542221'	=> [
			'zh-cn'			=> '乃东县',
			'en'			=> 'nai dong xian',
		],
		'542222'	=> [
			'zh-cn'			=> '扎囊县',
			'en'			=> 'za nang xian',
		],
		'542223'	=> [
			'zh-cn'			=> '贡嘎县',
			'en'			=> 'gong ga xian',
		],
		'542224'	=> [
			'zh-cn'			=> '桑日县',
			'en'			=> 'sang ri xian',
		],
		'542225'	=> [
			'zh-cn'			=> '琼结县',
			'en'			=> 'qiong jie xian',
		],
		'542226'	=> [
			'zh-cn'			=> '曲松县',
			'en'			=> 'qu song xian',
		],
		'542227'	=> [
			'zh-cn'			=> '措美县',
			'en'			=> 'cuo mei xian',
		],
		'542228'	=> [
			'zh-cn'			=> '洛扎县',
			'en'			=> 'luo zha xian',
		],
		'542229'	=> [
			'zh-cn'			=> '加查县',
			'en'			=> 'jia cha xian',
		],
		'542231'	=> [
			'zh-cn'			=> '隆子县',
			'en'			=> 'long zi xian',
		],
		'542232'	=> [
			'zh-cn'			=> '错那县',
			'en'			=> 'cuo na xian',
		],
		'542233'	=> [
			'zh-cn'			=> '浪卡子县',
			'en'			=> 'lang ka zi xian',
		],
		'542300'	=> [
			'zh-cn'			=> '日喀则地区',
			'en'			=> 'ri ka ze di qu',
		],
		'542301'	=> [
			'zh-cn'			=> '日喀则市',
			'en'			=> 'ri ka ze shi',
		],
		'542321'	=> [
			'zh-cn'			=> '日喀则县',
			'en'			=> 'ri ka ze xian',
		],
		'542322'	=> [
			'zh-cn'			=> '南木林县',
			'en'			=> 'nan mu lin xian',
		],
		'542323'	=> [
			'zh-cn'			=> '江孜县',
			'en'			=> 'jiang zi xian',
		],
		'542324'	=> [
			'zh-cn'			=> '定日县',
			'en'			=> 'ding ri xian',
		],
		'542325'	=> [
			'zh-cn'			=> '萨迦县',
			'en'			=> 'sa jia xian',
		],
		'542326'	=> [
			'zh-cn'			=> '拉孜县',
			'en'			=> 'la zi xian',
		],
		'542327'	=> [
			'zh-cn'			=> '昂仁县',
			'en'			=> 'ang ren xian',
		],
		'542328'	=> [
			'zh-cn'			=> '谢通门县',
			'en'			=> 'xie tong men xian',
		],
		'542329'	=> [
			'zh-cn'			=> '白朗县',
			'en'			=> 'bai lang xian',
		],
		'542330'	=> [
			'zh-cn'			=> '仁布县',
			'en'			=> 'ren bu xian',
		],
		'542331'	=> [
			'zh-cn'			=> '康马县',
			'en'			=> 'kang ma xian',
		],
		'542332'	=> [
			'zh-cn'			=> '定结县',
			'en'			=> 'ding jie xian',
		],
		'542333'	=> [
			'zh-cn'			=> '仲巴县',
			'en'			=> 'zhong ba xian',
		],
		'542334'	=> [
			'zh-cn'			=> '亚东县',
			'en'			=> 'ya dong xian',
		],
		'542335'	=> [
			'zh-cn'			=> '吉隆县',
			'en'			=> 'ji long xian',
		],
		'542336'	=> [
			'zh-cn'			=> '聂拉木县',
			'en'			=> 'nie la mu xian',
		],
		'542337'	=> [
			'zh-cn'			=> '萨嘎县',
			'en'			=> 'sa ga xian',
		],
		'542338'	=> [
			'zh-cn'			=> '岗巴县',
			'en'			=> 'gang ba xian',
		],
		'542339'	=> [
			'zh-cn'			=> '岗巴县',
			'en'			=> 'gang ba xian',
		],
		'542400'	=> [
			'zh-cn'			=> '那曲地区',
			'en'			=> 'na qu di qu',
		],
		'542421'	=> [
			'zh-cn'			=> '那曲县',
			'en'			=> 'na qu xian',
		],
		'542422'	=> [
			'zh-cn'			=> '嘉黎县',
			'en'			=> 'jia li xian',
		],
		'542423'	=> [
			'zh-cn'			=> '比如县',
			'en'			=> 'bi ru xian',
		],
		'542424'	=> [
			'zh-cn'			=> '聂荣县',
			'en'			=> 'nie rong xian',
		],
		'542425'	=> [
			'zh-cn'			=> '安多县',
			'en'			=> 'an duo xian',
		],
		'542426'	=> [
			'zh-cn'			=> '申扎县',
			'en'			=> 'shen zha xian',
		],
		'542428'	=> [
			'zh-cn'			=> '班戈县',
			'en'			=> 'ban ge xian',
		],
		'542429'	=> [
			'zh-cn'			=> '巴青县',
			'en'			=> 'ba qing xian',
		],
		'542430'	=> [
			'zh-cn'			=> '尼玛县',
			'en'			=> 'ni ma xian',
		],
		'542431'	=> [
			'zh-cn'			=> '双湖县',
			'en'			=> 'shuang hu xian',
		],
		'542500'	=> [
			'zh-cn'			=> '阿里地区',
			'en'			=> 'a li di qu',
		],
		'542521'	=> [
			'zh-cn'			=> '普兰县',
			'en'			=> 'pu lan xian',
		],
		'542522'	=> [
			'zh-cn'			=> '札达县',
			'en'			=> 'zha da xian',
		],
		'542523'	=> [
			'zh-cn'			=> '噶尔县',
			'en'			=> 'ga er xian',
		],
		'542524'	=> [
			'zh-cn'			=> '日土县',
			'en'			=> 'ri tu xian',
		],
		'542525'	=> [
			'zh-cn'			=> '革吉县',
			'en'			=> 'ge ji xian',
		],
		'542526'	=> [
			'zh-cn'			=> '改则县',
			'en'			=> 'gai ze xian',
		],
		'542527'	=> [
			'zh-cn'			=> '措勤县',
			'en'			=> 'cuo qin xian',
		],
		'542528'	=> [
			'zh-cn'			=> '隆格尔县',
			'en'			=> 'long ge er xian',
		],
		'542600'	=> [
			'zh-cn'			=> '林芝地区',
			'en'			=> 'lin zhi di qu',
		],
		'542621'	=> [
			'zh-cn'			=> '林芝县',
			'en'			=> 'lin zhi xian',
		],
		'542622'	=> [
			'zh-cn'			=> '工布江达县',
			'en'			=> 'gong bu jiang da xian',
		],
		'542623'	=> [
			'zh-cn'			=> '米林县',
			'en'			=> 'mi lin xian',
		],
		'542624'	=> [
			'zh-cn'			=> '墨脱县',
			'en'			=> 'mo tuo xian',
		],
		'542625'	=> [
			'zh-cn'			=> '波密县',
			'en'			=> 'bo mi xian',
		],
		'542626'	=> [
			'zh-cn'			=> '察隅县',
			'en'			=> 'cha yu xian',
		],
		'542700'	=> [
			'zh-cn'			=> '江孜地区',
			'en'			=> 'jiang zi di qu',
		],
		'542721'	=> [
			'zh-cn'			=> '江孜县',
			'en'			=> 'jiang zi xian',
		],
		'542722'	=> [
			'zh-cn'			=> '浪卡子县',
			'en'			=> 'lang ka zi xian',
		],
		'542723'	=> [
			'zh-cn'			=> '白朗县',
			'en'			=> 'bai lang xian',
		],
		'542724'	=> [
			'zh-cn'			=> '仁布县',
			'en'			=> 'ren bu xian',
		],
		'542725'	=> [
			'zh-cn'			=> '康马县',
			'en'			=> 'kang ma xian',
		],
		'542726'	=> [
			'zh-cn'			=> '亚东县',
			'en'			=> 'ya dong xian',
		],
		'542727'	=> [
			'zh-cn'			=> '岗巴县',
			'en'			=> 'gang ba xian',
		],
		'610000'	=> [
			'zh-cn'			=> '陕西省',
			'en'			=> 'shan xi sheng',
		],
		'610100'	=> [
			'zh-cn'			=> '西安市',
			'en'			=> 'xi an shi',
		],
		'610102'	=> [
			'zh-cn'			=> '新城区',
			'en'			=> 'xin cheng qu',
		],
		'610103'	=> [
			'zh-cn'			=> '碑林区',
			'en'			=> 'bei lin qu',
		],
		'610104'	=> [
			'zh-cn'			=> '莲湖区',
			'en'			=> 'lian hu qu',
		],
		'610111'	=> [
			'zh-cn'			=> '灞桥区',
			'en'			=> 'ba qiao qu',
		],
		'610112'	=> [
			'zh-cn'			=> '未央区',
			'en'			=> 'wei yang qu',
		],
		'610113'	=> [
			'zh-cn'			=> '雁塔区',
			'en'			=> 'yan ta qu',
		],
		'610114'	=> [
			'zh-cn'			=> '阎良区',
			'en'			=> 'yan liang qu',
		],
		'610115'	=> [
			'zh-cn'			=> '临潼区',
			'en'			=> 'lin tong qu',
		],
		'610116'	=> [
			'zh-cn'			=> '长安区',
			'en'			=> 'chang an qu',
		],
		'610117'	=> [
			'zh-cn'			=> '高陵区',
			'en'			=> 'gao ling qu',
		],
		'610118'	=> [
			'zh-cn'			=> '鄠邑区',
			'en'			=> 'hu yi qu',
		],
		'610121'	=> [
			'zh-cn'			=> '长安县',
			'en'			=> 'chang an xian',
		],
		'610122'	=> [
			'zh-cn'			=> '蓝田县',
			'en'			=> 'lan tian xian',
		],
		'610123'	=> [
			'zh-cn'			=> '临潼县',
			'en'			=> 'lin tong xian',
		],
		'610124'	=> [
			'zh-cn'			=> '周至县',
			'en'			=> 'zhou zhi xian',
		],
		'610126'	=> [
			'zh-cn'			=> '高陵县',
			'en'			=> 'gao ling xian',
		],
		'610200'	=> [
			'zh-cn'			=> '铜川市',
			'en'			=> 'tong chuan shi',
		],
		'610202'	=> [
			'zh-cn'			=> '王益区',
			'en'			=> 'wang yi qu',
		],
		'610203'	=> [
			'zh-cn'			=> '印台区',
			'en'			=> 'yin tai qu',
		],
		'610204'	=> [
			'zh-cn'			=> '耀州区',
			'en'			=> 'yao zhou qu',
		],
		'610222'	=> [
			'zh-cn'			=> '宜君县',
			'en'			=> 'yi jun xian',
		],
		'610300'	=> [
			'zh-cn'			=> '宝鸡市',
			'en'			=> 'bao ji shi',
		],
		'610302'	=> [
			'zh-cn'			=> '渭滨区',
			'en'			=> 'wei bin qu',
		],
		'610303'	=> [
			'zh-cn'			=> '金台区',
			'en'			=> 'jin tai qu',
		],
		'610304'	=> [
			'zh-cn'			=> '陈仓区',
			'en'			=> 'chen cang qu',
		],
		'610321'	=> [
			'zh-cn'			=> '宝鸡县',
			'en'			=> 'bao ji xian',
		],
		'610322'	=> [
			'zh-cn'			=> '凤翔县',
			'en'			=> 'feng xiang xian',
		],
		'610323'	=> [
			'zh-cn'			=> '岐山县',
			'en'			=> 'qi shan xian',
		],
		'610324'	=> [
			'zh-cn'			=> '扶风县',
			'en'			=> 'fu feng xian',
		],
		'610325'	=> [
			'zh-cn'			=> '武功县',
			'en'			=> 'wu gong xian',
		],
		'610328'	=> [
			'zh-cn'			=> '千阳县',
			'en'			=> 'qian yang xian',
		],
		'610329'	=> [
			'zh-cn'			=> '麟游县',
			'en'			=> 'lin you xian',
		],
		'610331'	=> [
			'zh-cn'			=> '太白县',
			'en'			=> 'tai bai xian',
		],
		'610400'	=> [
			'zh-cn'			=> '咸阳市',
			'en'			=> 'xian yang shi',
		],
		'610402'	=> [
			'zh-cn'			=> '秦都区',
			'en'			=> 'qin du qu',
		],
		'610403'	=> [
			'zh-cn'			=> '杨陵区',
			'en'			=> 'yang ling qu',
		],
		'610404'	=> [
			'zh-cn'			=> '渭城区',
			'en'			=> 'wei cheng qu',
		],
		'610421'	=> [
			'zh-cn'			=> '兴平县',
			'en'			=> 'xing ping xian',
		],
		'610422'	=> [
			'zh-cn'			=> '三原县',
			'en'			=> 'san yuan xian',
		],
		'610423'	=> [
			'zh-cn'			=> '泾阳县',
			'en'			=> 'jing yang xian',
		],
		'610425'	=> [
			'zh-cn'			=> '礼泉县',
			'en'			=> 'li quan xian',
		],
		'610426'	=> [
			'zh-cn'			=> '永寿县',
			'en'			=> 'yong shou xian',
		],
		'610428'	=> [
			'zh-cn'			=> '长武县',
			'en'			=> 'chang wu xian',
		],
		'610429'	=> [
			'zh-cn'			=> '旬邑县',
			'en'			=> 'xun yi xian',
		],
		'610430'	=> [
			'zh-cn'			=> '淳化县',
			'en'			=> 'chun hua xian',
		],
		'610431'	=> [
			'zh-cn'			=> '武功县',
			'en'			=> 'wu gong xian',
		],
		'610481'	=> [
			'zh-cn'			=> '兴平市',
			'en'			=> 'xing ping shi',
		],
		'610500'	=> [
			'zh-cn'			=> '渭南市',
			'en'			=> 'wei nan shi',
		],
		'610502'	=> [
			'zh-cn'			=> '临渭区',
			'en'			=> 'lin wei qu',
		],
		'610503'	=> [
			'zh-cn'			=> '华州区',
			'en'			=> 'hua zhou qu',
		],
		'610522'	=> [
			'zh-cn'			=> '潼关县',
			'en'			=> 'tong guan xian',
		],
		'610523'	=> [
			'zh-cn'			=> '大荔县',
			'en'			=> 'da li xian',
		],
		'610524'	=> [
			'zh-cn'			=> '合阳县',
			'en'			=> 'he yang xian',
		],
		'610525'	=> [
			'zh-cn'			=> '澄城县',
			'en'			=> 'cheng cheng xian',
		],
		'610526'	=> [
			'zh-cn'			=> '蒲城县',
			'en'			=> 'pu cheng xian',
		],
		'610527'	=> [
			'zh-cn'			=> '白水县',
			'en'			=> 'bai shui xian',
		],
		'610528'	=> [
			'zh-cn'			=> '富平县',
			'en'			=> 'fu ping xian',
		],
		'610581'	=> [
			'zh-cn'			=> '韩城市',
			'en'			=> 'han cheng shi',
		],
		'610582'	=> [
			'zh-cn'			=> '华阴市',
			'en'			=> 'hua yin shi',
		],
		'610600'	=> [
			'zh-cn'			=> '延安市',
			'en'			=> 'yan an shi',
		],
		'610602'	=> [
			'zh-cn'			=> '宝塔区',
			'en'			=> 'bao ta qu',
		],
		'610603'	=> [
			'zh-cn'			=> '安塞区',
			'en'			=> 'an sai qu',
		],
		'610621'	=> [
			'zh-cn'			=> '延长县',
			'en'			=> 'yan chang xian',
		],
		'610622'	=> [
			'zh-cn'			=> '延川县',
			'en'			=> 'yan chuan xian',
		],
		'610623'	=> [
			'zh-cn'			=> '子长县',
			'en'			=> 'zi chang xian',
		],
		'610624'	=> [
			'zh-cn'			=> '安塞县',
			'en'			=> 'an sai xian',
		],
		'610625'	=> [
			'zh-cn'			=> '志丹县',
			'en'			=> 'zhi dan xian',
		],
		'610626'	=> [
			'zh-cn'			=> '吴起县',
			'en'			=> 'wu qi xian',
		],
		'610627'	=> [
			'zh-cn'			=> '甘泉县',
			'en'			=> 'gan quan xian',
		],
		'610629'	=> [
			'zh-cn'			=> '洛川县',
			'en'			=> 'luo chuan xian',
		],
		'610630'	=> [
			'zh-cn'			=> '宜川县',
			'en'			=> 'yi chuan xian',
		],
		'610631'	=> [
			'zh-cn'			=> '黄龙县',
			'en'			=> 'huang long xian',
		],
		'610632'	=> [
			'zh-cn'			=> '黄陵县',
			'en'			=> 'huang ling xian',
		],
		'610700'	=> [
			'zh-cn'			=> '汉中市',
			'en'			=> 'han zhong shi',
		],
		'610702'	=> [
			'zh-cn'			=> '汉台区',
			'en'			=> 'han tai qu',
		],
		'610721'	=> [
			'zh-cn'			=> '南郑县',
			'en'			=> 'nan zheng xian',
		],
		'610722'	=> [
			'zh-cn'			=> '城固县',
			'en'			=> 'cheng gu xian',
		],
		'610724'	=> [
			'zh-cn'			=> '西乡县',
			'en'			=> 'xi xiang xian',
		],
		'610726'	=> [
			'zh-cn'			=> '宁强县',
			'en'			=> 'ning qiang xian',
		],
		'610727'	=> [
			'zh-cn'			=> '略阳县',
			'en'			=> 'lue yang xian',
		],
		'610728'	=> [
			'zh-cn'			=> '镇巴县',
			'en'			=> 'zhen ba xian',
		],
		'610729'	=> [
			'zh-cn'			=> '留坝县',
			'en'			=> 'liu ba xian',
		],
		'610730'	=> [
			'zh-cn'			=> '佛坪县',
			'en'			=> 'fo ping xian',
		],
		'610800'	=> [
			'zh-cn'			=> '榆林市',
			'en'			=> 'yu lin shi',
		],
		'610802'	=> [
			'zh-cn'			=> '榆阳区',
			'en'			=> 'yu yang qu',
		],
		'610803'	=> [
			'zh-cn'			=> '横山区',
			'en'			=> 'heng shan qu',
		],
		'610821'	=> [
			'zh-cn'			=> '神木县',
			'en'			=> 'shen mu xian',
		],
		'610822'	=> [
			'zh-cn'			=> '府谷县',
			'en'			=> 'fu gu xian',
		],
		'610823'	=> [
			'zh-cn'			=> '横山县',
			'en'			=> 'heng shan xian',
		],
		'610824'	=> [
			'zh-cn'			=> '靖边县',
			'en'			=> 'jing bian xian',
		],
		'610825'	=> [
			'zh-cn'			=> '定边县',
			'en'			=> 'ding bian xian',
		],
		'610826'	=> [
			'zh-cn'			=> '绥德县',
			'en'			=> 'sui de xian',
		],
		'610827'	=> [
			'zh-cn'			=> '米脂县',
			'en'			=> 'mi zhi xian',
		],
		'610829'	=> [
			'zh-cn'			=> '吴堡县',
			'en'			=> 'wu bu xian',
		],
		'610830'	=> [
			'zh-cn'			=> '清涧县',
			'en'			=> 'qing jian xian',
		],
		'610831'	=> [
			'zh-cn'			=> '子洲县',
			'en'			=> 'zi zhou xian',
		],
		'610900'	=> [
			'zh-cn'			=> '安康市',
			'en'			=> 'an kang shi',
		],
		'610902'	=> [
			'zh-cn'			=> '汉滨区',
			'en'			=> 'han bin qu',
		],
		'610921'	=> [
			'zh-cn'			=> '汉阴县',
			'en'			=> 'han yin xian',
		],
		'610922'	=> [
			'zh-cn'			=> '石泉县',
			'en'			=> 'shi quan xian',
		],
		'610923'	=> [
			'zh-cn'			=> '宁陕县',
			'en'			=> 'ning shan xian',
		],
		'610924'	=> [
			'zh-cn'			=> '紫阳县',
			'en'			=> 'zi yang xian',
		],
		'610925'	=> [
			'zh-cn'			=> '岚皋县',
			'en'			=> 'lan gao xian',
		],
		'610926'	=> [
			'zh-cn'			=> '平利县',
			'en'			=> 'ping li xian',
		],
		'610927'	=> [
			'zh-cn'			=> '镇坪县',
			'en'			=> 'zhen ping xian',
		],
		'610928'	=> [
			'zh-cn'			=> '旬阳县',
			'en'			=> 'xun yang xian',
		],
		'610929'	=> [
			'zh-cn'			=> '白河县',
			'en'			=> 'bai he xian',
		],
		'611000'	=> [
			'zh-cn'			=> '商洛市',
			'en'			=> 'shang luo shi',
		],
		'611002'	=> [
			'zh-cn'			=> '商州区',
			'en'			=> 'shang zhou qu',
		],
		'611021'	=> [
			'zh-cn'			=> '洛南县',
			'en'			=> 'luo nan xian',
		],
		'611022'	=> [
			'zh-cn'			=> '丹凤县',
			'en'			=> 'dan feng xian',
		],
		'611023'	=> [
			'zh-cn'			=> '商南县',
			'en'			=> 'shang nan xian',
		],
		'611024'	=> [
			'zh-cn'			=> '山阳县',
			'en'			=> 'shan yang xian',
		],
		'611025'	=> [
			'zh-cn'			=> '镇安县',
			'en'			=> 'zhen an xian',
		],
		'611026'	=> [
			'zh-cn'			=> '柞水县',
			'en'			=> 'zha shui xian',
		],
		'612100'	=> [
			'zh-cn'			=> '渭南地区',
			'en'			=> 'wei nan di qu',
		],
		'612101'	=> [
			'zh-cn'			=> '渭南市',
			'en'			=> 'wei nan shi',
		],
		'612102'	=> [
			'zh-cn'			=> '韩城市',
			'en'			=> 'han cheng shi',
		],
		'612103'	=> [
			'zh-cn'			=> '华阴市',
			'en'			=> 'hua yin shi',
		],
		'612121'	=> [
			'zh-cn'			=> '渭南县',
			'en'			=> 'wei nan xian',
		],
		'612122'	=> [
			'zh-cn'			=> '韩城县',
			'en'			=> 'han cheng xian',
		],
		'612123'	=> [
			'zh-cn'			=> '临潼县',
			'en'			=> 'lin tong xian',
		],
		'612125'	=> [
			'zh-cn'			=> '华阴县',
			'en'			=> 'hua yin xian',
		],
		'612126'	=> [
			'zh-cn'			=> '潼关县',
			'en'			=> 'tong guan xian',
		],
		'612127'	=> [
			'zh-cn'			=> '大荔县',
			'en'			=> 'da li xian',
		],
		'612128'	=> [
			'zh-cn'			=> '蒲城县',
			'en'			=> 'pu cheng xian',
		],
		'612129'	=> [
			'zh-cn'			=> '澄城县',
			'en'			=> 'cheng cheng xian',
		],
		'612130'	=> [
			'zh-cn'			=> '白水县',
			'en'			=> 'bai shui xian',
		],
		'612131'	=> [
			'zh-cn'			=> '蓝田县',
			'en'			=> 'lan tian xian',
		],
		'612132'	=> [
			'zh-cn'			=> '合阳县',
			'en'			=> 'he yang xian',
		],
		'612133'	=> [
			'zh-cn'			=> '富平县',
			'en'			=> 'fu ping xian',
		],
		'612200'	=> [
			'zh-cn'			=> '咸阳地区',
			'en'			=> 'xian yang di qu',
		],
		'612201'	=> [
			'zh-cn'			=> '咸阳市',
			'en'			=> 'xian yang shi',
		],
		'612221'	=> [
			'zh-cn'			=> '兴平县',
			'en'			=> 'xing ping xian',
		],
		'612222'	=> [
			'zh-cn'			=> '三原县',
			'en'			=> 'san yuan xian',
		],
		'612223'	=> [
			'zh-cn'			=> '泾阳县',
			'en'			=> 'jing yang xian',
		],
		'612225'	=> [
			'zh-cn'			=> '礼泉县',
			'en'			=> 'li quan xian',
		],
		'612226'	=> [
			'zh-cn'			=> '永寿县',
			'en'			=> 'yong shou xian',
		],
		'612228'	=> [
			'zh-cn'			=> '长武县',
			'en'			=> 'chang wu xian',
		],
		'612229'	=> [
			'zh-cn'			=> '旬邑县',
			'en'			=> 'xun yi xian',
		],
		'612230'	=> [
			'zh-cn'			=> '淳化县',
			'en'			=> 'chun hua xian',
		],
		'612231'	=> [
			'zh-cn'			=> '高陵县',
			'en'			=> 'gao ling xian',
		],
		'612232'	=> [
			'zh-cn'			=> '周至县',
			'en'			=> 'zhou zhi xian',
		],
		'612300'	=> [
			'zh-cn'			=> '汉中地区',
			'en'			=> 'han zhong di qu',
		],
		'612301'	=> [
			'zh-cn'			=> '汉中市',
			'en'			=> 'han zhong shi',
		],
		'612321'	=> [
			'zh-cn'			=> '南郑县',
			'en'			=> 'nan zheng xian',
		],
		'612322'	=> [
			'zh-cn'			=> '城固县',
			'en'			=> 'cheng gu xian',
		],
		'612324'	=> [
			'zh-cn'			=> '西乡县',
			'en'			=> 'xi xiang xian',
		],
		'612326'	=> [
			'zh-cn'			=> '宁强县',
			'en'			=> 'ning qiang xian',
		],
		'612327'	=> [
			'zh-cn'			=> '略阳县',
			'en'			=> 'lue yang xian',
		],
		'612328'	=> [
			'zh-cn'			=> '镇巴县',
			'en'			=> 'zhen ba xian',
		],
		'612329'	=> [
			'zh-cn'			=> '留坝县',
			'en'			=> 'liu ba xian',
		],
		'612330'	=> [
			'zh-cn'			=> '佛坪县',
			'en'			=> 'fo ping xian',
		],
		'612400'	=> [
			'zh-cn'			=> '安康地区',
			'en'			=> 'an kang di qu',
		],
		'612401'	=> [
			'zh-cn'			=> '安康市',
			'en'			=> 'an kang shi',
		],
		'612421'	=> [
			'zh-cn'			=> '安康县',
			'en'			=> 'an kang xian',
		],
		'612422'	=> [
			'zh-cn'			=> '汉阴县',
			'en'			=> 'han yin xian',
		],
		'612423'	=> [
			'zh-cn'			=> '石泉县',
			'en'			=> 'shi quan xian',
		],
		'612424'	=> [
			'zh-cn'			=> '宁陕县',
			'en'			=> 'ning shan xian',
		],
		'612425'	=> [
			'zh-cn'			=> '紫阳县',
			'en'			=> 'zi yang xian',
		],
		'612426'	=> [
			'zh-cn'			=> '岚皋县',
			'en'			=> 'lan gao xian',
		],
		'612427'	=> [
			'zh-cn'			=> '平利县',
			'en'			=> 'ping li xian',
		],
		'612428'	=> [
			'zh-cn'			=> '镇坪县',
			'en'			=> 'zhen ping xian',
		],
		'612429'	=> [
			'zh-cn'			=> '旬阳县',
			'en'			=> 'xun yang xian',
		],
		'612430'	=> [
			'zh-cn'			=> '白河县',
			'en'			=> 'bai he xian',
		],
		'612500'	=> [
			'zh-cn'			=> '商洛地区',
			'en'			=> 'shang luo di qu',
		],
		'612501'	=> [
			'zh-cn'			=> '商州市',
			'en'			=> 'shang zhou shi',
		],
		'612522'	=> [
			'zh-cn'			=> '洛南县',
			'en'			=> 'luo nan xian',
		],
		'612523'	=> [
			'zh-cn'			=> '丹凤县',
			'en'			=> 'dan feng xian',
		],
		'612524'	=> [
			'zh-cn'			=> '商南县',
			'en'			=> 'shang nan xian',
		],
		'612525'	=> [
			'zh-cn'			=> '山阳县',
			'en'			=> 'shan yang xian',
		],
		'612526'	=> [
			'zh-cn'			=> '镇安县',
			'en'			=> 'zhen an xian',
		],
		'612527'	=> [
			'zh-cn'			=> '柞水县',
			'en'			=> 'zha shui xian',
		],
		'612600'	=> [
			'zh-cn'			=> '延安地区',
			'en'			=> 'yan an di qu',
		],
		'612601'	=> [
			'zh-cn'			=> '延安市',
			'en'			=> 'yan an shi',
		],
		'612621'	=> [
			'zh-cn'			=> '延长县',
			'en'			=> 'yan chang xian',
		],
		'612622'	=> [
			'zh-cn'			=> '延川县',
			'en'			=> 'yan chuan xian',
		],
		'612623'	=> [
			'zh-cn'			=> '子长县',
			'en'			=> 'zi chang xian',
		],
		'612624'	=> [
			'zh-cn'			=> '安塞县',
			'en'			=> 'an sai xian',
		],
		'612625'	=> [
			'zh-cn'			=> '志丹县',
			'en'			=> 'zhi dan xian',
		],
		'612626'	=> [
			'zh-cn'			=> '吴旗县',
			'en'			=> 'wu qi xian',
		],
		'612627'	=> [
			'zh-cn'			=> '甘泉县',
			'en'			=> 'gan quan xian',
		],
		'612629'	=> [
			'zh-cn'			=> '洛川县',
			'en'			=> 'luo chuan xian',
		],
		'612630'	=> [
			'zh-cn'			=> '宜川县',
			'en'			=> 'yi chuan xian',
		],
		'612631'	=> [
			'zh-cn'			=> '黄龙县',
			'en'			=> 'huang long xian',
		],
		'612632'	=> [
			'zh-cn'			=> '黄陵县',
			'en'			=> 'huang ling xian',
		],
		'612633'	=> [
			'zh-cn'			=> '宜君县',
			'en'			=> 'yi jun xian',
		],
		'612700'	=> [
			'zh-cn'			=> '榆林地区',
			'en'			=> 'yu lin di qu',
		],
		'612701'	=> [
			'zh-cn'			=> '榆林市',
			'en'			=> 'yu lin shi',
		],
		'612721'	=> [
			'zh-cn'			=> '榆林县',
			'en'			=> 'yu lin xian',
		],
		'612722'	=> [
			'zh-cn'			=> '神木县',
			'en'			=> 'shen mu xian',
		],
		'612723'	=> [
			'zh-cn'			=> '府谷县',
			'en'			=> 'fu gu xian',
		],
		'612724'	=> [
			'zh-cn'			=> '横山县',
			'en'			=> 'heng shan xian',
		],
		'612725'	=> [
			'zh-cn'			=> '靖边县',
			'en'			=> 'jing bian xian',
		],
		'612726'	=> [
			'zh-cn'			=> '定边县',
			'en'			=> 'ding bian xian',
		],
		'612727'	=> [
			'zh-cn'			=> '绥德县',
			'en'			=> 'sui de xian',
		],
		'612728'	=> [
			'zh-cn'			=> '米脂县',
			'en'			=> 'mi zhi xian',
		],
		'612730'	=> [
			'zh-cn'			=> '吴堡县',
			'en'			=> 'wu bu xian',
		],
		'612731'	=> [
			'zh-cn'			=> '清涧县',
			'en'			=> 'qing jian xian',
		],
		'612732'	=> [
			'zh-cn'			=> '子洲县',
			'en'			=> 'zi zhou xian',
		],
		'619001'	=> [
			'zh-cn'			=> '兴平市',
			'en'			=> 'xing ping shi',
		],
		'619002'	=> [
			'zh-cn'			=> '韩城市',
			'en'			=> 'han cheng shi',
		],
		'619003'	=> [
			'zh-cn'			=> '华阴市',
			'en'			=> 'hua yin shi',
		],
		'620000'	=> [
			'zh-cn'			=> '甘肃省',
			'en'			=> 'gan su sheng',
		],
		'620100'	=> [
			'zh-cn'			=> '兰州市',
			'en'			=> 'lan zhou shi',
		],
		'620102'	=> [
			'zh-cn'			=> '城关区',
			'en'			=> 'cheng guan qu',
		],
		'620103'	=> [
			'zh-cn'			=> '七里河区',
			'en'			=> 'qi li he qu',
		],
		'620104'	=> [
			'zh-cn'			=> '西固区',
			'en'			=> 'xi gu qu',
		],
		'620105'	=> [
			'zh-cn'			=> '安宁区',
			'en'			=> 'an ning qu',
		],
		'620111'	=> [
			'zh-cn'			=> '红古区',
			'en'			=> 'hong gu qu',
		],
		'620112'	=> [
			'zh-cn'			=> '白银区',
			'en'			=> 'bai yin qu',
		],
		'620121'	=> [
			'zh-cn'			=> '永登县',
			'en'			=> 'yong deng xian',
		],
		'620122'	=> [
			'zh-cn'			=> '皋兰县',
			'en'			=> 'gao lan xian',
		],
		'620123'	=> [
			'zh-cn'			=> '榆中县',
			'en'			=> 'yu zhong xian',
		],
		'620200'	=> [
			'zh-cn'			=> '嘉峪关市',
			'en'			=> 'jia yu guan shi',
		],
		'620300'	=> [
			'zh-cn'			=> '金昌市',
			'en'			=> 'jin chang shi',
		],
		'620302'	=> [
			'zh-cn'			=> '金川区',
			'en'			=> 'jin chuan qu',
		],
		'620321'	=> [
			'zh-cn'			=> '永昌县',
			'en'			=> 'yong chang xian',
		],
		'620400'	=> [
			'zh-cn'			=> '白银市',
			'en'			=> 'bai yin shi',
		],
		'620402'	=> [
			'zh-cn'			=> '白银区',
			'en'			=> 'bai yin qu',
		],
		'620403'	=> [
			'zh-cn'			=> '平川区',
			'en'			=> 'ping chuan qu',
		],
		'620421'	=> [
			'zh-cn'			=> '靖远县',
			'en'			=> 'jing yuan xian',
		],
		'620422'	=> [
			'zh-cn'			=> '会宁县',
			'en'			=> 'hui ning xian',
		],
		'620423'	=> [
			'zh-cn'			=> '景泰县',
			'en'			=> 'jing tai xian',
		],
		'620500'	=> [
			'zh-cn'			=> '天水市',
			'en'			=> 'tian shui shi',
		],
		'620502'	=> [
			'zh-cn'			=> '秦州区',
			'en'			=> 'qin zhou qu',
		],
		'620503'	=> [
			'zh-cn'			=> '麦积区',
			'en'			=> 'mai ji qu',
		],
		'620521'	=> [
			'zh-cn'			=> '清水县',
			'en'			=> 'qing shui xian',
		],
		'620522'	=> [
			'zh-cn'			=> '秦安县',
			'en'			=> 'qin an xian',
		],
		'620523'	=> [
			'zh-cn'			=> '甘谷县',
			'en'			=> 'gan gu xian',
		],
		'620524'	=> [
			'zh-cn'			=> '武山县',
			'en'			=> 'wu shan xian',
		],
		'620525'	=> [
			'zh-cn'			=> '张家川回族自治县',
			'en'			=> 'zhang jia chuan hui zu zi zhi xian',
		],
		'620600'	=> [
			'zh-cn'			=> '武威市',
			'en'			=> 'wu wei shi',
		],
		'620602'	=> [
			'zh-cn'			=> '凉州区',
			'en'			=> 'liang zhou qu',
		],
		'620621'	=> [
			'zh-cn'			=> '民勤县',
			'en'			=> 'min qin xian',
		],
		'620622'	=> [
			'zh-cn'			=> '古浪县',
			'en'			=> 'gu lang xian',
		],
		'620623'	=> [
			'zh-cn'			=> '天祝藏族自治县',
			'en'			=> 'tian zhu zang zu zi zhi xian',
		],
		'620700'	=> [
			'zh-cn'			=> '张掖市',
			'en'			=> 'zhang ye shi',
		],
		'620702'	=> [
			'zh-cn'			=> '甘州区',
			'en'			=> 'gan zhou qu',
		],
		'620721'	=> [
			'zh-cn'			=> '肃南裕固族自治县',
			'en'			=> 'su nan yu gu zu zi zhi xian',
		],
		'620722'	=> [
			'zh-cn'			=> '民乐县',
			'en'			=> 'min yue xian',
		],
		'620723'	=> [
			'zh-cn'			=> '临泽县',
			'en'			=> 'lin ze xian',
		],
		'620724'	=> [
			'zh-cn'			=> '高台县',
			'en'			=> 'gao tai xian',
		],
		'620725'	=> [
			'zh-cn'			=> '山丹县',
			'en'			=> 'shan dan xian',
		],
		'620800'	=> [
			'zh-cn'			=> '平凉市',
			'en'			=> 'ping liang shi',
		],
		'620802'	=> [
			'zh-cn'			=> '崆峒区',
			'en'			=> 'kong tong qu',
		],
		'620821'	=> [
			'zh-cn'			=> '泾川县',
			'en'			=> 'jing chuan xian',
		],
		'620822'	=> [
			'zh-cn'			=> '灵台县',
			'en'			=> 'ling tai xian',
		],
		'620823'	=> [
			'zh-cn'			=> '崇信县',
			'en'			=> 'chong xin xian',
		],
		'620824'	=> [
			'zh-cn'			=> '华亭县',
			'en'			=> 'hua ting xian',
		],
		'620825'	=> [
			'zh-cn'			=> '庄浪县',
			'en'			=> 'zhuang lang xian',
		],
		'620826'	=> [
			'zh-cn'			=> '静宁县',
			'en'			=> 'jing ning xian',
		],
		'620900'	=> [
			'zh-cn'			=> '酒泉市',
			'en'			=> 'jiu quan shi',
		],
		'620902'	=> [
			'zh-cn'			=> '肃州区',
			'en'			=> 'su zhou qu',
		],
		'620921'	=> [
			'zh-cn'			=> '金塔县',
			'en'			=> 'jin ta xian',
		],
		'620922'	=> [
			'zh-cn'			=> '瓜州县',
			'en'			=> 'gua zhou xian',
		],
		'620923'	=> [
			'zh-cn'			=> '肃北蒙古族自治县',
			'en'			=> 'su bei meng gu zu zi zhi xian',
		],
		'620924'	=> [
			'zh-cn'			=> '阿克塞哈萨克族自治县',
			'en'			=> 'a ke sai ha sa ke zu zi zhi xian',
		],
		'620925'	=> [
			'zh-cn'			=> '安西县',
			'en'			=> 'an xi xian',
		],
		'620981'	=> [
			'zh-cn'			=> '玉门市',
			'en'			=> 'yu men shi',
		],
		'620982'	=> [
			'zh-cn'			=> '敦煌市',
			'en'			=> 'dun huang shi',
		],
		'621000'	=> [
			'zh-cn'			=> '庆阳市',
			'en'			=> 'qing yang shi',
		],
		'621002'	=> [
			'zh-cn'			=> '西峰区',
			'en'			=> 'xi feng qu',
		],
		'621021'	=> [
			'zh-cn'			=> '庆城县',
			'en'			=> 'qing cheng xian',
		],
		'621023'	=> [
			'zh-cn'			=> '华池县',
			'en'			=> 'hua chi xian',
		],
		'621024'	=> [
			'zh-cn'			=> '合水县',
			'en'			=> 'he shui xian',
		],
		'621025'	=> [
			'zh-cn'			=> '正宁县',
			'en'			=> 'zheng ning xian',
		],
		'621027'	=> [
			'zh-cn'			=> '镇原县',
			'en'			=> 'zhen yuan xian',
		],
		'621100'	=> [
			'zh-cn'			=> '定西市',
			'en'			=> 'ding xi shi',
		],
		'621102'	=> [
			'zh-cn'			=> '安定区',
			'en'			=> 'an ding qu',
		],
		'621121'	=> [
			'zh-cn'			=> '通渭县',
			'en'			=> 'tong wei xian',
		],
		'621122'	=> [
			'zh-cn'			=> '陇西县',
			'en'			=> 'long xi xian',
		],
		'621123'	=> [
			'zh-cn'			=> '渭源县',
			'en'			=> 'wei yuan xian',
		],
		'621124'	=> [
			'zh-cn'			=> '临洮县',
			'en'			=> 'lin tao xian',
		],
		'621200'	=> [
			'zh-cn'			=> '陇南市',
			'en'			=> 'long nan shi',
		],
		'621202'	=> [
			'zh-cn'			=> '武都区',
			'en'			=> 'wu du qu',
		],
		'621223'	=> [
			'zh-cn'			=> '宕昌县',
			'en'			=> 'dang chang xian',
		],
		'621225'	=> [
			'zh-cn'			=> '西和县',
			'en'			=> 'xi he xian',
		],
		'621228'	=> [
			'zh-cn'			=> '两当县',
			'en'			=> 'liang dang xian',
		],
		'622100'	=> [
			'zh-cn'			=> '酒泉地区',
			'en'			=> 'jiu quan di qu',
		],
		'622101'	=> [
			'zh-cn'			=> '玉门市',
			'en'			=> 'yu men shi',
		],
		'622102'	=> [
			'zh-cn'			=> '酒泉市',
			'en'			=> 'jiu quan shi',
		],
		'622103'	=> [
			'zh-cn'			=> '敦煌市',
			'en'			=> 'dun huang shi',
		],
		'622121'	=> [
			'zh-cn'			=> '酒泉县',
			'en'			=> 'jiu quan xian',
		],
		'622122'	=> [
			'zh-cn'			=> '敦煌县',
			'en'			=> 'dun huang xian',
		],
		'622123'	=> [
			'zh-cn'			=> '金塔县',
			'en'			=> 'jin ta xian',
		],
		'622124'	=> [
			'zh-cn'			=> '肃北蒙古族自治县',
			'en'			=> 'su bei meng gu zu zi zhi xian',
		],
		'622125'	=> [
			'zh-cn'			=> '阿克塞哈萨克族自治县',
			'en'			=> 'a ke sai ha sa ke zu zi zhi xian',
		],
		'622126'	=> [
			'zh-cn'			=> '安西县',
			'en'			=> 'an xi xian',
		],
		'622200'	=> [
			'zh-cn'			=> '张掖地区',
			'en'			=> 'zhang ye di qu',
		],
		'622201'	=> [
			'zh-cn'			=> '张掖市',
			'en'			=> 'zhang ye shi',
		],
		'622221'	=> [
			'zh-cn'			=> '张掖县',
			'en'			=> 'zhang ye xian',
		],
		'622222'	=> [
			'zh-cn'			=> '肃南裕固族自治县',
			'en'			=> 'su nan yu gu zu zi zhi xian',
		],
		'622223'	=> [
			'zh-cn'			=> '民乐县',
			'en'			=> 'min yue xian',
		],
		'622224'	=> [
			'zh-cn'			=> '临泽县',
			'en'			=> 'lin ze xian',
		],
		'622225'	=> [
			'zh-cn'			=> '高台县',
			'en'			=> 'gao tai xian',
		],
		'622226'	=> [
			'zh-cn'			=> '山丹县',
			'en'			=> 'shan dan xian',
		],
		'622300'	=> [
			'zh-cn'			=> '武威地区',
			'en'			=> 'wu wei di qu',
		],
		'622301'	=> [
			'zh-cn'			=> '武威市',
			'en'			=> 'wu wei shi',
		],
		'622321'	=> [
			'zh-cn'			=> '武威县',
			'en'			=> 'wu wei xian',
		],
		'622322'	=> [
			'zh-cn'			=> '民勤县',
			'en'			=> 'min qin xian',
		],
		'622323'	=> [
			'zh-cn'			=> '古浪县',
			'en'			=> 'gu lang xian',
		],
		'622324'	=> [
			'zh-cn'			=> '景泰县',
			'en'			=> 'jing tai xian',
		],
		'622326'	=> [
			'zh-cn'			=> '天祝藏族自治县',
			'en'			=> 'tian zhu zang zu zi zhi xian',
		],
		'622400'	=> [
			'zh-cn'			=> '定西地区',
			'en'			=> 'ding xi di qu',
		],
		'622421'	=> [
			'zh-cn'			=> '定西县',
			'en'			=> 'ding xi xian',
		],
		'622422'	=> [
			'zh-cn'			=> '靖远县',
			'en'			=> 'jing yuan xian',
		],
		'622423'	=> [
			'zh-cn'			=> '会宁县',
			'en'			=> 'hui ning xian',
		],
		'622424'	=> [
			'zh-cn'			=> '通渭县',
			'en'			=> 'tong wei xian',
		],
		'622425'	=> [
			'zh-cn'			=> '陇西县',
			'en'			=> 'long xi xian',
		],
		'622426'	=> [
			'zh-cn'			=> '渭源县',
			'en'			=> 'wei yuan xian',
		],
		'622427'	=> [
			'zh-cn'			=> '临洮县',
			'en'			=> 'lin tao xian',
		],
		'622500'	=> [
			'zh-cn'			=> '天水地区',
			'en'			=> 'tian shui di qu',
		],
		'622501'	=> [
			'zh-cn'			=> '天水市',
			'en'			=> 'tian shui shi',
		],
		'622521'	=> [
			'zh-cn'			=> '张家川回族自治县',
			'en'			=> 'zhang jia chuan hui zu zi zhi xian',
		],
		'622522'	=> [
			'zh-cn'			=> '天水县',
			'en'			=> 'tian shui xian',
		],
		'622523'	=> [
			'zh-cn'			=> '清水县',
			'en'			=> 'qing shui xian',
		],
		'622525'	=> [
			'zh-cn'			=> '两当县',
			'en'			=> 'liang dang xian',
		],
		'622527'	=> [
			'zh-cn'			=> '西和县',
			'en'			=> 'xi he xian',
		],
		'622528'	=> [
			'zh-cn'			=> '武山县',
			'en'			=> 'wu shan xian',
		],
		'622529'	=> [
			'zh-cn'			=> '甘谷县',
			'en'			=> 'gan gu xian',
		],
		'622530'	=> [
			'zh-cn'			=> '秦安县',
			'en'			=> 'qin an xian',
		],
		'622600'	=> [
			'zh-cn'			=> '陇南地区',
			'en'			=> 'long nan di qu',
		],
		'622621'	=> [
			'zh-cn'			=> '武都县',
			'en'			=> 'wu du xian',
		],
		'622623'	=> [
			'zh-cn'			=> '宕昌县',
			'en'			=> 'dang chang xian',
		],
		'622627'	=> [
			'zh-cn'			=> '西和县',
			'en'			=> 'xi he xian',
		],
		'622629'	=> [
			'zh-cn'			=> '两当县',
			'en'			=> 'liang dang xian',
		],
		'622700'	=> [
			'zh-cn'			=> '平凉地区',
			'en'			=> 'ping liang di qu',
		],
		'622701'	=> [
			'zh-cn'			=> '平凉市',
			'en'			=> 'ping liang shi',
		],
		'622721'	=> [
			'zh-cn'			=> '平凉县',
			'en'			=> 'ping liang xian',
		],
		'622722'	=> [
			'zh-cn'			=> '泾川县',
			'en'			=> 'jing chuan xian',
		],
		'622723'	=> [
			'zh-cn'			=> '灵台县',
			'en'			=> 'ling tai xian',
		],
		'622724'	=> [
			'zh-cn'			=> '崇信县',
			'en'			=> 'chong xin xian',
		],
		'622725'	=> [
			'zh-cn'			=> '华亭县',
			'en'			=> 'hua ting xian',
		],
		'622726'	=> [
			'zh-cn'			=> '庄浪县',
			'en'			=> 'zhuang lang xian',
		],
		'622727'	=> [
			'zh-cn'			=> '静宁县',
			'en'			=> 'jing ning xian',
		],
		'622800'	=> [
			'zh-cn'			=> '庆阳地区',
			'en'			=> 'qing yang di qu',
		],
		'622801'	=> [
			'zh-cn'			=> '西峰市',
			'en'			=> 'xi feng shi',
		],
		'622821'	=> [
			'zh-cn'			=> '庆阳县',
			'en'			=> 'qing yang xian',
		],
		'622823'	=> [
			'zh-cn'			=> '华池县',
			'en'			=> 'hua chi xian',
		],
		'622824'	=> [
			'zh-cn'			=> '合水县',
			'en'			=> 'he shui xian',
		],
		'622825'	=> [
			'zh-cn'			=> '正宁县',
			'en'			=> 'zheng ning xian',
		],
		'622827'	=> [
			'zh-cn'			=> '镇原县',
			'en'			=> 'zhen yuan xian',
		],
		'622900'	=> [
			'zh-cn'			=> '临夏回族自治州',
			'en'			=> 'lin xia hui zu zi zhi zhou',
		],
		'622901'	=> [
			'zh-cn'			=> '临夏市',
			'en'			=> 'lin xia shi',
		],
		'622921'	=> [
			'zh-cn'			=> '临夏县',
			'en'			=> 'lin xia xian',
		],
		'622922'	=> [
			'zh-cn'			=> '康乐县',
			'en'			=> 'kang le xian',
		],
		'622923'	=> [
			'zh-cn'			=> '永靖县',
			'en'			=> 'yong jing xian',
		],
		'622924'	=> [
			'zh-cn'			=> '广河县',
			'en'			=> 'guang he xian',
		],
		'622925'	=> [
			'zh-cn'			=> '和政县',
			'en'			=> 'he zheng xian',
		],
		'622926'	=> [
			'zh-cn'			=> '东乡族自治县',
			'en'			=> 'dong xiang zu zi zhi xian',
		],
		'622927'	=> [
			'zh-cn'			=> '积石山保安族东乡族撒拉族自治县',
			'en'			=> 'ji shi shan bao an zu dong xiang zu sa la zu zi zhi xian',
		],
		'623000'	=> [
			'zh-cn'			=> '甘南藏族自治州',
			'en'			=> 'gan nan zang zu zi zhi zhou',
		],
		'623001'	=> [
			'zh-cn'			=> '合作市',
			'en'			=> 'he zuo shi',
		],
		'623021'	=> [
			'zh-cn'			=> '临潭县',
			'en'			=> 'lin tan xian',
		],
		'623022'	=> [
			'zh-cn'			=> '卓尼县',
			'en'			=> 'zhuo ni xian',
		],
		'623023'	=> [
			'zh-cn'			=> '舟曲县',
			'en'			=> 'zhou qu xian',
		],
		'623024'	=> [
			'zh-cn'			=> '迭部县',
			'en'			=> 'die bu xian',
		],
		'623025'	=> [
			'zh-cn'			=> '玛曲县',
			'en'			=> 'ma qu xian',
		],
		'623026'	=> [
			'zh-cn'			=> '碌曲县',
			'en'			=> 'lu qu xian',
		],
		'623027'	=> [
			'zh-cn'			=> '夏河县',
			'en'			=> 'xia he xian',
		],
		'630000'	=> [
			'zh-cn'			=> '青海省',
			'en'			=> 'qing hai sheng',
		],
		'630100'	=> [
			'zh-cn'			=> '西宁市',
			'en'			=> 'xi ning shi',
		],
		'630102'	=> [
			'zh-cn'			=> '城东区',
			'en'			=> 'cheng dong qu',
		],
		'630103'	=> [
			'zh-cn'			=> '城中区',
			'en'			=> 'cheng zhong qu',
		],
		'630104'	=> [
			'zh-cn'			=> '城西区',
			'en'			=> 'cheng xi qu',
		],
		'630105'	=> [
			'zh-cn'			=> '城北区',
			'en'			=> 'cheng bei qu',
		],
		'630121'	=> [
			'zh-cn'			=> '大通回族土族自治县',
			'en'			=> 'da tong hui zu tu zu zi zhi xian',
		],
		'630122'	=> [
			'zh-cn'			=> '湟中县',
			'en'			=> 'huang zhong xian',
		],
		'630123'	=> [
			'zh-cn'			=> '湟源县',
			'en'			=> 'huang yuan xian',
		],
		'630200'	=> [
			'zh-cn'			=> '海东市',
			'en'			=> 'hai dong shi',
		],
		'630202'	=> [
			'zh-cn'			=> '乐都区',
			'en'			=> 'le dou qu',
		],
		'630203'	=> [
			'zh-cn'			=> '平安区',
			'en'			=> 'ping an qu',
		],
		'630221'	=> [
			'zh-cn'			=> '平安县',
			'en'			=> 'ping an xian',
		],
		'630222'	=> [
			'zh-cn'			=> '民和回族土族自治县',
			'en'			=> 'min he hui zu tu zu zi zhi xian',
		],
		'630223'	=> [
			'zh-cn'			=> '互助土族自治县',
			'en'			=> 'hu zhu tu zu zi zhi xian',
		],
		'630224'	=> [
			'zh-cn'			=> '化隆回族自治县',
			'en'			=> 'hua long hui zu zi zhi xian',
		],
		'630225'	=> [
			'zh-cn'			=> '循化撒拉族自治县',
			'en'			=> 'xun hua sa la zu zi zhi xian',
		],
		'632100'	=> [
			'zh-cn'			=> '海东地区',
			'en'			=> 'hai dong di qu',
		],
		'632121'	=> [
			'zh-cn'			=> '平安县',
			'en'			=> 'ping an xian',
		],
		'632122'	=> [
			'zh-cn'			=> '民和回族土族自治县',
			'en'			=> 'min he hui zu tu zu zi zhi xian',
		],
		'632123'	=> [
			'zh-cn'			=> '乐都县',
			'en'			=> 'le du xian',
		],
		'632124'	=> [
			'zh-cn'			=> '湟中县',
			'en'			=> 'huang zhong xian',
		],
		'632125'	=> [
			'zh-cn'			=> '湟源县',
			'en'			=> 'huang yuan xian',
		],
		'632126'	=> [
			'zh-cn'			=> '互助土族自治县',
			'en'			=> 'hu zhu tu zu zi zhi xian',
		],
		'632127'	=> [
			'zh-cn'			=> '化隆回族自治县',
			'en'			=> 'hua long hui zu zi zhi xian',
		],
		'632128'	=> [
			'zh-cn'			=> '循化撒拉族自治县',
			'en'			=> 'xun hua sa la zu zi zhi xian',
		],
		'632200'	=> [
			'zh-cn'			=> '海北藏族自治州',
			'en'			=> 'hai bei zang zu zi zhi zhou',
		],
		'632221'	=> [
			'zh-cn'			=> '门源回族自治县',
			'en'			=> 'men yuan hui zu zi zhi xian',
		],
		'632222'	=> [
			'zh-cn'			=> '祁连县',
			'en'			=> 'qi lian xian',
		],
		'632223'	=> [
			'zh-cn'			=> '海晏县',
			'en'			=> 'hai yan xian',
		],
		'632224'	=> [
			'zh-cn'			=> '刚察县',
			'en'			=> 'gang cha xian',
		],
		'632300'	=> [
			'zh-cn'			=> '黄南藏族自治州',
			'en'			=> 'huang nan zang zu zi zhi zhou',
		],
		'632321'	=> [
			'zh-cn'			=> '同仁县',
			'en'			=> 'tong ren xian',
		],
		'632322'	=> [
			'zh-cn'			=> '尖扎县',
			'en'			=> 'jian zha xian',
		],
		'632323'	=> [
			'zh-cn'			=> '泽库县',
			'en'			=> 'ze ku xian',
		],
		'632324'	=> [
			'zh-cn'			=> '河南蒙古族自治县',
			'en'			=> 'he nan meng gu zu zi zhi xian',
		],
		'632500'	=> [
			'zh-cn'			=> '海南藏族自治州',
			'en'			=> 'hai nan zang zu zi zhi zhou',
		],
		'632521'	=> [
			'zh-cn'			=> '共和县',
			'en'			=> 'gong he xian',
		],
		'632522'	=> [
			'zh-cn'			=> '同德县',
			'en'			=> 'tong de xian',
		],
		'632523'	=> [
			'zh-cn'			=> '贵德县',
			'en'			=> 'gui de xian',
		],
		'632524'	=> [
			'zh-cn'			=> '兴海县',
			'en'			=> 'xing hai xian',
		],
		'632525'	=> [
			'zh-cn'			=> '贵南县',
			'en'			=> 'gui nan xian',
		],
		'632600'	=> [
			'zh-cn'			=> '果洛藏族自治州',
			'en'			=> 'guo luo zang zu zi zhi zhou',
		],
		'632621'	=> [
			'zh-cn'			=> '玛沁县',
			'en'			=> 'ma qin xian',
		],
		'632622'	=> [
			'zh-cn'			=> '班玛县',
			'en'			=> 'ban ma xian',
		],
		'632623'	=> [
			'zh-cn'			=> '甘德县',
			'en'			=> 'gan de xian',
		],
		'632624'	=> [
			'zh-cn'			=> '达日县',
			'en'			=> 'da ri xian',
		],
		'632625'	=> [
			'zh-cn'			=> '久治县',
			'en'			=> 'jiu zhi xian',
		],
		'632626'	=> [
			'zh-cn'			=> '玛多县',
			'en'			=> 'ma duo xian',
		],
		'632700'	=> [
			'zh-cn'			=> '玉树藏族自治州',
			'en'			=> 'yu shu zang zu zi zhi zhou',
		],
		'632701'	=> [
			'zh-cn'			=> '玉树市',
			'en'			=> 'yu shu shi',
		],
		'632721'	=> [
			'zh-cn'			=> '玉树县',
			'en'			=> 'yu shu xian',
		],
		'632722'	=> [
			'zh-cn'			=> '杂多县',
			'en'			=> 'za duo xian',
		],
		'632723'	=> [
			'zh-cn'			=> '称多县',
			'en'			=> 'chen duo xian',
		],
		'632724'	=> [
			'zh-cn'			=> '治多县',
			'en'			=> 'zhi duo xian',
		],
		'632725'	=> [
			'zh-cn'			=> '囊谦县',
			'en'			=> 'nang qian xian',
		],
		'632726'	=> [
			'zh-cn'			=> '曲麻莱县',
			'en'			=> 'qu ma lai xian',
		],
		'632800'	=> [
			'zh-cn'			=> '海西蒙古族藏族自治州',
			'en'			=> 'hai xi meng gu zu zang zu zi zhi zhou',
		],
		'632801'	=> [
			'zh-cn'			=> '格尔木市',
			'en'			=> 'ge er mu shi',
		],
		'632802'	=> [
			'zh-cn'			=> '德令哈市',
			'en'			=> 'de ling ha shi',
		],
		'632821'	=> [
			'zh-cn'			=> '乌兰县',
			'en'			=> 'wu lan xian',
		],
		'632822'	=> [
			'zh-cn'			=> '都兰县',
			'en'			=> 'du lan xian',
		],
		'632823'	=> [
			'zh-cn'			=> '天峻县',
			'en'			=> 'tian jun xian',
		],
		'640000'	=> [
			'zh-cn'			=> '宁夏回族自治区',
			'en'			=> 'ning xia hui zu zi zhi qu',
		],
		'640100'	=> [
			'zh-cn'			=> '银川市',
			'en'			=> 'yin chuan shi',
		],
		'640103'	=> [
			'zh-cn'			=> '新城区',
			'en'			=> 'xin cheng qu',
		],
		'640104'	=> [
			'zh-cn'			=> '兴庆区',
			'en'			=> 'xing qing qu',
		],
		'640105'	=> [
			'zh-cn'			=> '西夏区',
			'en'			=> 'xi xia qu',
		],
		'640106'	=> [
			'zh-cn'			=> '金凤区',
			'en'			=> 'jin feng qu',
		],
		'640121'	=> [
			'zh-cn'			=> '永宁县',
			'en'			=> 'yong ning xian',
		],
		'640122'	=> [
			'zh-cn'			=> '贺兰县',
			'en'			=> 'he lan xian',
		],
		'640181'	=> [
			'zh-cn'			=> '灵武市',
			'en'			=> 'ling wu shi',
		],
		'640200'	=> [
			'zh-cn'			=> '石嘴山市',
			'en'			=> 'shi zui shan shi',
		],
		'640202'	=> [
			'zh-cn'			=> '大武口区',
			'en'			=> 'da wu kou qu',
		],
		'640204'	=> [
			'zh-cn'			=> '石炭井区',
			'en'			=> 'shi tan jing qu',
		],
		'640205'	=> [
			'zh-cn'			=> '惠农区',
			'en'			=> 'hui nong qu',
		],
		'640221'	=> [
			'zh-cn'			=> '平罗县',
			'en'			=> 'ping luo xian',
		],
		'640222'	=> [
			'zh-cn'			=> '陶乐县',
			'en'			=> 'tao le xian',
		],
		'640223'	=> [
			'zh-cn'			=> '惠农县',
			'en'			=> 'hui nong xian',
		],
		'640300'	=> [
			'zh-cn'			=> '吴忠市',
			'en'			=> 'wu zhong shi',
		],
		'640302'	=> [
			'zh-cn'			=> '利通区',
			'en'			=> 'li tong qu',
		],
		'640303'	=> [
			'zh-cn'			=> '红寺堡区',
			'en'			=> 'hong si bao qu',
		],
		'640321'	=> [
			'zh-cn'			=> '中卫县',
			'en'			=> 'zhong wei xian',
		],
		'640322'	=> [
			'zh-cn'			=> '中宁县',
			'en'			=> 'zhong ning xian',
		],
		'640323'	=> [
			'zh-cn'			=> '盐池县',
			'en'			=> 'yan chi xian',
		],
		'640324'	=> [
			'zh-cn'			=> '同心县',
			'en'			=> 'tong xin xian',
		],
		'640381'	=> [
			'zh-cn'			=> '青铜峡市',
			'en'			=> 'qing tong xia shi',
		],
		'640382'	=> [
			'zh-cn'			=> '灵武市',
			'en'			=> 'ling wu shi',
		],
		'640400'	=> [
			'zh-cn'			=> '固原市',
			'en'			=> 'gu yuan shi',
		],
		'640402'	=> [
			'zh-cn'			=> '原州区',
			'en'			=> 'yuan zhou qu',
		],
		'640421'	=> [
			'zh-cn'			=> '海原县',
			'en'			=> 'hai yuan xian',
		],
		'640422'	=> [
			'zh-cn'			=> '西吉县',
			'en'			=> 'xi ji xian',
		],
		'640423'	=> [
			'zh-cn'			=> '隆德县',
			'en'			=> 'long de xian',
		],
		'640424'	=> [
			'zh-cn'			=> '泾源县',
			'en'			=> 'jing yuan xian',
		],
		'640425'	=> [
			'zh-cn'			=> '彭阳县',
			'en'			=> 'peng yang xian',
		],
		'640500'	=> [
			'zh-cn'			=> '中卫市',
			'en'			=> 'zhong wei shi',
		],
		'640502'	=> [
			'zh-cn'			=> '沙坡头区',
			'en'			=> 'sha po tou qu',
		],
		'640521'	=> [
			'zh-cn'			=> '中宁县',
			'en'			=> 'zhong ning xian',
		],
		'640522'	=> [
			'zh-cn'			=> '海原县',
			'en'			=> 'hai yuan xian',
		],
		'642100'	=> [
			'zh-cn'			=> '银南地区',
			'en'			=> 'yin nan di qu',
		],
		'642101'	=> [
			'zh-cn'			=> '吴忠市',
			'en'			=> 'wu zhong shi',
		],
		'642102'	=> [
			'zh-cn'			=> '青铜峡市',
			'en'			=> 'qing tong xia shi',
		],
		'642103'	=> [
			'zh-cn'			=> '灵武市',
			'en'			=> 'ling wu shi',
		],
		'642121'	=> [
			'zh-cn'			=> '吴忠县',
			'en'			=> 'wu zhong xian',
		],
		'642122'	=> [
			'zh-cn'			=> '青铜峡县',
			'en'			=> 'qing tong xia xian',
		],
		'642123'	=> [
			'zh-cn'			=> '中卫县',
			'en'			=> 'zhong wei xian',
		],
		'642124'	=> [
			'zh-cn'			=> '中宁县',
			'en'			=> 'zhong ning xian',
		],
		'642125'	=> [
			'zh-cn'			=> '灵武县',
			'en'			=> 'ling wu xian',
		],
		'642126'	=> [
			'zh-cn'			=> '盐池县',
			'en'			=> 'yan chi xian',
		],
		'642127'	=> [
			'zh-cn'			=> '同心县',
			'en'			=> 'tong xin xian',
		],
		'642200'	=> [
			'zh-cn'			=> '固原地区',
			'en'			=> 'gu yuan di qu',
		],
		'642221'	=> [
			'zh-cn'			=> '固原县',
			'en'			=> 'gu yuan xian',
		],
		'642222'	=> [
			'zh-cn'			=> '海原县',
			'en'			=> 'hai yuan xian',
		],
		'642223'	=> [
			'zh-cn'			=> '西吉县',
			'en'			=> 'xi ji xian',
		],
		'642224'	=> [
			'zh-cn'			=> '隆德县',
			'en'			=> 'long de xian',
		],
		'642225'	=> [
			'zh-cn'			=> '泾源县',
			'en'			=> 'jing yuan xian',
		],
		'642226'	=> [
			'zh-cn'			=> '彭阳县',
			'en'			=> 'peng yang xian',
		],
		'650000'	=> [
			'zh-cn'			=> '新疆维吾尔自治区',
			'en'			=> 'xin jiang wei wu er zi zhi qu',
		],
		'650100'	=> [
			'zh-cn'			=> '乌鲁木齐市',
			'en'			=> 'wu lu mu qi shi',
		],
		'650102'	=> [
			'zh-cn'			=> '天山区',
			'en'			=> 'tian shan qu',
		],
		'650103'	=> [
			'zh-cn'			=> '沙依巴克区',
			'en'			=> 'sha yi ba ke qu',
		],
		'650104'	=> [
			'zh-cn'			=> '新市区',
			'en'			=> 'xin shi qu',
		],
		'650105'	=> [
			'zh-cn'			=> '水磨沟区',
			'en'			=> 'shui mo gou qu',
		],
		'650106'	=> [
			'zh-cn'			=> '头屯河区',
			'en'			=> 'tou tun he qu',
		],
		'650107'	=> [
			'zh-cn'			=> '达坂城区',
			'en'			=> 'da ban cheng qu',
		],
		'650108'	=> [
			'zh-cn'			=> '东山区',
			'en'			=> 'dong shan qu',
		],
		'650109'	=> [
			'zh-cn'			=> '米东区',
			'en'			=> 'mi dong qu',
		],
		'650121'	=> [
			'zh-cn'			=> '乌鲁木齐县',
			'en'			=> 'wu lu mu qi xian',
		],
		'650200'	=> [
			'zh-cn'			=> '克拉玛依市',
			'en'			=> 'ke la ma yi shi',
		],
		'650202'	=> [
			'zh-cn'			=> '独山子区',
			'en'			=> 'du shan zi qu',
		],
		'650203'	=> [
			'zh-cn'			=> '克拉玛依区',
			'en'			=> 'ke la ma yi qu',
		],
		'650204'	=> [
			'zh-cn'			=> '白碱滩区',
			'en'			=> 'bai jian tan qu',
		],
		'650205'	=> [
			'zh-cn'			=> '乌尔禾区',
			'en'			=> 'wu er he qu',
		],
		'650300'	=> [
			'zh-cn'			=> '石河子市',
			'en'			=> 'shi he zi shi',
		],
		'650400'	=> [
			'zh-cn'			=> '吐鲁番市',
			'en'			=> 'tu lu fan shi',
		],
		'650402'	=> [
			'zh-cn'			=> '高昌区',
			'en'			=> 'gao chang qu',
		],
		'650421'	=> [
			'zh-cn'			=> '鄯善县',
			'en'			=> 'shan shan xian',
		],
		'650422'	=> [
			'zh-cn'			=> '托克逊县',
			'en'			=> 'tuo ke xun xian',
		],
		'650500'	=> [
			'zh-cn'			=> '哈密市',
			'en'			=> 'ha mi shi',
		],
		'650502'	=> [
			'zh-cn'			=> '伊州区',
			'en'			=> 'yi zhou qu',
		],
		'650521'	=> [
			'zh-cn'			=> '巴里坤哈萨克自治县',
			'en'			=> 'ba li kun ha sa ke zi zhi xian',
		],
		'650522'	=> [
			'zh-cn'			=> '伊吾县',
			'en'			=> 'yi wu xian',
		],
		'652100'	=> [
			'zh-cn'			=> '吐鲁番地区',
			'en'			=> 'tu lu fan di qu',
		],
		'652101'	=> [
			'zh-cn'			=> '吐鲁番市',
			'en'			=> 'tu lu fan shi',
		],
		'652121'	=> [
			'zh-cn'			=> '吐鲁番县',
			'en'			=> 'tu lu fan xian',
		],
		'652122'	=> [
			'zh-cn'			=> '鄯善县',
			'en'			=> 'shan shan xian',
		],
		'652123'	=> [
			'zh-cn'			=> '托克逊县',
			'en'			=> 'tuo ke xun xian',
		],
		'652200'	=> [
			'zh-cn'			=> '哈密地区',
			'en'			=> 'ha mi di qu',
		],
		'652201'	=> [
			'zh-cn'			=> '哈密市',
			'en'			=> 'ha mi shi',
		],
		'652221'	=> [
			'zh-cn'			=> '哈密县',
			'en'			=> 'ha mi xian',
		],
		'652222'	=> [
			'zh-cn'			=> '巴里坤哈萨克自治县',
			'en'			=> 'ba li kun ha sa ke zi zhi xian',
		],
		'652223'	=> [
			'zh-cn'			=> '伊吾县',
			'en'			=> 'yi wu xian',
		],
		'652300'	=> [
			'zh-cn'			=> '昌吉回族自治州',
			'en'			=> 'chang ji hui zu zi zhi zhou',
		],
		'652301'	=> [
			'zh-cn'			=> '昌吉市',
			'en'			=> 'chang ji shi',
		],
		'652302'	=> [
			'zh-cn'			=> '阜康市',
			'en'			=> 'fu kang shi',
		],
		'652303'	=> [
			'zh-cn'			=> '米泉市',
			'en'			=> 'mi quan shi',
		],
		'652321'	=> [
			'zh-cn'			=> '昌吉县',
			'en'			=> 'chang ji xian',
		],
		'652322'	=> [
			'zh-cn'			=> '米泉县',
			'en'			=> 'mi quan xian',
		],
		'652323'	=> [
			'zh-cn'			=> '呼图壁县',
			'en'			=> 'hu tu bi xian',
		],
		'652324'	=> [
			'zh-cn'			=> '玛纳斯县',
			'en'			=> 'ma na si xian',
		],
		'652325'	=> [
			'zh-cn'			=> '奇台县',
			'en'			=> 'qi tai xian',
		],
		'652326'	=> [
			'zh-cn'			=> '阜康县',
			'en'			=> 'fu kang xian',
		],
		'652327'	=> [
			'zh-cn'			=> '吉木萨尔县',
			'en'			=> 'ji mu sa er xian',
		],
		'652328'	=> [
			'zh-cn'			=> '木垒哈萨克自治县',
			'en'			=> 'mu lei ha sa ke zi zhi xian',
		],
		'652400'	=> [
			'zh-cn'			=> '伊犁哈萨克自治州',
			'en'			=> 'yi li ha sa ke zi zhi zhou',
		],
		'652401'	=> [
			'zh-cn'			=> '伊宁市',
			'en'			=> 'yi ning shi',
		],
		'652402'	=> [
			'zh-cn'			=> '奎屯市',
			'en'			=> 'kui tun shi',
		],
		'652404'	=> [
			'zh-cn'			=> '奎屯市',
			'en'			=> 'kui tun shi',
		],
		'652421'	=> [
			'zh-cn'			=> '伊宁县',
			'en'			=> 'yi ning xian',
		],
		'652422'	=> [
			'zh-cn'			=> '察布查尔锡伯自治县',
			'en'			=> 'cha bu cha er xi bo zi zhi xian',
		],
		'652423'	=> [
			'zh-cn'			=> '霍城县',
			'en'			=> 'huo cheng xian',
		],
		'652424'	=> [
			'zh-cn'			=> '巩留县',
			'en'			=> 'gong liu xian',
		],
		'652425'	=> [
			'zh-cn'			=> '新源县',
			'en'			=> 'xin yuan xian',
		],
		'652426'	=> [
			'zh-cn'			=> '昭苏县',
			'en'			=> 'zhao su xian',
		],
		'652427'	=> [
			'zh-cn'			=> '特克斯县',
			'en'			=> 'te ke si xian',
		],
		'652428'	=> [
			'zh-cn'			=> '尼勒克县',
			'en'			=> 'ni le ke xian',
		],
		'652500'	=> [
			'zh-cn'			=> '塔城地区',
			'en'			=> 'ta cheng di qu',
		],
		'652521'	=> [
			'zh-cn'			=> '塔城县',
			'en'			=> 'ta cheng xian',
		],
		'652522'	=> [
			'zh-cn'			=> '额敏县',
			'en'			=> 'e min xian',
		],
		'652523'	=> [
			'zh-cn'			=> '乌苏县',
			'en'			=> 'wu su xian',
		],
		'652524'	=> [
			'zh-cn'			=> '沙湾县',
			'en'			=> 'sha wan xian',
		],
		'652525'	=> [
			'zh-cn'			=> '托里县',
			'en'			=> 'tuo li xian',
		],
		'652526'	=> [
			'zh-cn'			=> '裕民县',
			'en'			=> 'yu min xian',
		],
		'652527'	=> [
			'zh-cn'			=> '和布克赛尔蒙古自治县',
			'en'			=> 'he bu ke sai er meng gu zi zhi xian',
		],
		'652600'	=> [
			'zh-cn'			=> '阿勒泰地区',
			'en'			=> 'a le tai di qu',
		],
		'652621'	=> [
			'zh-cn'			=> '阿勒泰县',
			'en'			=> 'a le tai xian',
		],
		'652622'	=> [
			'zh-cn'			=> '布尔津县',
			'en'			=> 'bu er jin xian',
		],
		'652623'	=> [
			'zh-cn'			=> '富蕴县',
			'en'			=> 'fu yun xian',
		],
		'652624'	=> [
			'zh-cn'			=> '福海县',
			'en'			=> 'fu hai xian',
		],
		'652625'	=> [
			'zh-cn'			=> '哈巴河县',
			'en'			=> 'ha ba he xian',
		],
		'652626'	=> [
			'zh-cn'			=> '青河县',
			'en'			=> 'qing he xian',
		],
		'652627'	=> [
			'zh-cn'			=> '吉木乃县',
			'en'			=> 'ji mu nai xian',
		],
		'652700'	=> [
			'zh-cn'			=> '博尔塔拉蒙古自治州',
			'en'			=> 'bo er ta la meng gu zi zhi zhou',
		],
		'652701'	=> [
			'zh-cn'			=> '博乐市',
			'en'			=> 'bo le shi',
		],
		'652702'	=> [
			'zh-cn'			=> '阿拉山口市',
			'en'			=> 'a la shan kou shi',
		],
		'652721'	=> [
			'zh-cn'			=> '博乐县',
			'en'			=> 'bo le xian',
		],
		'652722'	=> [
			'zh-cn'			=> '精河县',
			'en'			=> 'jing he xian',
		],
		'652723'	=> [
			'zh-cn'			=> '温泉县',
			'en'			=> 'wen quan xian',
		],
		'652800'	=> [
			'zh-cn'			=> '巴音郭楞蒙古自治州',
			'en'			=> 'ba yin guo leng meng gu zi zhi zhou',
		],
		'652801'	=> [
			'zh-cn'			=> '库尔勒市',
			'en'			=> 'ku er le shi',
		],
		'652821'	=> [
			'zh-cn'			=> '库尔勒县',
			'en'			=> 'ku er le xian',
		],
		'652822'	=> [
			'zh-cn'			=> '轮台县',
			'en'			=> 'lun tai xian',
		],
		'652823'	=> [
			'zh-cn'			=> '尉犁县',
			'en'			=> 'yu li xian',
		],
		'652824'	=> [
			'zh-cn'			=> '若羌县',
			'en'			=> 'ruo qiang xian',
		],
		'652825'	=> [
			'zh-cn'			=> '且末县',
			'en'			=> 'qie mo xian',
		],
		'652826'	=> [
			'zh-cn'			=> '焉耆回族自治县',
			'en'			=> 'yan qi hui zu zi zhi xian',
		],
		'652827'	=> [
			'zh-cn'			=> '和静县',
			'en'			=> 'he jing xian',
		],
		'652828'	=> [
			'zh-cn'			=> '和硕县',
			'en'			=> 'he shuo xian',
		],
		'652829'	=> [
			'zh-cn'			=> '博湖县',
			'en'			=> 'bo hu xian',
		],
		'652900'	=> [
			'zh-cn'			=> '阿克苏地区',
			'en'			=> 'a ke su di qu',
		],
		'652901'	=> [
			'zh-cn'			=> '阿克苏市',
			'en'			=> 'a ke su shi',
		],
		'652921'	=> [
			'zh-cn'			=> '阿克苏县',
			'en'			=> 'a ke su xian',
		],
		'652922'	=> [
			'zh-cn'			=> '温宿县',
			'en'			=> 'wen su xian',
		],
		'652923'	=> [
			'zh-cn'			=> '库车县',
			'en'			=> 'ku che xian',
		],
		'652924'	=> [
			'zh-cn'			=> '沙雅县',
			'en'			=> 'sha ya xian',
		],
		'652925'	=> [
			'zh-cn'			=> '新和县',
			'en'			=> 'xin he xian',
		],
		'652926'	=> [
			'zh-cn'			=> '拜城县',
			'en'			=> 'bai cheng xian',
		],
		'652927'	=> [
			'zh-cn'			=> '乌什县',
			'en'			=> 'wu shi xian',
		],
		'652928'	=> [
			'zh-cn'			=> '阿瓦提县',
			'en'			=> 'a wa ti xian',
		],
		'652929'	=> [
			'zh-cn'			=> '柯坪县',
			'en'			=> 'ke ping xian',
		],
		'653000'	=> [
			'zh-cn'			=> '克孜勒苏柯尔克孜自治州',
			'en'			=> 'ke zi le su ke er ke zi zi zhi zhou',
		],
		'653001'	=> [
			'zh-cn'			=> '阿图什市',
			'en'			=> 'a tu shi shi',
		],
		'653021'	=> [
			'zh-cn'			=> '阿图什县',
			'en'			=> 'a tu shi xian',
		],
		'653022'	=> [
			'zh-cn'			=> '阿克陶县',
			'en'			=> 'a ke tao xian',
		],
		'653023'	=> [
			'zh-cn'			=> '阿合奇县',
			'en'			=> 'a he qi xian',
		],
		'653024'	=> [
			'zh-cn'			=> '乌恰县',
			'en'			=> 'wu qia xian',
		],
		'653100'	=> [
			'zh-cn'			=> '喀什地区',
			'en'			=> 'ka shi di qu',
		],
		'653101'	=> [
			'zh-cn'			=> '喀什市',
			'en'			=> 'ka shi shi',
		],
		'653121'	=> [
			'zh-cn'			=> '疏附县',
			'en'			=> 'shu fu xian',
		],
		'653122'	=> [
			'zh-cn'			=> '疏勒县',
			'en'			=> 'shu le xian',
		],
		'653123'	=> [
			'zh-cn'			=> '英吉沙县',
			'en'			=> 'ying ji sha xian',
		],
		'653124'	=> [
			'zh-cn'			=> '泽普县',
			'en'			=> 'ze pu xian',
		],
		'653125'	=> [
			'zh-cn'			=> '莎车县',
			'en'			=> 'sha che xian',
		],
		'653126'	=> [
			'zh-cn'			=> '叶城县',
			'en'			=> 'ye cheng xian',
		],
		'653127'	=> [
			'zh-cn'			=> '麦盖提县',
			'en'			=> 'mai ge ti xian',
		],
		'653128'	=> [
			'zh-cn'			=> '岳普湖县',
			'en'			=> 'yue pu hu xian',
		],
		'653129'	=> [
			'zh-cn'			=> '伽师县',
			'en'			=> 'jia shi xian',
		],
		'653130'	=> [
			'zh-cn'			=> '巴楚县',
			'en'			=> 'ba chu xian',
		],
		'653131'	=> [
			'zh-cn'			=> '塔什库尔干塔吉克自治县',
			'en'			=> 'ta shen ku er gan ta ji ke zi zhi xian',
		],
		'653200'	=> [
			'zh-cn'			=> '和田地区',
			'en'			=> 'he tian di qu',
		],
		'653201'	=> [
			'zh-cn'			=> '和田市',
			'en'			=> 'he tian shi',
		],
		'653221'	=> [
			'zh-cn'			=> '和田县',
			'en'			=> 'he tian xian',
		],
		'653222'	=> [
			'zh-cn'			=> '墨玉县',
			'en'			=> 'mo yu xian',
		],
		'653223'	=> [
			'zh-cn'			=> '皮山县',
			'en'			=> 'pi shan xian',
		],
		'653224'	=> [
			'zh-cn'			=> '洛浦县',
			'en'			=> 'luo pu xian',
		],
		'653225'	=> [
			'zh-cn'			=> '策勒县',
			'en'			=> 'ce le xian',
		],
		'653226'	=> [
			'zh-cn'			=> '于田县',
			'en'			=> 'yu tian xian',
		],
		'653227'	=> [
			'zh-cn'			=> '民丰县',
			'en'			=> 'min feng xian',
		],
		'654000'	=> [
			'zh-cn'			=> '伊犁哈萨克自治州',
			'en'			=> 'yi li ha sa ke zi zhi zhou',
		],
		'654001'	=> [
			'zh-cn'			=> '奎屯市',
			'en'			=> 'kui tun shi',
		],
		'654002'	=> [
			'zh-cn'			=> '伊宁市',
			'en'			=> 'yi ning shi',
		],
		'654003'	=> [
			'zh-cn'			=> '奎屯市',
			'en'			=> 'kui tun shi',
		],
		'654004'	=> [
			'zh-cn'			=> '霍尔果斯市',
			'en'			=> 'huo er guo si shi',
		],
		'654021'	=> [
			'zh-cn'			=> '伊宁县',
			'en'			=> 'yi ning xian',
		],
		'654022'	=> [
			'zh-cn'			=> '察布查尔锡伯自治县',
			'en'			=> 'cha bu cha er xi bo zi zhi xian',
		],
		'654023'	=> [
			'zh-cn'			=> '霍城县',
			'en'			=> 'huo cheng xian',
		],
		'654024'	=> [
			'zh-cn'			=> '巩留县',
			'en'			=> 'gong liu xian',
		],
		'654025'	=> [
			'zh-cn'			=> '新源县',
			'en'			=> 'xin yuan xian',
		],
		'654026'	=> [
			'zh-cn'			=> '昭苏县',
			'en'			=> 'zhao su xian',
		],
		'654027'	=> [
			'zh-cn'			=> '特克斯县',
			'en'			=> 'te ke si xian',
		],
		'654028'	=> [
			'zh-cn'			=> '尼勒克县',
			'en'			=> 'ni le ke xian',
		],
		'654100'	=> [
			'zh-cn'			=> '伊犁地区',
			'en'			=> 'yi li di qu',
		],
		'654101'	=> [
			'zh-cn'			=> '伊宁市',
			'en'			=> 'yi ning shi',
		],
		'654121'	=> [
			'zh-cn'			=> '伊宁县',
			'en'			=> 'yi ning xian',
		],
		'654122'	=> [
			'zh-cn'			=> '察布查尔锡伯自治县',
			'en'			=> 'cha bu cha er xi bo zi zhi xian',
		],
		'654123'	=> [
			'zh-cn'			=> '霍城县',
			'en'			=> 'huo cheng xian',
		],
		'654124'	=> [
			'zh-cn'			=> '巩留县',
			'en'			=> 'gong liu xian',
		],
		'654125'	=> [
			'zh-cn'			=> '新源县',
			'en'			=> 'xin yuan xian',
		],
		'654126'	=> [
			'zh-cn'			=> '昭苏县',
			'en'			=> 'zhao su xian',
		],
		'654127'	=> [
			'zh-cn'			=> '特克斯县',
			'en'			=> 'te ke si xian',
		],
		'654128'	=> [
			'zh-cn'			=> '尼勒克县',
			'en'			=> 'ni le ke xian',
		],
		'654200'	=> [
			'zh-cn'			=> '塔城地区',
			'en'			=> 'ta cheng di qu',
		],
		'654201'	=> [
			'zh-cn'			=> '塔城市',
			'en'			=> 'ta cheng shi',
		],
		'654202'	=> [
			'zh-cn'			=> '乌苏市',
			'en'			=> 'wu su shi',
		],
		'654221'	=> [
			'zh-cn'			=> '额敏县',
			'en'			=> 'e min xian',
		],
		'654222'	=> [
			'zh-cn'			=> '乌苏县',
			'en'			=> 'wu su xian',
		],
		'654223'	=> [
			'zh-cn'			=> '沙湾县',
			'en'			=> 'sha wan xian',
		],
		'654224'	=> [
			'zh-cn'			=> '托里县',
			'en'			=> 'tuo li xian',
		],
		'654225'	=> [
			'zh-cn'			=> '裕民县',
			'en'			=> 'yu min xian',
		],
		'654226'	=> [
			'zh-cn'			=> '和布克赛尔蒙古自治县',
			'en'			=> 'he bu ke sai er meng gu zi zhi xian',
		],
		'654300'	=> [
			'zh-cn'			=> '阿勒泰地区',
			'en'			=> 'a le tai di qu',
		],
		'654301'	=> [
			'zh-cn'			=> '阿勒泰市',
			'en'			=> 'a le tai shi',
		],
		'654321'	=> [
			'zh-cn'			=> '布尔津县',
			'en'			=> 'bu er jin xian',
		],
		'654322'	=> [
			'zh-cn'			=> '富蕴县',
			'en'			=> 'fu yun xian',
		],
		'654323'	=> [
			'zh-cn'			=> '福海县',
			'en'			=> 'fu hai xian',
		],
		'654324'	=> [
			'zh-cn'			=> '哈巴河县',
			'en'			=> 'ha ba he xian',
		],
		'654325'	=> [
			'zh-cn'			=> '青河县',
			'en'			=> 'qing he xian',
		],
		'654326'	=> [
			'zh-cn'			=> '吉木乃县',
			'en'			=> 'ji mu nai xian',
		],
		'659001'	=> [
			'zh-cn'			=> '石河子市',
			'en'			=> 'shi he zi shi',
		],
		'659002'	=> [
			'zh-cn'			=> '阿拉尔市',
			'en'			=> 'a la er shi',
		],
		'659003'	=> [
			'zh-cn'			=> '图木舒克市',
			'en'			=> 'tu mu shu ke shi',
		],
		'659004'	=> [
			'zh-cn'			=> '五家渠市',
			'en'			=> 'wu jia qu shi',
		],
		'659005'	=> [
			'zh-cn'			=> '北屯市',
			'en'			=> 'bei tun shi',
		],
		'659006'	=> [
			'zh-cn'			=> '铁门关市',
			'en'			=> 'tie men guan shi',
		],
		'659007'	=> [
			'zh-cn'			=> '双河市',
			'en'			=> 'shuang he shi',
		],
		'659008'	=> [
			'zh-cn'			=> '可克达拉市',
			'en'			=> 'ke ke da la shi',
		],
		'659009'	=> [
			'zh-cn'			=> '昆玉市',
			'en'			=> 'kun yu shi',
		],
		'710000'	=> [
			'zh-cn'			=> '台湾省',
			'en'			=> 'tai wan sheng',
		],
		'810000'	=> [
			'zh-cn'			=> '香港特别行政区',
			'en'			=> 'xiang gang te bie xing zheng qu',
		],
		'820000'	=> [
			'zh-cn'			=> '澳门特别行政区',
			'en'			=> 'ao men te bie xing zheng qu',
		]
	];

	/**
	 * Stop building an ID card instance.
	 *
	 * @return  void
	 *
	 * @throws  InvalidArgumentException
	 */
	protected function __construct()
	{
	}

	/**
	 * Get region with ID card.
	 *
	 * @return  array
	 */
	public function getArea()
	{
		return "{$this->getProvince()} {$this->getCity()} {$this->getCounty()}";
	}

	/**
	 * Get the province.
	 *
	 * @return  string|null
	 */
	public function getProvince() : ?string
	{
		$k = substr(static::$idCard, 0, 2) . '0000';

		if ( ! isset($this->regions[$k]) )
		{
			return null;
		}

		return $this->regions[$k][static::$locale];
	}

	/**
	 * Get the city.
	 *
	 * @return  string|null
	 */
	public function getCity() : ?string
	{
		$k = substr(static::$idCard, 0, 4) . '00';

		if ( ! isset($this->regions[$k]) )
		{
			return null;
		}

		return $this->regions[$k][static::$locale];
	}

	/**
	 * Get the county.
	 *
	 * @return  string|null
	 */
	public function getCounty() : ?string
	{
		$k = substr(static::$idCard, 0, 6);

		if ( ! isset($this->regions[$k]) )
		{
			return null;
		}

		return $this->regions[$k][static::$locale];
	}

	/**
	 * Get the user gender.
	 *
	 * @return  string
	 */
	public function getGender() : string
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
	 * @param		string		$format		Dateformat Default example: 'Y-m-d'
	 *
	 * @return  string
	 */
	public function getBirthday(string $format = 'Y-m-d') : string
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
	public function getAge() : int
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
	public function getZodiac() : string
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
	public function getConstellation() : string
	{
		$month = (int) substr(static::$idCard, 10, 2);

		$month = $month - 1;

		$day = (int) substr(static::$idCard, 12, 2);

		if ( $day < $this->constellationEdgeDays[$month] )
		{
			$month = $month - 1;
		}

		if ( $month > 0 )
		{
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
	public function toJson(int $options = 0) : string
	{
		return json_encode($this->toArray(), $options);
	}

	/**
	 * Get the personal information of item as a plain array.
	 *
	 * @return  array
	 */
	public function toArray() : array
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