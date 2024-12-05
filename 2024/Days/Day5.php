<?php

declare(strict_types=1);

namespace App2024\Days;

use Day;

class Day5 extends Day
{
    private array $rules;
    private array $updates;
    private int $part1 = 0;
    private int $part2 = 0;

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();

        $this->getData();
        $this->sumMiddlePagesOfCorrectUpdates();
    }
    
    private function getData(): void
    {
        $addRules = true;
        foreach ($this->data as $line) {
            if (strlen($line) === 0) {
                $addRules = false;
                continue;
            }

            if ($addRules) {
                $this->rules[] = $line;
            } else {
                $this->updates[] = $line;
            }
        }
    }

    public function partOne(): int
    {
        return $this->part1;
    }

    public function partTwo(): int
    {
        return $this->part2;
    }

    private function sumMiddlePagesOfCorrectUpdates(): void
    {
        $rulesArray = [];
        foreach ($this->rules as $rule) {
            [$x, $y] = explode('|', $rule);
            $rulesArray[] = [(int)$x, (int)$y];
        }

        foreach ($this->updates as $update) {
            $updateArray = array_map('intval', explode(',', $update));

            if ($this->isUpdateCorrectlyOrdered($rulesArray, $updateArray)) {
                $middleIndex = floor(count($updateArray) / 2);
                $this->part1 += $updateArray[$middleIndex];
            } else {
                $correctedUpdate = $this->applyCorrectSort($rulesArray, $updateArray);
                $middleIndex = floor(count($correctedUpdate) / 2);
                $this->part2 += $correctedUpdate[$middleIndex];
            }
        }
    }

    private function isUpdateCorrectlyOrdered($rules, $update): bool
    {
        $updatePages = array_flip($update);

        foreach ($rules as [$x, $y]) {
            if (isset($updatePages[$x]) && isset($updatePages[$y])) {
                if ($updatePages[$x] > $updatePages[$y]) {
                    return false;
                }
            }
        }

        return true;
    }

    function applyCorrectSort($rules, $updateArray): array
    {
        $graph = [];
        $inDegree = [];

        foreach ($updateArray as $page) {
            $graph[$page] = [];
            $inDegree[$page] = 0;
        }

        foreach ($rules as $rule) {
            [$before, $after] = $rule;

            if (in_array($before, $updateArray) && in_array($after, $updateArray)) {
                $graph[$before][] = $after;
                $inDegree[$after]++; 
            }
        }

        $queue = [];
        $sortedOrder = [];

        foreach ($inDegree as $node => $degree) {
            if ($degree === 0) {
                $queue[] = $node;
            }
        }

        while (!empty($queue)) {
            $current = array_shift($queue);
            $sortedOrder[] = $current;

            foreach ($graph[$current] as $neighbor) {
                $inDegree[$neighbor]--;
                if ($inDegree[$neighbor] === 0) {
                    $queue[] = $neighbor;
                }
            }
        }

        return $sortedOrder;
    }
}