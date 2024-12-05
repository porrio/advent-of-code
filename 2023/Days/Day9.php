<?php

declare(strict_types=1);

namespace App2023\Days;

use Day;

class Day9 extends Day
{
    private array $histories;

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();

        foreach ($this->data as $history) {
            $this->histories[] = str_getcsv($history, ' ');
        }
    }

    public function partOne(): int
    {
        return array_sum(array_map([$this, 'extrapolateNext'], $this->histories));
    }

    public function partTwo(): int
    {
        return array_sum(array_map([$this, 'extrapolatePrevious'], $this->histories));
    }
    
    private function getSequences($sequence): array
    {
        $differences[] = $sequence;
        $sec = $sequence;
        $break = false;

        while ($break === false) {
            $difRow = [];
            for ($i = 1; $i < count($sec); $i++) {
                $difRow[] = $sec[$i] - $sec[$i - 1];
            }

            $differences[] = $difRow;
            $sec = $difRow;

            $break = count(array_unique($difRow, SORT_REGULAR)) === 1 && current($difRow) == 0;
        }

        krsort($differences, SORT_DESC);
        return $differences;
    }

    private function extrapolateNext($sequence): int
    {
        $sequences = self::getSequences($sequence);

        $diff = 0;
        foreach ($sequences as $key => $value) {
            $sequences[$key][] = end($value) + $diff;
            $diff = end($value) + $diff;
        }

        return $diff;
    }


    private function extrapolatePrevious($sequence): int
    {
        $sequences = self::getSequences($sequence);

        $diff = 0;
        foreach ($sequences as $key => $value) {
            $sequences[$key][-1] = current($value) - $diff;
            $diff = current($value) - $diff;
        }

        return $diff;
    }
}