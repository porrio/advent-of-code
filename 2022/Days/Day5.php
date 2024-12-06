<?php

declare(strict_types=1);

namespace App2022\Days;

use Day;

class Day5 extends Day
{
    private array $cargoBay = [];
    private array $instruction = [];

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData(PHP_EOL . PHP_EOL);
    }

    public function partOne(): string
    {
        return $this->moveCrates();
    }

    public function partTwo(): string
    {
        return $this->moveCrates(false);
    }

    private function fetchCargoBay(): void
    {
        $cargoBayColumns = explode(PHP_EOL,  $this->data[0]);

        $result = [];
        foreach ($cargoBayColumns as $cargoBayColumn) {
            $crates = str_split($cargoBayColumn, 4);

            foreach ($crates as $key => $crate) {
                $key++;

                // Find crate
                preg_match('/[a-zA-Z]+/', $crate, $letter);

                if (!empty($letter[0])) {
                    !empty($result[$key]) ? array_unshift($result[$key], $letter[0]) : $result[$key][] = $letter[0];
                }
            }
        }

        ksort($result, SORT_NUMERIC);

        $this->cargoBay = $result;
    }

    private function fetchInstructions(): void
    {
        $lines = explode(PHP_EOL, $this->data[1]);

        $result = [];
        foreach ($lines as $key => $line) {
            preg_match_all('/(.*?)\s[0-9]+/', $line, $matches);

            foreach ($matches[0] as $instruction) {
                list($move, $step) = explode(' ', trim($instruction));
                $result[$key][$move] = $step;
            }
        }

        $this->instruction =  $result;
    }

    private function moveCrates(bool $sortCrates = true): string
    {
        $this->fetchCargoBay();
        $this->fetchInstructions();

        foreach ($this->instruction as $step) {
            $quantity = (int)$step['move'];
            $from = (int)$step['from'];
            $to = (int)$step['to'];

            $crates = array_slice($this->cargoBay[$from], -$quantity);

            if ($sortCrates === true) {
                krsort($crates, SORT_NUMERIC);
            }

            array_splice($this->cargoBay[$from], count($this->cargoBay[$from]) - $quantity, $quantity);
            $this->cargoBay[$to] = array_merge($this->cargoBay[$to], $crates);
        }

        $message = '';
        foreach ($this->cargoBay as $column) {
            $message .= end($column);
        }

        return $message;
    }
}