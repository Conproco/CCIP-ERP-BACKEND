<?php

declare(strict_types=1);

namespace Src\HumanResource\Application\Services\Payroll;

use Carbon\Carbon;

class PayrollCalculationService
{
    /**
     * Calculate days worked in a month based on hire/fire dates
     */
    public function calculateDays(string $hireDate, ?string $firedDate, string $month): int
    {
        $hire = Carbon::parse($hireDate);
        $fire = $firedDate ? Carbon::parse($firedDate) : null;

        $monthCarbon = Carbon::parse($month . '-01');
        $monthDays = $monthCarbon->daysInMonth;
        $targetMonth = $monthCarbon->month;
        $targetYear = $monthCarbon->year;

        if ($fire) {
            $fireInMonth = $fire->month === $targetMonth && $fire->year === $targetYear;
            $hireInMonth = $hire->month === $targetMonth && $hire->year === $targetYear;

            // Hired and fired in same month
            if ($hireInMonth && $fireInMonth) {
                $worked = $fire->day - $hire->day + 1;
                return $worked >= $monthDays ? 30 : $worked;
            }

            // Fired this month (hired before)
            if ($fireInMonth) {
                return $fire->day === $monthDays ? 30 : $fire->day;
            }

            // Fired in another month - worked full month
            return 30;
        }

        // Check if hired this month
        $hireInMonth = $hire->month === $targetMonth && $hire->year === $targetYear;

        if ($hireInMonth) {
            return $hire->day === 1 ? 30 : ($monthDays - $hire->day + 1);
        }

        // Hired before this month - worked full month
        return 30;
    }

    /**
     * Calculate proportional amount based on days worked
     */
    public function calculateAmount(int $days, float $basicSalary): float
    {
        $amountPerDay = $basicSalary / 30;
        return round($amountPerDay * $days, 2);
    }
}
