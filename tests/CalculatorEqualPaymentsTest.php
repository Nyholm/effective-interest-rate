<?php

namespace Nyholm\EffectiveInterest\Test;

use Nyholm\EffectiveInterest\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorEqualPaymentsTest extends TestCase
{
    /**
     * @dataProvider generator
     */
    public function testWithEqualPayments(float $correctValue, int $principal, int $payment, int $numberOfMonths, float $guess)
    {
        $calculator = new Calculator();
        $interest = $calculator->withEqualPayments($principal, $payment, $numberOfMonths, $guess);
        $this->assertEquals($correctValue, $interest, 'Failed to calculate effective interest with specified payments.', 0.0001);
    }

    public function generator()
    {
        return [
            [0.1128, 11200, 291, 48, 0.02],
            [0.0712, 100000, 2400, 48, 0.03],
        ];
    }

}
