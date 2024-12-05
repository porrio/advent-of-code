<?php

declare(strict_types=1);

namespace App2023\Days;

use Day;

class Day23 extends Day
{
    private bool $followSlopes;
    private array $startPoint;
    private array $endPoint;
    private array $grid;

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();

        $this->followSlopes = true;
        $this->grid = array_map('str_split', $this->data);
        $this->startPoint = $this->findStart();
        $this->endPoint = $this->findEnd();
    }

    public function partOne(): int
    {
        $this->followSlopes = true;
        return $this->findLongestPath();
    }

    public function partTwo(): int
    {
        $this->followSlopes = false;
        return $this->findLongestPath();
    }

    private function findLongestPath(): int
    {
        $arrayGrid = $this->grid;
        $maxPathLength = 0;

        $rows = count($arrayGrid);
        $cols = count($arrayGrid[0]);

        $start = $this->startPoint;

        // Perform DFS to find the longest path
        self::dfs($arrayGrid, $start[0], $start[1], 0, $maxPathLength, $rows, $cols);

        return $maxPathLength - 1;
    }

    /**
     * Depth First Find
     */
    private function dfs(&$arrayGrid, $x, $y, $currentPathLength, &$maxPathLength, $rows, $cols) {
        // Check if the current position is within bounds and is a valid step
        if ($x < 0 || $x >= $rows || $y < 0 || $y >= $cols || $arrayGrid[$x][$y] === '#' || $arrayGrid[$x][$y] === 'O') {
            return;
        }

        // Mark the current position as visited
        $temp = $arrayGrid[$x][$y];
        $arrayGrid[$x][$y] = 'O';

        // Update the current path length
        $currentPathLength++;

        // Update the maximum path length if endpoint is reached
        if ($x === $this->endPoint[0] && $y === $this->endPoint[1]) {
            $maxPathLength = max($maxPathLength, $currentPathLength);
        }

        // Perform DFS in all four directions
        if ($this->followSlopes === true) {
            switch ($temp) {
                case '>':
                    self::dfs($arrayGrid, $x, $y + 1, $currentPathLength, $maxPathLength, $rows, $cols);
                    break;
                case '<':
                    self::dfs($arrayGrid, $x, $y - 1, $currentPathLength, $maxPathLength, $rows, $cols);
                    break;
                case '^':
                    self::dfs($arrayGrid, $x - 1, $y, $currentPathLength, $maxPathLength, $rows, $cols);
                    break;
                case 'v':
                    self::dfs($arrayGrid, $x + 1, $y, $currentPathLength, $maxPathLength, $rows, $cols);
                    break;
                default:
                    self::dfs($arrayGrid, $x + 1, $y, $currentPathLength, $maxPathLength, $rows, $cols);
                    self::dfs($arrayGrid, $x - 1, $y, $currentPathLength, $maxPathLength, $rows, $cols);
                    self::dfs($arrayGrid, $x, $y + 1, $currentPathLength, $maxPathLength, $rows, $cols);
                    self::dfs($arrayGrid, $x, $y - 1, $currentPathLength, $maxPathLength, $rows, $cols);
            }
        } else {
            self::dfs($arrayGrid, $x + 1, $y, $currentPathLength, $maxPathLength, $rows, $cols);
            self::dfs($arrayGrid, $x - 1, $y, $currentPathLength, $maxPathLength, $rows, $cols);
            self::dfs($arrayGrid, $x, $y + 1, $currentPathLength, $maxPathLength, $rows, $cols);
            self::dfs($arrayGrid, $x, $y - 1, $currentPathLength, $maxPathLength, $rows, $cols);
        }

        $arrayGrid[$x][$y] = $temp;
    }

    private function findStart(): array
    {
        $row = current($this->grid);
        foreach ($row as $colIndex => $cell) {
            if ($cell === '.') {
                return [0, $colIndex];
            }
        }

        return [0, 0];
    }

    private function findEnd(): array
    {
        $row = end($this->grid);
        foreach ($row as $colIndex => $cell) {
            if ($cell === '.') {
                return [count($this->grid) - 1, $colIndex];
            }
        }

        return [0, 0];
    }
}
