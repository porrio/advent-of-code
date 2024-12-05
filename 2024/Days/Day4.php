<?php

declare(strict_types=1);

namespace App2024\Days;

use Day;

class Day4 extends Day
{
    private int $rows;
    private int $cols;
    private int $part1 = 0;
    private int $part2 = 0;

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();

        $this->rows = count($this->data);
        $this->cols = strlen($this->data[0]);
        $this->countXMASOccurrences();
    }

    public function partOne(): int
    {
       return $this->part1;
    }

    public function partTwo(): int
    {
        return $this->part2;
    }

    private function countXMASOccurrences(): void
    {
        $directions = [
            [0, 1],
            [1, 0],
            [1, 1],
            [1, -1],
            [0, -1],
            [-1, 0],
            [-1, -1],
            [-1, 1],
        ];

        for ($row = 0; $row < $this->rows; $row++) {
            for ($col = 0; $col < $this->cols; $col++) {
                foreach ($directions as $direction) {
                    if ($this->checkXMAS($row, $col, $direction)) {
                        $this->part1++;
                    }
                }

                if ($this->isMASInXPattern($row, $col)) {
                    $this->part2++;
                }
            }
        }
    }

    private function checkXMAS($startRow, $startCol, $direction): bool
    {
        $word = 'XMAS';
        $wordLength = strlen($word);
        for ($i = 0; $i < $wordLength; $i++) {
            $r = $startRow + $i * $direction[0];
            $c = $startCol + $i * $direction[1];

            if ($r < 0 || $r >= $this->rows || $c < 0 || $c >= $this->cols) {
                return false;
            }

            if ($this->data[$r][$c] !== $word[$i]) {
                return false;
            }
        }

        return true;
    }

    private function isMASInXPattern(int $row, int $col): bool
    {
        if ($row - 1 < 0 || $row + 1 >= $this->rows || $col - 1 < 0 || $col + 1 >= $this->cols) {
            return false;
        }

        if ($this->data[$row][$col] !== 'A') {
            return false;
        }

        $lTop = $this->data[$row - 1][$col - 1];
        $rTop = $this->data[$row - 1][$col + 1];
        $lBot = $this->data[$row + 1][$col - 1];
        $rBot = $this->data[$row + 1][$col + 1]; 

        return (
            ($lTop === 'M' && $rTop === 'S' && $lBot === 'M' && $rBot === 'S') ||
            ($lTop === 'S' && $rTop === 'M' && $lBot === 'S' && $rBot === 'M') ||
            ($lTop === 'M' && $rTop === 'M' && $lBot === 'S' && $rBot === 'S') ||
            ($lTop === 'S' && $rTop === 'S' && $lBot === 'M' && $rBot === 'M')
        );
    }
}
