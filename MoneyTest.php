<?php

use PHPUnit\Framework\TestCase;
use Slov\Money\Money;

class MoneyTest extends TestCase
{
    public function testMoneyInstance()
    {
        $money = Money::create(100);
        $this->assertInstanceOf('Slov\Money\Money', $money);
    }

    public function testGetAmount()
    {
        $amount = 100;
        $money = Money::create(100);
        $this->assertEquals($amount, $money->getAmount());
    }

    public function testEqual()
    {
        $moneyHundred1 = Money::create(100);
        $moneyHundred2 = Money::create(100);
        $this->assertTrue($moneyHundred1->equal($moneyHundred2));
    }

    public function testNotEqual()
    {
        $moneyHundred = Money::create(100);
        $moneyThousand = Money::create(1000);
        $this->assertFalse($moneyHundred->equal($moneyThousand));
    }

    public function equalOrLessDataProvider()
    {
        return [
            [Money::create(100), Money::create(100), true],
            [Money::create(100), Money::create(1000), true],
            [Money::create(1000), Money::create(100), false]
        ];
    }

    /**
     * @param Money $moneyLeft деньги слева
     * @param Money $moneyRight деньги справа
     * @param boolean $expectedResult ожидаемый результат
     * @dataProvider equalOrLessDataProvider
     */
    public function testEqualOrLess($moneyLeft, $moneyRight, $expectedResult)
    {
        $this->assertEquals($expectedResult, $moneyLeft->equalOrLess($moneyRight));
    }

    public function equalOrMoreDataProvider()
    {
        return [
            [Money::create(100), Money::create(100), true],
            [Money::create(100), Money::create(1000), false],
            [Money::create(1000), Money::create(100), true]
        ];
    }

    /**
     * @param Money $moneyLeft деньги слева
     * @param Money $moneyRight деньги справа
     * @param boolean $expectedResult ожидаемый результат
     * @dataProvider equalOrMoreDataProvider
     */
    public function testEqualOrMore($moneyLeft, $moneyRight, $expectedResult)
    {
        $this->assertEquals($expectedResult, $moneyLeft->equalOrMore($moneyRight));
    }

    public function lessDataProvider()
    {
        return [
            [Money::create(100), Money::create(100), false],
            [Money::create(100), Money::create(1000), true],
            [Money::create(1000), Money::create(100), false]
        ];
    }

    /**
     * @param Money $moneyLeft деньги слева
     * @param Money $moneyRight деньги справа
     * @param boolean $expectedResult ожидаемый результат
     * @dataProvider lessDataProvider
     */
    public function testLess($moneyLeft, $moneyRight, $expectedResult)
    {
        $this->assertEquals($expectedResult, $moneyLeft->less($moneyRight));
    }

    public function moreDataProvider()
    {
        return [
            [Money::create(100), Money::create(100), false],
            [Money::create(100), Money::create(1000), false],
            [Money::create(1000), Money::create(100), true]
        ];
    }

    /**
     * @param Money $moneyLeft деньги слева
     * @param Money $moneyRight деньги справа
     * @param boolean $expectedResult ожидаемый результат
     * @dataProvider moreDataProvider
     */
    public function testMore($moneyLeft, $moneyRight, $expectedResult)
    {
        $this->assertEquals($expectedResult, $moneyLeft->more($moneyRight));
    }

    public function testAdd()
    {
        $moneyOneHundred = Money::create(100);
        $moneyOneThousand = Money::create(1000);
        $moneyOneHundredOneThousand = Money::create(1100);

        $this->assertTrue(
            $moneyOneHundredOneThousand->equal(
                $moneyOneThousand->add($moneyOneHundred)
            )
        );
    }

    public function testAddList()
    {
        $moneyList = [
            Money::create(100),
            Money::create(200),
            Money::create(300),
            Money::create(400)
        ];

        $moneyOneThousand = Money::create(1000);

        $this->assertTrue(
            $moneyOneThousand->equal(
                Money::create()->addList($moneyList)
            )
        );
    }

    public function testSub()
    {
        $moneyOneThousand = Money::create(1000);
        $moneyOneHundred = Money::create(100);
        $nineHundred = Money::create(900);

        $this->assertTrue(
            $nineHundred->equal(
                $moneyOneThousand->sub($moneyOneHundred)
            )
        );
    }

    public function testSubList()
    {
        $moneyHundred = Money::create(100);
        $moneyThousand = Money::create(1000);

        $moneyList = [
            Money::create(200),
            Money::create(300),
            Money::create(400)
        ];

        $this->assertTrue(
            $moneyHundred->equal(
                $moneyThousand->subList($moneyList)
            )
        );
    }

    public function testRound()
    {
        $this->assertEquals(
            99,
            Money::create()->round(99.99)
        );
    }

    public function testMul()
    {
        $penaltyRatePerYear = 0.20;
        $creditAmount = 1000000;
        $daysInYear = 365;
        $penaltyAmountPerDayExpected = 547;

        $penaltyRatePerDay = $penaltyRatePerYear / $daysInYear;
        $penaltyPerDay = Money::create($creditAmount)->mul($penaltyRatePerDay);

        $this->assertTrue(
            $penaltyPerDay->equal(Money::create($penaltyAmountPerDayExpected))
        );
    }

    public function testDiv()
    {
        $penaltyRatePerYear = 0.20;
        $creditAmount = 1000000;
        $daysInYear = 365;
        $penaltyAmountPerDayExpected = 547;

        $penaltyAmountPerYear = $penaltyRatePerYear * $creditAmount;
        $penaltyPerDay = Money::create($penaltyAmountPerYear)->div($daysInYear);

        $this->assertTrue(
            $penaltyPerDay->equal(Money::create($penaltyAmountPerDayExpected))
        );
    }

    public function testAllocate()
    {
        $creditAmount = 35000*100;
        $creditParts = 3;
        $expectedMoneyParts = [
            Money::create(11667*100),
            Money::create(11667*100),
            Money::create(11666*100)
        ];

        $actualMoneyParts = Money::create($creditAmount)->allocate($creditParts);
        foreach($expectedMoneyParts as $part => $moneyPart){
            $actualMoneyPart = $actualMoneyParts[$part];
            $expectedMoneyPart = $expectedMoneyParts[$part];
            $this->assertTrue($actualMoneyPart->equal($expectedMoneyPart));
        }
    }
}