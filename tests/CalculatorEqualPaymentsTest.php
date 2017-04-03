<?php

namespace Nyholm\EffectiveInterest;

use PHPUnit\Framework\TestCase;

class CalculatorEqualPaymentsTest extends TestCase
{
    /**
     * @dataProvider generator
     */
    public function testWithEqualPayments(float $correctValue, int $principal, int $payments, int $numberOfMonths, float $guess)
    {
        $calculator = new Calculator();
        $interest = $calculator->withEqualPayments($principal, $payments, $numberOfMonths, $guess);
        $this->assertEquals($correctValue, $interest, 'Failed to calculate effective interest with specified payments.', 0.00001);
    }

    public function generator()
    {
        return [
            [0.1128, 11200, 291, 48, 0.02],
        ];
    }

}
