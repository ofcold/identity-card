
<p align="center"><img src="id-card.svg"></p>

China (region) citizen ID card tool
------------------------

>  China (Mainland) ID card package, the data from the national standard `GB/T 2260-2007`  <a href="http://www.stats.gov.cn/tjsj/tjbz/xzqhdm/" target="blank"> (People's Republic of China administrative divisions code standard).</a>

## Installing

```bash

    composer require ofcold/identity-card
```


## Instructions
A component based on People's Republic of China citizen ID card to obtain the user information.This works for any php framework, but only if the php version is greater than 7.1.

## Useing

#### Verify your Chinese ID card
```php

    try {
        $idCard = Ofcold\IdentityCard\IdentityCard::make('32010619831029081');
        print_r($idCard->toArray());
    }
    catch (\Exception $e)
    {
        print_r($e->getMessage());
    }


```

#### OR test file.
```bash
    php test
```


```php

    try {
        $idCard = Ofcold\IdentityCard\IdentityCard::make('320106198310290811', 'en');
        //  Use locale, Current supported zh-cn,en
        // $idCard = Ofcold\IdentityCard\IdentityCard::make('320106198310290811', 'zh-cn');
        $area = $idCard->getArea();
        $gender = $idCard->getGender();
        $birthday = $idCard->getBirthday();
        $age = $idCard->getAge();
        $constellation = $idCard->getConstellation();
    }
    catch (\Exception $e)
    {
        $e->getMessage();
    }



```


#### Results:
```json

{
    "area": "Jiangsu Nanjing Gulouqu",
    "gender": "male",
    "birthday": "1983-10-29",
    "age": 34,
    "constellation": "Scorpio"
}
```

### Api
- getArea():string `Get Area`
- getConstellation():string `Get constellation`
- getAge():int `Get age`
- getBirthday(string $foramt = 'Y-m-d'):string `Get birthday`
- getGender():string `Get gender`
- getCounty():string|null `Get county`
- getCity():string|null `Get city`
- getProvince():string|null `Get province`
- toArray():array `Get all information.`
- toJson(int $option):string `Json format all information`
