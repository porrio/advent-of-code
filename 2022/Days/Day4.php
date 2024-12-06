<?php

declare(strict_types=1);

namespace App2022\Days;

use Day;

class Day4 extends Day
{
    private array $counter = [1 => 0, 2 => 0];

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();
        $this->countOverlappingAssignments();
    }

    public function partOne(): int
    {
        return $this->counter[1];
    }

    public function partTwo(): int
    {
        return $this->counter[2];
    }

    private function countOverlappingAssignments(): void
    {
        foreach ($this->data as $pair) {
            $assignment1 = explode(',', $pair)[0];
            $assignment2 = explode(',', $pair)[1];

            $assignment1Start = explode('-', $assignment1)[0];
            $assignment1End = explode('-', $assignment1)[1];
            $assignment2Start = explode('-', $assignment2)[0];
            $assignment2End = explode('-', $assignment2)[1];

            if (
                ($assignment1Start <= $assignment2Start && $assignment1End >= $assignment2End) ||
                ($assignment2Start <= $assignment1Start && $assignment2End >= $assignment1End)
            ) {
                $this->counter[1]++;
            }

            $range1 = range($assignment1Start, $assignment1End);
            $range2 = range($assignment2Start, $assignment2End);

            if (count(array_intersect($range1, $range2)) > 0) {
                $this->counter[2]++;
            }
        }
    }
}
