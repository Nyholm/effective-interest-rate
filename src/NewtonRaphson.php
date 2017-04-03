<?php

namespace Nyholm\EffectiveInterest;

/**
 * Newton-Raphsons method to do a numerical analysis to find the effective interest.
 *
 * {@link https://en.wikipedia.org/wiki/Newton%27s_method}
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class NewtonRaphson
{
    /**
     * @var int
     */
    private $precision;

    /**
     * @param int $precision The number of decimals to care to calculate.
     */
    public function __construct(int $precision = 7)
    {
        $this->precision = $precision;
    }

    /**
     * @param callable $fx
     * @param callable $fdx
     * @param float    $guess
     *
     * @return float
     */
    public function run(callable $fx, callable $fdx, float $guess): float
    {
        $errorLimit = pow(10, -1 * $this->precision);
        do {
            $previousValue = $guess;
            $guess = $previousValue - ($fx($guess) / $fdx($guess));
        } while (abs($guess - $previousValue) > $errorLimit);

        return $guess;
    }
}
