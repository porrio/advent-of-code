<?php

declare(strict_types=1);

namespace App2024\Days;

use Day;

class Day6 extends Day
{
    private array $grid;
    private int $rows;
    private int $cols;
    private array $visited;
    private array $guardPosition = [];
    private string $facing;

    private const DIRECTIONS = ['^' => [-1, 0], '>' => [0, 1], 'v' => [1, 0], '<' => [0, -1]];
    private const TURNS = ['^' => '>', '>' => 'v', 'v' => '<', '<' => '^'];

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();

        $this->grid = array_map('str_split', $this->data);
        $this->rows = count($this->grid);
        $this->cols = count($this->grid[0]);

        [$guardPosition, $facing] = $this->findGuard($this->grid);

        $this->guardPosition = $guardPosition;
        $this->facing = $facing;
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
        $grid = $grid ?? $this->grid;

        [$guardPosition, $facing] = [$this->guardPosition, $this->facing];

        $visitedStates = [];
        if (!$loopDetection) {
            $this->visited[$guardPosition[0]][$guardPosition[1]] = true;
        }

        while (true) {
            [$row, $col] = $guardPosition;
            [$dr, $dc] = self::DIRECTIONS[$facing];

            $nextRow = $row + $dr;
            $nextCol = $col + $dc;

            if ($nextRow < 0 || $nextRow >= $this->rows || $nextCol < 0 || $nextCol >= $this->cols) {
                return $loopDetection ? false : array_sum(array_map('count', $this->visited));
            }

            if ($grid[$nextRow][$nextCol] === '#') {
                $facing = self::TURNS[$facing];
            } else {
                $guardPosition = [$nextRow, $nextCol];

                if ($loopDetection) {
                    if (isset($visitedStates[$guardPosition[0]][$guardPosition[1]][$facing])) {
                        return true; // Loop detected
                    }

                    $visitedStates[$guardPosition[0]][$guardPosition[1]][$facing] = true;
                } else {
                    $this->visited[$guardPosition[0]][$guardPosition[1]] = true;
                }
            }
        }
    }

    private function countLoopObstructionPositions(): int
    {
        $loopCount = 0;

        foreach ($this->visited as $row => $line) {
            foreach ($line as $col => $cell) {
                if ($this->grid[$row][$col] === '.') {
                    $grid = $this->grid;
                    $grid[$row][$col] = '#';

                    if ($this->simulatePatrol(true, $grid)) {
                        $loopCount++;
                    }
                }
            }
        }

        return $loopCount;
    }

    private function findGuard(array &$grid): array
    {
        foreach ($grid as $row => $line) {
            $col = strcspn(implode('', $line), '^>v<');
            if ($col < strlen(implode('', $line))) {
                $facing = $line[$col];
                $grid[$row][$col] = '.';
                return [[$row, $col], $facing];
            }
        }

        return [null, null];
    }
}
