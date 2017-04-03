<?php

namespace Nyholm\EffectiveInterest;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Calculator
{
    /**
     * @var NewtonRaphson
     */
    private $newton;

    /**
     * @param NewtonRaphson $newton
     */
    public function __construct(NewtonRaphson $newton = null)
    {
        $this->newton = $newton ?? new NewtonRaphson();
    }

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
        $values = [-1 * $principal];
        $dates = [1];
        $startDate = new \DateTimeImmutable($startDate);

        foreach ($payments as $date => $payment) {
            $values[] = $payment;
            $dates[] = 1 + $startDate->diff(new \DateTime($date))->days;
        }

        $fx = function ($x) use ($dates, $values) {
            $sum = 0;
            foreach ($dates as $idx => $date) {
                $sum += $values[$idx] * pow(1 + $x, ($dates[0] - $date) / 365);
            }

            return $sum;
        };

        $fdx = function ($x) use ($dates, $values) {
            $sum = 0;
            foreach ($dates as $idx => $date) {
                $sum += (1 / 365) * ($dates[0] - $date) * $values[$idx] * pow(1 + $x, (($dates[0] - $date) / 365.0) - 1.0);
            }

            return $sum;
        };

        return $this->newton->run($fx, $fdx, $guess);
    }

    /**
     * Get the effective interest when the monthly payments are exactly the same.
     *
     * @param int   $a The total loan amount (Principal)
     * @param int   $p The monthly payment
     * @param int   $n The number of months
     * @param float $i A guess of what the interest might be. Interest as a number between zero and one. Example 0.045
     *
     * @return float
     */
    public function withEqualPayments(int $a, int $p, int $n, float $i): float
    {
        $fx = function ($i) use ($a, $p, $n) {
            return  $p - $p * pow(1 + $i, -1 * $n) - $i * $a;
        };

        $fdx = function ($i) use ($a, $p, $n) {
            return  $n * $p * pow(1 + $i, -1 * $n - 1) - $a;
        };

        return 12 * $this->newton->run($fx, $fdx, $i);
    }
}
