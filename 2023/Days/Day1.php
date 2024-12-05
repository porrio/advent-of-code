<?php

declare(strict_types=1);

namespace App2023\Days;

use Day;

class Day1 extends Day
{
    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();
    }

    public function partOne(): int
    {
        $values = [];
        foreach ($this->data as $line) {
            $line = preg_replace('/\D/', '', $line);
            $values[] = substr($line, 0, 1) . substr($line, -1);
        }

        return array_sum($values);
    }

    public function PartTwo(): int
    {
        $digitMapping = [
            'one' => '1',
            'two' => '2',
            'three' => '3',
            'four' => '4',
            'five' => '5',
            'six' => '6',
            'seven' => '7',
            'eight' => '8',
            'nine' => '9',
        ];

        $values = [];
        foreach ($this->data as $line) {
            $digitPositions = [];
            foreach ($digitMapping as $spelledOut => $digit) {
                $i = 0;
                while (strpos($line, $spelledOut, $i) !== false) {
                    $digitPositions[strpos($line, $spelledOut, $i)] = (int)$digit;
                    $i++;
                }

                $i = 0;
                while (strpos($line, $digit, $i) !== false) {
                    $digitPositions[strpos($line, $digit, $i)] = (int)$digit;
                    $i++;
                }
            }

            ksort($digitPositions);
            $value = reset($digitPositions) . end($digitPositions);
            $values[] = (int)$value;
        }

        return array_sum($values);
    }
}
