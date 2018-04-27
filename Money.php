<?php

namespace Slov\Money;

/** Класс для опреации с деньгами */
class Money
{
    /** @var int сумма в минорных денежных единицах */
    protected $amount;

    /** @var int число частей в основной денежной единице (например, копеек в рубях) */
    protected static $majorCurrencyParts = 100;

    /**
     * @param int $amount сумма в минорных денежных единицах
     */
    protected function __construct($amount)
    {
        $this->amount = $this->round($amount);
    }

    /**
     * @param int $amount сумма в минорных денежных единицах
     * @return Money
     */
    public static function create($amount = 0)
    {
        return new Money($amount);
    }

    /**
     * @return int сумма в минорных денежных единицах
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return int число частей в основной денежной единице (например, копеек в рубях)
     */
    public static function getMajorCurrencyParts()
    {
        return self::$majorCurrencyParts;
    }

    /**
     * @param int $majorCurrencyParts число частей в основной денежной единице (например, копеек в рубях)
     */
    public static function setMajorCurrencyParts($majorCurrencyParts)
    {
        self::$majorCurrencyParts = $majorCurrencyParts;
    }

    /** Сравнение равно
     * @param Money $money сравниваемые деньги
     * @return bool true - если равны */
    public function equal(Money $money)
    {
        return $this->getAmount() === $money->getAmount();
    }

    /** Сравнение меньше или равно
     * @param Money $money сравниваемые деньги
     * @return bool true - если текущие деньги меньше или равны сравниваемых */
    public function equalOrLess(Money $money)
    {
        return $this->getAmount() <= $money->getAmount();
    }

    /** Сравнение больше или равно
     * @param Money $money сравниваемые деньги
     * @return bool true - если текущие деньги больше или равны сравниваемых */
    public function equalOrMore(Money $money)
    {
        return $this->getAmount() >= $money->getAmount();
    }

    /** Сравнение меньше
     * @param Money $money сравниваемые деньги
     * @return bool true - если текущие деньги меньше сравниваемых */
    public function less(Money $money)
    {
        return $this->getAmount() < $money->getAmount();
    }

    /** Сравнение больше
     * @param Money $money сравниваемые деньги
     * @return bool true - если текущие деньги больше сравниваемых */
    public function more(Money $money)
    {
        return $this->getAmount() > $money->getAmount();
    }

    /** Сложение
     * @param Money $money слогаемое
     * @return Money результат сложения */
    public function add(Money $money)
    {
        return Money::create(
            $this->getAmount() + $money->getAmount()
        );
    }

    /** Сложение списка
     * @param Money[] $moneyList список слогаемых
     * @return Money результат сложения */
    public function addList($moneyList)
    {
        $amountList = array_map(
            function($money){
                /** @var Money $money */
                return $money->getAmount();
            },
            $moneyList
        );
        return Money::create(
            $this->getAmount() + array_sum($amountList)
        );
    }

    /** Вычитание
     * @param Money $money вычитаемое
     * @return Money разность */
    public function sub(Money $money)
    {
        return Money::create(
            $this->getAmount() - $money->getAmount()
        );
    }

    /** Вычитание списка вычетаемых
     * @param Money[] $moneyList список вычетаемых
     * @return Money разность */
    public function subList($moneyList)
    {
        return $this->sub(
            Money::create()->addList($moneyList)
        );
    }

    /** Округление до целых
     * @param float $amount дробная сумма
     * @return int сумма после округления */
    public function round($amount)
    {
        return (int)$amount;
    }

    /** Умножение
     * @param float $multiplier множитель
     * @return Money */
    public function mul($multiplier)
    {
        return Money::create(
            $this->round($this->getAmount() * $multiplier)
        );
    }

    /** Деление
     * @param float $divider делитель
     * @return Money частное */
    public function div($divider)
    {
        return $this->mul(1/$divider );
    }

    /** Разделение денег на части
     * @param int $parts число частей
     * @return Money[] список частей */
    public function allocate(int $parts)
    {
        $moneyParts = [];
        $majorAmount = (int) ($this->getAmount() / self::getMajorCurrencyParts());
        $majorAmountPart = (int) ($majorAmount / $parts);
        $majorAmountRest = $majorAmount - $parts * $majorAmountPart;
        for ($part = 0; $part < $parts; $part++, $majorAmountRest--)
        {
            $additionalMajorAmount = $majorAmountRest > 0 ? 1 : 0;
            $minorAmountPart = ($majorAmountPart + $additionalMajorAmount) * self::getMajorCurrencyParts();
            $moneyParts[] = Money::create($minorAmountPart);
        }
        return $moneyParts;
    }
}