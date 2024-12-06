<?php

declare(strict_types=1);

namespace App2022\Days;

use Day;

class Day8 extends Day
{
    private int $visibleTrees = 0;
    private int $maxScore = 0;

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();
        $this->countVisibleTreesAndCalculateMaxScore();
    }

    public function partOne(): int
    {
        return $this->visibleTrees;
    }

    public function partTwo(): int
    {
        return $this->maxScore;
    }

    private function countVisibleTreesAndCalculateMaxScore(): void
    {
        $width = strlen($this->data[0]);
        $height = count($this->data);

        $this->visibleTrees = $width * 2 + $height * 2 - 4;

        for ($x = 1; $x < $width - 1; $x++) {
            for ($y = 1; $y < $height - 1; $y++) {
                $score = 1;
                $currentTree = $this->data[$y][$x];

                for ($checkY = $y - 1, $checkVisibility = true, $checkScore = 0; $checkY >= 0; $checkY--) {
                    $checkScore++;
                    if ($this->data[$checkY][$x] >= $currentTree) {
                        $checkVisibility = false;
                        break;
                    }
                }

                $visible = $checkVisibility;
                $score *= $checkScore;

                for ($checkY = $y + 1, $checkVisibility = true, $checkScore = 0; $checkY < $height; $checkY++) {
                    $checkScore++;
                    if ($this->data[$checkY][$x] >= $currentTree) {
                        $checkVisibility = false;
                        break;
                    }
                }

                $visible = $visible || $checkVisibility;
                $score *= $checkScore;

                for ($checkX = $x - 1, $checkVisibility = true, $checkScore = 0; $checkX >= 0; $checkX--) {
                    $checkScore++;
                    if ($this->data[$y][$checkX] >= $currentTree) {
                        $checkVisibility = false;
                        break;
                    }
                }

                $visible = $visible || $checkVisibility;
                $score *= $checkScore;

                for ($checkX = $x + 1, $checkVisibility = true, $checkScore = 0; $checkX < $width; $checkX++) {
                    $checkScore++;
                    if ($this->data[$y][$checkX] >= $currentTree) {
                        $checkVisibility = false;
                        break;
                    }
                }

                $visible = $visible || $checkVisibility;
                $score *= $checkScore;

                if ($visible === true) {
                    $this->visibleTrees++;
                }

                if ($score > $this->maxScore) {
                    $this->maxScore = $score;
                }
            }
        }
    }
}
