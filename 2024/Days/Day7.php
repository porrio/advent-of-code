<?php

declare(strict_types=1);

namespace App2024\Days;

use Day;

class Day7 extends Day
{
    private array $equations = [];

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();
        $this->fetchEquations();
    }

    public function partOne(): int
    {
        return $this->getTotalCalibrationValue();
    }

    public function partTwo(): int
    {
        return $this->getTotalCalibrationValue(true);
    }

    private function fetchEquations(): void
    {
        $equations = [];
        foreach ($this->data as $line) {
            [$target, $numbers] = explode(':', $line);
            $target = (int)trim($target);

            $numbers = array_map('intval', explode(' ', trim($numbers)));

            $equations[] = [$target, $numbers];
        }

        $this->equations = $equations;
    }

    private function getTotalCalibrationValue(bool $includeConcatenation = false): int
    {
        $total = 0;

        foreach ($this->equations as $equation) {
            [$target, $numbers] = $equation;

            if ($this->checkEquation($numbers, $target, $includeConcatenation)) {
                $total += $target;
            }
        }

        return $total;
    }

    private function checkEquation($numbers, $target, $includeConcatenation): bool
    {
        return $this->checkOperationValue(1, $numbers[0], $numbers, $target, $includeConcatenation);
    }

    private function checkOperationValue($index, $current, $numbers, $target, $includeConcatenation): bool
    {
        if ($index === count($numbers)) {
            return $current === $target;
        }

        $nextNumber = $numbers[$index];

        if (
            $includeConcatenation &&
            $this->checkOperationValue($index + 1, intval($current . $nextNumber), $numbers, $target, $includeConcatenation)) {
            return true;
        }

        return  $this->checkOperationValue($index + 1, $current + $nextNumber, $numbers, $target, $includeConcatenation) ||
            $this->checkOperationValue($index + 1, $current * $nextNumber, $numbers, $target, $includeConcatenation);
    }
}