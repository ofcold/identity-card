
China (region) citizen ID card tool
------------------------

>  China (Mainland) ID card package, the data from the national standard `GB / T 2260-2007` (People's Republic of China administrative divisions code standard).  

### Installing

```bash

    composer require anomalylab/component-identity-card
```


### Instructions

```php

    $idCard = Anomaly\Component\IdentityCard\IdentityCard::make('320106198310290811');

    $area = $idCard->getArea();
    $gender = $idCard->getGender();
    $birthday = $idCard->getBirthday();
    $age = $idCard->getAge();
    $constellation = $idCard->getConstellation();


```

Results:
```json

{
    "area": "江苏省 南京市 鼓楼区",
    "gender": "male",
    "birthday": "1983-10-29",
    "age": 34,
    "constellation": "天蝎座"
}
```
