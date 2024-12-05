<?php

declare(strict_types=1);

namespace App2023\Days;

use Day;

class Day11 extends Day
{
    private int $rows;
    private int $cols;
    private int $sumOfShortestPaths;
    private int $expansion;

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();

        $this->rows = count($this->data);
        $this->cols = strlen($this->data[0]);
        $this->sumOfShortestPaths = 0;
        $this->expansion = 1;
    }

    public function partOne(): int
    {
        return self::findSumOfShortestPaths();
    }

    public function partTwo(): int
    {
        $this->expansion = 999999;
        return self::findSumOfShortestPaths();
    }

    private function findSumOfShortestPaths(): int
    {
        $hashPoints = self::getAllHashPoints();
        $gaps = self::identifyGaps();

        for ($i = 0; $i < count($hashPoints); $i++) {
            for ($j = $i + 1; $j < count($hashPoints); $j++) {
                $distance = self::manhattanDistance($hashPoints[$i], $hashPoints[$j], $gaps);
                $this->sumOfShortestPaths += $distance;
            }
        }

        return $this->sumOfShortestPaths;
    }

    private function getAllHashPoints(): array
    {
        $hashPoints = [];

        for ($i = 0; $i < $this->rows; $i++) {
            for ($j = 0; $j < $this->cols; $j++) {
                if ($this->data[$i][$j] === '#') {
                    $hashPoints[] = [$i, $j];
                }
            }
        }

        return $hashPoints;
    }

    private function identifyGaps(): array
    {
        $gaps = [];

        for ($i = 0; $i < $this->rows; $i++) {
            $isEmptyRow = strpos($this->data[$i], '#') === false;

            if ($isEmptyRow) {
                $gaps[] = [$i, $this->expansion, true];
            }
        }

        for ($j = 0; $j < $this->cols; $j++) {
            $isEmptyColumn = true;
            for ($i = 0; $i < $this->rows; $i++) {
                if ($this->data[$i][$j] === '#') {
                    $isEmptyColumn = false;
                    break;
                }
            }

            if ($isEmptyColumn) {
                $gaps[] = [$j, $this->expansion, false];
            }
        }

        return $gaps;
    }

    private function manhattanDistance($point1, $point2, $gaps): int
    {
        [$x1, $y1] = $point1;
        [$x2, $y2] = $point2;

        $gapY1 = 0;
        $gapY2 = 0;
        $gapX1 = 0;
        $gapX2 = 0;

        // Adjust distance for gaps
        foreach ($gaps as [$gapStart, $gapSize, $isRow]) {
            if (!$isRow) {
                if ($y1 > $gapStart) {
                    $gapY1 += $gapSize;
                }

                if ($y2 > $gapStart) {
                    $gapY2 += $gapSize;
                }
            } else {
                if ($x1 > $gapStart) {
                    $gapX1 += $gapSize;
                }

                if ($x2 > $gapStart) {
                    $gapX2 += $gapSize;
                }
            }
        }

        $x1 += $gapX1;
        $x2 += $gapX2;
        $y1 += $gapY1;
        $y2 += $gapY2;

        return abs($x1 - $x2) + abs($y1 - $y2);
    }
}