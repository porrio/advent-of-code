<?php

declare(strict_types=1);

namespace App2024\Days;

use Day;

class Day6 extends Day
{
    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();
    }

    public function partOne(): int
    {
        return $this->simulatePatrol();
    }

    public function partTwo(): int
    {
        return $this->countLoopObstructionPositions();
    }

    private function simulatePatrol(bool $loopDetection = false, array $grid = null)
    {
        $grid = $grid ?? array_map('str_split', $this->data);

        $directions = ['^' => [-1, 0], '>' => [0, 1], 'v' => [1, 0], '<' => [0, -1]];
        $turns = ['^' => '>', '>' => 'v', 'v' => '<', '<' => '^'];

        $rows = count($grid);
        $cols = count($grid[0]);
        $guardPosition = null;
        $facing = null;

        foreach ($grid as $row => $line) {
            foreach ($line as $col => $char) {
                if (in_array($char, ['^', '>', 'v', '<'])) {
                    $guardPosition = [$row, $col];
                    $facing = $char;
                    $grid[$row][$col] = '.';
                    break 2;
                }
            }
        }

        $visitedStates = [];
        $visited = [];
        if (!$loopDetection) {
            $visited[implode(',', $guardPosition)] = true;
        }

        while (true) {
            [$row, $col] = $guardPosition;
            [$dr, $dc] = $directions[$facing];

            $nextRow = $row + $dr;
            $nextCol = $col + $dc;

            if ($nextRow < 0 || $nextRow >= $rows || $nextCol < 0 || $nextCol >= $cols) {
                return $loopDetection ? false : count($visited);
            }

            if ($grid[$nextRow][$nextCol] === '#') {
                $facing = $turns[$facing];
            } else {
                $guardPosition = [$nextRow, $nextCol];

                if ($loopDetection) {
                    $state = implode(',', $guardPosition) . ',' . $facing;

                    if (isset($visitedStates[$state])) {
                        return true; // Loop detected
                    }

                    $visitedStates[$state] = true;
                } else {
                    $visited[implode(',', $guardPosition)] = true;
                }
            }
        }
    }

    private function countLoopObstructionPositions(): int
    {
        $originalGrid = array_map('str_split', $this->data);

        $loopCount = 0;

        foreach ($originalGrid as $row => $line) {
            foreach ($line as $col => $cell) {
                if ($cell === '.') {
                    $grid = $originalGrid;
                    $grid[$row][$col] = '#';

                    if ($this->simulatePatrol(true, $grid)) {
                        $loopCount++;
                    }
                }
            }
        }

        return $loopCount;
    }
}
