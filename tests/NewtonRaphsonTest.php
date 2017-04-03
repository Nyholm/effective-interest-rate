<?php

namespace Nyholm\EffectiveInterest\Test;

use Nyholm\EffectiveInterest\NewtonRaphson;
use PHPUnit\Framework\TestCase;

class NewtonRaphsonTest extends TestCase
{
    /**
     * From https://brownmath.com/bsci/loan.htm#Sample6.
     */
    public function testGetEffectiveInterest()
    {
        $a = 11200;
        $p = 291;
        $n = 48;

        $fx = function ($i) use ($a, $p, $n) {
            return  $p - $p * pow(1 + $i, -1 * $n) - $i * $a;
        };

        $fdx = function ($i) use ($a, $p, $n) {
            return  $n * $p * pow(1 + $i, -1 * $n - 1) - $a;
        };

        $newton = new NewtonRaphson();
        $interest = 12 * $newton->run($fx, $fdx, 0.12);
        $this->assertEquals(0.1128, $interest, '', 0.0001);
    }
}
