<?php

declare(strict_types=1);

namespace App2023\Days;

use Day;

class Day8 extends Day
{
    private array $instructions;
    private array $cleanMap;
    private bool $needsCleaning;

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();

        $this->instructions = str_split($this->data[0]);
        unset($this->data[0]);
        unset($this->data[1]);

        $this->needsCleaning = true;
    }

    public function partOne(): int
    {
        self::cleanMap();
        return self::navigateToEndpoint('AAA', 'ZZZ');
    }

    /**
     * @throws Exception
     */
    public function partTwo(): int
    {
        self::cleanMap();

        $startingPoints = [];
        $endPoints = [];
        foreach ($this->cleanMap as $position => $directions) {
            if (parent::str_ends_with($position, 'A')) {
                $startingPoints[] = $position;
            }

            if (parent::str_ends_with($position, 'Z')) {
                $endPoints[] = $position;
            }
        }

        $cycles = 0;
        $positions = $startingPoints;
        $endpointReachedSteps = [];
        while (count($endpointReachedSteps) !== count($endPoints)) {
            foreach ($this->instructions as $instruction) {
                $cycles++;

                foreach ($positions as $key => $currentPosition) {
                    $nextPosition = self::step($currentPosition, $instruction);
                    $positions[$key] = $nextPosition;
                }

                foreach ($positions as $position) {
                    if (parent::str_ends_with($position, 'Z')) {
                        $endpointReachedSteps[$position] = $cycles;
                    }
                }
            }
        }

        return parent::lcm(array_values($endpointReachedSteps));
    }
    
    private function cleanMap(): void
    {
        if ($this->needsCleaning === false) {
            return;
        }

        foreach ($this->data as $map) {
            $position = strstr($map, ' ', true);
            $directions = strstr($map, '(');
            $left = str_replace('(', '', strstr($directions, ',', true));
            $right = str_replace([')', ', '], '', strstr($directions, ','));
            $this->cleanMap[$position] = [
                'L' => $left,
                'R' => $right
            ];
        }

        $this->needsCleaning = false;
    }

    private function navigateToEndpoint(string $startPoint, string $endPoint): int
    {
        $steps = 0;
        $endpointReached = false;

        while ($endpointReached === false) {
            foreach ($this->instructions as $instruction) {
                $nextPosition = self::step($startPoint, $instruction);
                $steps++;
                $startPoint = $nextPosition;
                if ($startPoint === $endPoint) {
                    $endpointReached = true;
                }
            }
        }

        return $steps;
    }
    
    private function step($currentPosition, $instruction)
    {
        return $this->cleanMap[$currentPosition][$instruction];
    }
}