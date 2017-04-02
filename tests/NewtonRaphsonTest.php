<?php

namespace Nyholm\EffectiveInterest;


use PHPUnit\Framework\TestCase;

class NewtonRaphsonTest extends TestCase
{
    /**
     * From https://brownmath.com/bsci/loan.htm#Sample6
     */
    public function testGetEffectiveInterest()
    {
        $newton = new NewtonRaphson();
        $interest = $newton->getEffectiveInterest(11200, 291, 48, 0.12);
        $this->assertEquals(0.1128, $interest, '', 0.0001);
    }
}
