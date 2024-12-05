<?php

declare(strict_types=1);

namespace App2023\Days;

use Day;

class Day3 extends Day
{
    private bool $needsCycle = true;
    private array $invalidedPositions;
    private array $validPartNumbers;
    private array $gears;

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();

        foreach ($this->data as $x => $line) {
            $this->data[$x] = str_split($line);
        }

        $this->invalidedPositions = [];
        $this->validPartNumbers = [];
        $this->gears = [];
    }
    
    private function cycleParts(): void
    {
        foreach ($this->data as $x => $line) {
            foreach ($line as $y => $linePart) {
                if (in_array($x . '-' . $y, $this->invalidedPositions)) {
                    continue;
                }

                $partNumberPositions = [];
                if (is_numeric($linePart)) {
                    $partNumber = $linePart;
                    $checkY = $y +1;

                    $partNumberPositions[] = [
                        'x' => $x,
                        'y' => $y,
                    ];

                    while (is_numeric($line[$checkY])) {
                        $partNumber .= $line[$checkY];
                        $partNumberPositions[] = [
                            'x' => $x,
                            'y' => $checkY,
                        ];

                        $this->invalidedPositions[] = $x . '-' . $checkY;
                        $checkY += 1;
                    }

                    foreach ($partNumberPositions as $partNumberPosition) {
                        $x = $partNumberPosition['x'];
                        $y = $partNumberPosition['y'];

                        $adjacentPositions = self::findAdjacent($x, $y);
                        foreach ($adjacentPositions as $adjacentPosition) {
                            $checkValue = $this->data[$adjacentPosition['x']][$adjacentPosition['y']];
                            if (!empty($checkValue) && !is_numeric($checkValue) && $checkValue !== '.') {
                                if ($checkValue === '*') {
                                    $key = $adjacentPosition['x'] . '-' . $adjacentPosition['y'];
                                    $this->gears[$key][] = $partNumber;
                                }

                                $this->validPartNumbers[] = $partNumber;
                                break 2;
                            }
                        }
                    }

                    $this->invalidedPositions[] = $x . '-' . $y;
                }
            }
        }

        $this->needsCycle = false;
    }

    public function partOne(): int
    {
        if ($this->needsCycle === true) {
            $this->cycleParts();
        }

        return array_sum($this->validPartNumbers);
    }

    public function partTwo(): int
    {
        if ($this->needsCycle === true) {
            $this->cycleParts();
        }

        $gearRatio = 0;
        foreach ($this->gears as $gear) {
            if (count($gear) > 1) {
                $gearRatio += array_product($gear);
            }
        }

        return $gearRatio;
    }

    private function findAdjacent(int $x, int $y): array
    {
        $adjacent[] = ['x' => $x, 'y' => $y -1];
        $adjacent[] = ['x' => $x, 'y' => $y +1];
        $adjacent[] = ['x' => $x -1, 'y' => $y];
        $adjacent[] = ['x' => $x +1, 'y' => $y];
        $adjacent[] = ['x' => $x -1, 'y' => $y -1];
        $adjacent[] = ['x' => $x -1, 'y' => $y +1];
        $adjacent[] = ['x' => $x +1, 'y' => $y -1];
        $adjacent[] = ['x' => $x +1, 'y' => $y +1];

        return $adjacent;
    }
}
