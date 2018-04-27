# Money
Класс Money должен использоваться для денежных рассчётов.
Деньги должны задаваться в минорных денежных единицах (копейках, центах, пенни и.т.д)

## Доступные операции:
**create** - **создание**
```php
$money = Money::create(100);
```

**getAmount** - **получение суммы в минорных единицах**
```php
$result = Money::create(100)->getAmount(); // $result = 100
$result = Money::create(100.99)->getAmount(); // $result = 100
```

### Сравнение

**equal** - сравнение **равно**
```php
$result = Money::create(100)->equal(Money::create(100)); // $result = true
```

**equalOrLess** - сравнение **меньше или равно**
```php
$result = Money::create(100)->equalOrLess(Money::create(1000)); // $result = true
$result = Money::create(100)->equalOrLess(Money::create(100)); // $result = true
$result = Money::create(1000)->equalOrLess(Money::create(100)); // $result = false
```

**equalOrMore** - сравнение **больше или равно**
```php
$result = Money::create(100)->equalOrMore(Money::create(1000)); // $result = flase
$result = Money::create(100)->equalOrMore(Money::create(100)); // $result = true
$result = Money::create(1000)->equalOrMore(Money::create(100)); // $result = true
```

**less** - сравнение **меньше**
```php
$result = Money::create(100)->less(Money::create(1000)); // $result = true
$result = Money::create(100)->less(Money::create(100)); // $result = false
$result = Money::create(1000)->less(Money::create(100)); // $result = false
```

**more** - сравнение **больше**
```php
$result = Money::create(100)->more(Money::create(1000)); // $result = false
$result = Money::create(100)->more(Money::create(100)); // $result = false
$result = Money::create(1000)->more(Money::create(100)); // $result = true
```

### Математические операции

**add** - операция **сложение**
```php
$result = Money::create(100)->add(Money::create(100)); // $result = Money::create(200)
```

**addList** - операция **сложение списка**
```php
$result = Money::create(100)->addList([
    Money::create(100),
    Money::create(100)
]); // $result = Money::create(300)
```

**sub** - операция **вычитание**
```php
$result = Money::create(300)->sub(Money::create(100)); // $result = Money::create(200)
```

**subList** - операция **вычитание списка**
```php
$result = Money::create(500)->subList([
    Money::create(100),
    Money::create(100)
]); // $result = Money::create(300)
```

**round** - операция **округление**
```php
$result = Money::create()->round(1000/3); // $result = 333
```

**mul** - операция **умножение**
```php
$result = Money::create(1000)->mul(1/3); // $result = Money::create(333)
```

**dev** - операция **деление**
```php
$result = Money::create(1000)->div(3); // $result = Money::create(333)
```

### Разбиение на части

**allocate** - операция **разбиение на части**
```php
$result = Money::create(3500000)->allocate(3); // $result = [Money::create(1166700), Money::create(1166700), Money::create(1166600)]
```
