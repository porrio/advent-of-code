<?php

declare(strict_types=1);

namespace App2022\Days;

use Day;

class Day2 extends Day
{
    private int $part1 = 0;
    private int $part2 = 0;

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();
        $this->cycleRounds();
    }

    public function partOne(): int
    {
        return $this->part1;
    }

    public function partTwo(): int
    {
        return $this->part2;
    }

    private function cycleRounds(): void
    {
        foreach ($this->data as $round) {
            $info = explode(' ', $round);
            $this->part1 += $this->getScorePart1($info);
            $this->part2 += $this->getScorePart2($info);
        }
    }

    private function getScorePart1(array $info): int
    {
        $score = 0;
        $playerA = $info[0];
        $playerB = $info[1];

        switch ($playerA) {
            case 'A':
                if ($playerB === 'X') {
                    $score = 3;
                } elseif ($playerB === 'Y') {
                    $score = 6;
                }
                break;
            case 'B':
                if ($playerB === 'Y') {
                    $score = 3;
                } elseif ($playerB === 'Z') {
                    $score = 6;
                }
                break;
            case 'C':
                if ($playerB === 'X') {
                    $score = 6;
                } elseif ($playerB === 'Z') {
                    $score = 3;
                }
                break;
        }

        $bonus = $this->getBonus($playerB);

        return $bonus + $score;
    }

    private function getScorePart2(array $info): int
    {
        $playerA = $info[0];
        $playerB = $info[1];
        $score = $bonus = 0;

        switch ($playerB) {
            case 'X': //lost
                if ($playerA === 'A') {
                    $bonus = $this->getBonus('C');
                } elseif ($playerA === 'B') {
                    $bonus = $this->getBonus('A');
                } else {
                    $bonus = $this->getBonus('B');
                }
                break;
            case 'Y': //draw
                $score = 3;
                $bonus = $this->getBonus($playerA);
                break;
            case 'Z': //win
                $score = 6;
                if ($playerA === 'A') {
                    $bonus = $this->getBonus('B');
                } elseif ($playerA === 'B') {
                    $bonus = $this->getBonus('C');
                } else {
                    $bonus = $this->getBonus('A');
                }
                break;
        }

        return $score + $bonus;
    }

    private function getBonus(string $played): int
    {
        $bonusPoints = [
            'A' => 1,
            'X' => 1,
            'B' => 2,
            'Y' => 2,
            'C' => 3,
            'Z' => 3,
        ];

        return $bonusPoints[$played];
    }
}