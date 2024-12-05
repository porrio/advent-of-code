<?php

declare(strict_types=1);

namespace App2023\Days;

use Day;

class Day14 extends Day
{
    private array $grid;
    private array $cache;

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();

        $grid = [];
        foreach ($this->data as $key => $row) {
            $grid[$key] = str_split($row);
        }

        $this->grid = $grid;
        $this->cache = [];
    }

    public function partOne(): int
    {
        $this->grid = self::flipGridInDirection($this->grid, 'N');
        return self::calculateLoad();
    }

    public function partTwo(): int
    {
        $iterations = 1000000000;
        $firstOccurrence = $nextOccurrence = -1;

        for ($i = 0; $i < $iterations; $i++) {
            $index = self::checkSeenBefore();

            if ($index !== -1) {
                $firstOccurrence = $index;
                $nextOccurrence = $i;
                break;
            } else {
                $this->cache[$i] = $this->grid;
            }

            self::spinCycle();
        }

        $delta = $nextOccurrence - $firstOccurrence;
        $idx = ($iterations - $firstOccurrence) % $delta + $firstOccurrence;
        $this->grid = $this->cache[$idx];

        return self::calculateLoad();
    }

    function spinCycle(): void
    {
        $grid = self::flipGridInDirection($this->grid, 'N');
        $grid = self::flipGridInDirection($grid, 'W');
        $grid = self::flipGridInDirection($grid, 'S');
        $this->grid = self::flipGridInDirection($grid, 'E');
    }

    private function calculateLoad(): int
    {
        $load = 0;
        $modifier = count($this->grid);
        foreach ($this->grid as $row) {
            $amount = count(array_keys($row, 'O'));
            $load += $amount * $modifier;
            $modifier--;
        }

        return $load;
    }

    private function flipGridInDirection($grid, string $direction): array
    {
        switch ($direction) {
            case 'N':
                foreach ($grid as $y => $row) {
                    foreach ($row as $x => $pos) {
                        if ($y > 0 && $pos === 'O') {
                            $prevKey = $y;
                            $nextKey = $y -1;

                            while (array_key_exists($nextKey, $grid) && $grid[$nextKey][$x] === '.') {
                                $grid[$nextKey][$x] = 'O';
                                $grid[$prevKey][$x] = '.';
                                $nextKey--;
                                $prevKey--;
                            }
                        }
                    }
                }
                break;
            case 'W':
                foreach ($grid as $y => $row) {
                    foreach ($row as $x => $pos) {
                        if ($x > 0 && $pos === 'O') {
                            $prevKey = $x;
                            $nextKey = $x -1;

                            while (array_key_exists($nextKey, $grid[$y]) && $grid[$y][$nextKey] === '.') {
                                $grid[$y][$nextKey] = 'O';
                                $grid[$y][$prevKey] = '.';
                                $nextKey--;
                                $prevKey--;
                            }
                        }
                    }
                }
                break;
            case 'S':
                krsort($grid, SORT_DESC);
                $grid = array_values($grid);
                $grid = self::flipGridInDirection($grid, 'N');
                krsort($grid, SORT_ASC);
                $grid = array_values($grid);
                break;
            case 'E':
                foreach ($grid as $key => $row) {
                    krsort($row, SORT_DESC);
                    $grid[$key] = array_values($row);
                }

                $grid = self::flipGridInDirection($grid, 'W');

                foreach ($grid as $key => $row) {
                    krsort($row, SORT_ASC);
                    $grid[$key] = array_values($row);
                }

                break;
        }

        return $grid;
    }

    function checkSeenBefore(): int
    {
        for ($i = 0; $i < count($this->cache); $i++) {
            if ($this->grid === $this->cache[$i]) {
                return $i;
            }
        }

        return -1;
    }
}