<?php

declare(strict_types=1);

namespace App2024\Days;

use Day;

class Day1 extends Day
{
    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();
    }

    public function partOne(): int
    {
        return $this->calculateTotalDistance($this->data);
    }

    public function partTwo(): int
    {
        return $this->calculateSimilarityScore($this->data);
    }

    private function calculateTotalDistance(array $inputLines): int
    {
        $leftList = [];
        $rightList = [];

        foreach ($inputLines as $line) {
            list($left, $right) = preg_split('/\s+/', trim($line));
            $leftList[] = (int)$left;
            $rightList[] = (int)$right;
        }

        sort($leftList);
        sort($rightList);

        $totalDistance = 0;

        for ($i = 0; $i < count($leftList); $i++) {
            $totalDistance += abs($leftList[$i] - $rightList[$i]);
        }

        return $totalDistance;
    }

    private function calculateSimilarityScore(array $inputLines): int
    {
        $leftList = [];
        $rightList = [];

        foreach ($inputLines as $line) {
            list($left, $right) = preg_split('/\s+/', trim($line));
            $leftList[] = (int)$left;
            $rightList[] = (int)$right;
        }

        $rightCount = array_count_values($rightList);

        $similarityScore = 0;
        foreach ($leftList as $number) {
            if (isset($rightCount[$number])) {
                $similarityScore += $number * $rightCount[$number];
            }
        }

        return $similarityScore;
    }
}