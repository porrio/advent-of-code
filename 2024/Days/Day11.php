<?php

declare(strict_types=1);

namespace App2024\Days;

use Day;

class Day11 extends Day
{
    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();
        $this->data = explode(' ', $this->data[0]);
    }

    public function partOne(): int
    {
        return $this->countStonesAfterBlinks($this->data, 25);
    }

    public function partTwo(): int
    {
        return $this->countStonesAfterBlinks($this->data, 75);
    }

    function countStonesAfterBlinks(array $stones, int $blinks): int
    {
        $stoneCounts = array_count_values($stones);

        for ($b = 0; $b < $blinks; $b++) {
            $newStoneCounts = [];

            foreach ($stoneCounts as $stone => $count) {
                if ($stone == 0) {
                    $newStoneCounts[1] = ($newStoneCounts[1] ?? 0) + $count;
                } elseif (strlen((string)$stone) % 2 == 0) {
                    $str = (string)$stone;
                    $mid = strlen($str) / 2;
                    $left = (int)substr($str, 0, $mid);
                    $right = (int)substr($str, $mid);
                    $newStoneCounts[$left] = ($newStoneCounts[$left] ?? 0) + $count;
                    $newStoneCounts[$right] = ($newStoneCounts[$right] ?? 0) + $count;
                } else {
                    $newStone = $stone * 2024;
                    $newStoneCounts[$newStone] = ($newStoneCounts[$newStone] ?? 0) + $count;
                }
            }

            $stoneCounts = $newStoneCounts;
        }

        return array_sum($stoneCounts);
    }
}