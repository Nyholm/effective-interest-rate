<?php

declare(strict_types=1);

namespace Nyholm\EffectiveInterest;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class Calculator
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
        list($values, $days) = $this->preparePayments($principal, $startDate, $payments);

        $fx = function ($x) use ($days, $values) {
            $sum = 0;
            foreach ($days as $idx => $day) {
                $sum += $values[$idx] * pow(1 + $x, ($days[0] - $day) / 365);
            }

            return $sum;
        };

        $fdx = function ($x) use ($days, $values) {
            $sum = 0;
            foreach ($days as $idx => $day) {
                $sum += (1 / 365) * ($days[0] - $day) * $values[$idx] * pow(1 + $x, (($days[0] - $day) / 365) - 1);
            }

            return $sum;
        };

        return $this->newton->run($fx, $fdx, $guess);
    }

    /**
     * Get the effective interest when the monthly payments are exactly the same.
     *
     * @param int   $principal      The total loan amount (Principal)
     * @param int   $payment        The monthly payment
     * @param int   $numberOfMonths The number of months
     * @param float $guess          A guess of what the interest might be. Interest as a number between zero and one. Example 0.045
     *
     * @return float
     */
    public function withEqualPayments(int $principal, int $payment, int $numberOfMonths, float $guess): float
    {
        $fx = function ($x) use ($principal, $payment, $numberOfMonths) {
            return  $payment - $payment * pow(1 + $x, -1 * $numberOfMonths) - $x * $principal;
        };

        $fdx = function ($x) use ($principal, $payment, $numberOfMonths) {
            return  $numberOfMonths * $payment * pow(1 + $x, -1 * $numberOfMonths - 1) - $principal;
        };

        return 12 * $this->newton->run($fx, $fdx, $guess);
    }

    /**
     * Prepare payment data by separating dates from values and prefix the array with the principal.
     *
     * @param int    $principal
     * @param string $startDate
     * @param array  $payments
     *
     * @return array
     */
    private function preparePayments(int $principal, string $startDate, array $payments): array
    {
        $values = [-1 * $principal];
        $days = [1];
        $startDate = new \DateTimeImmutable($startDate);

        foreach ($payments as $date => $payment) {
            $values[] = $payment;
            $days[] = 1 + $startDate->diff(new \DateTime($date))->days;
        }

        return [$values, $days];
    }
}
