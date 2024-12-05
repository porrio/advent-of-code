<?php

declare(strict_types=1);

namespace App2024\Days;

use Day;

class Day2 extends Day
{
    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();
    }

    public function partOne(): int
    {
        return $this->countSafeReports($this->data);
    }

    public function partTwo(): int
    {
        return $this->countSafeReports($this->data, true);
    }

    private function countSafeReports($reports, $useDampener = false): int
    {
        $safeCount = 0;

        foreach ($reports as $report) {
            $levels = array_map('intval', preg_split('/\s+/', trim($report)));

            if ($this->isSafe($levels)) {
                $safeCount++;
                continue;
            }

            if (!$useDampener) {
                continue;
            }

            for ($i = 0; $i < count($levels); $i++) {
                $modifiedLevels = $levels;
                unset($modifiedLevels[$i]);
                $modifiedLevels = array_values($modifiedLevels);

                if ($this->isSafe($modifiedLevels)) {
                    $safeCount++;
                    break;
                }
            }
        }

        return $safeCount;
    }

    private function isSafe(array $levels): bool
    {
        if (count($levels) < 2) {
            return true;
        }

        $isIncreasing = true;
        $isDecreasing = true;

        for ($i = 1; $i < count($levels); $i++) {
            $difference = $levels[$i] - $levels[$i - 1];

            if ($difference < -3 || $difference > 3 || $difference === 0) {
                return false;
            }

            if ($difference < 0) {
                $isIncreasing = false;
            } elseif ($difference > 0) {
                $isDecreasing = false;
            }
        }

        return $isIncreasing || $isDecreasing;
    }
}