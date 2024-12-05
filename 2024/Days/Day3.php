<?php

declare(strict_types=1);

namespace App2024\Days;

use Day;

class Day3 extends Day
{
    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData(PHP_EOL, 'string');
    }

    public function partOne(): int
    {
        return $this->sumValidMultiplications($this->data);
    }

    public function partTwo(): int
    {
        return $this->sumValidMultiplicationsWithConditions($this->data);
    }

    private function sumValidMultiplications($input): int
    {
        $pattern = '/mul\((\d+),(\d+)\)/';
        preg_match_all($pattern, $input, $matches);

        $total = 0;

        foreach ($matches[1] as $index => $x) {
            $y = $matches[2][$index];
            $total += intval($x) * intval($y);
        }

        return $total;
    }

    private function sumValidMultiplicationsWithConditions($input): int
    {
        $mulPattern = '/mul\((\d+),(\d+)\)/';
        $doPattern = '/do\(\)/';
        $dontPattern = '/don\'t\(\)/';
        $enabled = true;
        $sum = 0;

        preg_match_all("/mul\(\d+,\d+\)|do\(\)|don\'t\(\)/", $input, $matches);

        foreach ($matches[0] as $match) {
            if (preg_match($doPattern, $match)) {
                $enabled = true;
            } elseif (preg_match($dontPattern, $match)) {
                $enabled = false;
            } elseif ($enabled && preg_match($mulPattern, $match, $mulMatches)) {
                $x = intval($mulMatches[1]);
                $y = intval($mulMatches[2]);
                $sum += $x * $y;
            }
        }

        return $sum;
    }
}