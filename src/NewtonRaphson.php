<?php

namespace Nyholm\EffectiveInterest;

/**
 * Newton-Raphsons method to do a numerical analysis to find the effective interest.
 *
 * {@link https://en.wikipedia.org/wiki/Newton%27s_method}
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class NewtonRaphson
{
    /**
     * @var int
     */
    private $precision;

    /**
     * @param int $precision
     */
    public function __construct(int $precision = 7)
    {
        $this->precision = $precision;
    }

    /**
     * Get the effective interest when the monthly payments are exactly the same.
     *
     * @param int   $a The total loan amount
     * @param int   $p The monthly payment
     * @param int   $n The number of months
     * @param float $i A guess of what the interest might be. Interest as a number between zero and one. Example 0.045
     *
     * @return float
     */
    public function getEffectiveInterest(int $a, int $p, int $n, float $i): float
    {
        $newValue = $i;
        do {
            $previousValue = $newValue;
            $newValue = $this->doCalculate($a, $p, $n, $previousValue);
        } while (round($previousValue, $this->precision) !== round($newValue, $this->precision));

        return $newValue * 12;
    }

    private function doCalculate(int $a, int $p, int $n, float $i): float
    {
        $f = $p - $p * pow(1 + $i, -1 * $n) - $i * $a;
        $fPrim = $n * $p * pow(1 + $i, -1 * $n - 1) - $a;

        return $i - ($f / $fPrim);
    }
}
