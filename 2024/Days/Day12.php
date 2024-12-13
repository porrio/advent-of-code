<?php

declare(strict_types=1);

namespace App2024\Days;

use Day;

class Day12 extends Day
{
    private const DIRECTIONS = [[-1, 0], [1, 0], [0, -1], [0, 1]];
    private array $visited;
    private int $rows;
    private int $cols;
    private array $prices;

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();
        $this->rows = count($this->data);
        $this->cols = strlen($this->data[0]);
        $this->visited = array_fill(0, $this->rows, array_fill(0, $this->cols, false));
        $this->prices = $this->calculateTotalFencingPrice();
    }

    public function partOne(): int
    {
        return $this->prices[1];
    }

    public function partTwo(): int
    {
        return $this->prices[2];
    }

    function calculateTotalFencingPrice(): array
    {
        $totalPrices = [
            1 => 0,
            2 => 0,
        ];

        for ($x = 0; $x < $this->rows; $x++) {
            for ($y = 0; $y < $this->cols; $y++) {
                if (!$this->visited[$x][$y]) {
                    $type = $this->data[$x][$y];
                    [$area, $perimeter, $sides] = $this->findRegion($x, $y, $type);

                    $totalPrices[1] += $area * $perimeter;
                    $totalPrices[2] += $area * $sides;

                }
            }
        }

        return $totalPrices;
    }

    function findRegion($x, $y, $type): array
    {
        $stack = [[$x, $y]];
        $area = 0;
        $perimeter = 0;
        $sides = 0;

        while (!empty($stack)) {
            [$cx, $cy] = array_pop($stack);
            if ($this->visited[$cx][$cy]) {
                continue;
            }

            $this->visited[$cx][$cy] = true;
            $area++;

            foreach (self::DIRECTIONS as [$dx, $dy]) {
                $nx = $cx + $dx;
                $ny = $cy + $dy;

                if ($nx < 0 || $ny < 0 || $nx >= $this->rows || $ny >= $this->cols) {
                    $perimeter++;
                } elseif ($this->data[$nx][$ny] !== $type) {
                    $perimeter++;
                } elseif (!$this->visited[$nx][$ny]) {
                    $stack[] = [$nx, $ny];
                }
            }
        }

        return [$area, $perimeter, $sides];
    }
}