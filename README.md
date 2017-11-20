
<p align="center"><img src="id-card.svg"></p>

一个简单的身份证号码获取用户信息工具
------------------------

>  中国（大陆地区）公民身份证，数据来源于国家标准GB/T 2260-2007 <a href="http://www.stats.gov.cn/tjsj/tjbz/xzqhdm/" target="blank">（中华人民共和国行政区划代码)</a>


## 安装

```bash

    composer require anomalylab/component-identity-card
```


## 说明
一个基于中华人民共和国公民身份证的组件可以获取用户信息。这个适用于任何php框架，但是只有当php版本>=7.1时才可以。
<br>
<a href="README_en.md">English Documentation</a>

## 使用

#### 验证你的身份证号码
```php

    try {
        Anomaly\Component\IdentityCard\IdentityCard::make('32010619831029081');
        
        return true;
    }
    catch (\Exception $e)
    {
        print_r($e->getMessage());
        return false;
    }

```

#### 或运行测试文件
```bash
    php test
```


```php

    try {
        $idCard = Anomaly\Component\IdentityCard\IdentityCard::make('320106198310290811');
        //  Use locale, Current supported zh-cn,en
        // $idCard = Anomaly\Component\IdentityCard\IdentityCard::make('320106198310290811', 'zh-cn');
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


#### 返回结果:
```json

{
    "area": "Jiang 南京市 鼓楼区",
    "gender": "male",
    "birthday": "1983-10-29",
    "age": 34,
    "constellation": "天蝎座"
}
```


## Api
- getArea():string `获取地区`
- getConstellation():string `获取星座`
- getAge():int `获取年龄`
- getBirthday(string $foramt = 'Y-m-d'):string `获取生日`
- getGender():string `获取性别`
- getCounty():string|null `获取县城`
- getCity():string|null `获取城市`
- getProvince():string|null `获取省`
- toArray():array `全部信息`
- toJson(int $option):string `全部信息`
