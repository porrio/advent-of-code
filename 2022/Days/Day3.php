<?php

declare(strict_types=1);

namespace App2022\Days;

use Day;

class Day3 extends Day
{
    private array $items;

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();
        $this->fetchItems();
    }

    public function partOne(): int
    {
        return $this->getScoreForItems($this->items[1]);
    }

    public function partTwo(): int
    {
        return $this->getScoreForItems($this->items[2]);
    }

    private function fetchItems(): void
    {
        foreach ($this->data as $rucksack) {
            $compartments = str_split($rucksack, strlen($rucksack) / 2);
            $compartmentA = str_split($compartments[0]);
            $compartmentB = str_split($compartments[1]);
            $commonItem = implode(array_unique(array_intersect($compartmentA, $compartmentB)));

            $this->items[1][] = $commonItem;
        }

        $groups = array_chunk($this->data, 3);
        foreach ($groups as $group) {
            $bagA = str_split($group[0]);
            $bagB = str_split($group[1]);
            $bagC = str_split($group[2]);
            $badge = implode(array_unique(array_intersect($bagA, $bagB, $bagC)));

            $this->items[2][] = $badge;
        }
    }

    private function getScoreForItems(array $items): int
    {
        $alphabetUpper = range('A', 'Z');
        $alphabetLower = range('a', 'z');
        $alphabet = array_merge($alphabetLower, $alphabetUpper);

        $score = 0;
        foreach ($items as $item) {
            $score += array_search($item, $alphabet) +1;
        }

        return (int)$score;
    }
}