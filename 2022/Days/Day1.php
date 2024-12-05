<?php

declare(strict_types=1);

namespace App2022\Days;

use Day;

class Day1 extends Day
{
    private array $elvesCalories = [];

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData(PHP_EOL . PHP_EOL);
        $this->fillElvesCalories();
    }

    public function partOne(): int
    {
        return max($this->elvesCalories);
    }

    public function partTwo(): int
    {
        sort($this->elvesCalories);
        return array_sum(array_slice($this->elvesCalories, -3, 3, true));
    }

    private function fillElvesCalories(): void
    {
        foreach ($this->data as $key => $elv) {
            $calories = array_sum(explode(PHP_EOL, $elv));
            $this->elvesCalories[$key] = $calories;
        }
    }
}
