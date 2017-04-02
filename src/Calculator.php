<?php

namespace Nyholm\EffectiveInterest;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Calculator
{
    /**
     * Get the interest when you know all the payments and their dates. Use this function when you have
     * administration fees at the first payment and/or when payments are irregular.
     *
     * @param int    $principal
     * @param string $startDate in format 'YYYY-mm-dd'
     * @param array  $payments  array with payment dates and values ['YYYY-mm-dd'=>int]
     * @param float  $guess     A guess what the interest may be. Between zero and one. Example 0.045
     *
     * @return float
     */
    public function withSpecifiedPayments(int $principal, string $startDate, array $payments, float $guess): float
    {
        return 0.045;
    }

    public function withEqualPayments()
    {
        // TODO implement NewtonRaphson call.
    }
}
