<?php

declare(strict_types=1);

namespace App2024\Days;

use Day;

class Day10 extends Day
{
    private int $width = 0;
    private int $height = 0;

    private const DIRECTIONS = [
        [-1, 0], // up
        [1, 0],  // down
        [0, -1], // left
        [0, 1]   // right
    ];

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();
        $this->parseMap();
    }
    
    public function partOne(): int
    {
        return $this->calculateTrailheadScore($this->data);
    }

    public function partTwo(): int
    {
        return $this->calculateTrailheadScore($this->data, true);
    }

    private function parseMap(): void
    {
        foreach ($this->data as $index => $line) {
            $this->data[$index] = array_map('intval', str_split($line));
        }

        $this->width = count($this->data);
        $this->height = count($this->data[0]);
    }

    private function calculateTrailheadScore(array $map, bool $getRating = false): int
    {
        $visited = array_fill(0, count($map), array_fill(0, count($map[0]), false));
        $totalScore = 0;

        for ($i = 0; $i < count($map); $i++) {
            for ($j = 0; $j < count($map[0]); $j++) {
                if ($map[$i][$j] === 0) {
                    $totalScore += $this->exploreTrail($map, $i, $j, $visited, !$getRating);
                }
            }
        }

        return $totalScore;
    }

    private function exploreTrail(array $map, int $x, int $y, array $visited, bool $checkVisited = true): int
    {
        $trailScore = 0;

        $stack = [[$x, $y]];

        while (count($stack) > 0) {
            [$currentX, $currentY] = array_pop($stack);

            if ($checkVisited === true) {
                if ($visited[$currentX][$currentY]) {
                    continue;
                }

                $visited[$currentX][$currentY] = true;
            }

            if ($map[$currentX][$currentY] === 9) {
                $trailScore++;
            }

            foreach (self::DIRECTIONS as $dir) {
                $newX = $currentX + $dir[0];
                $newY = $currentY + $dir[1];

                if (
                    $x >= 0 &&
                    $x < $this->width &&
                    $y >= 0 &&
                    $y < $this->height &&
                    $map[$newX][$newY] === $map[$currentX][$currentY] + 1
                ) {
                    $stack[] = [$newX, $newY];
                }
            }
        }

        return $trailScore;
    }
}