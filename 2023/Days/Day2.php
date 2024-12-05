<?php

declare(strict_types=1);

namespace App2023\Days;

use Day;

class Day2 extends Day
{
    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();
    }

    public function partOne(): int
    {
        $result = 0;
        foreach ($this->data as $game) {
            if (self::validateGame($game)) {
                $result += self::fetchGameId($game);
            }
        }

        return $result;
    }

    public function partTwo(): int
    {
        $result = 0;
        foreach ($this->data as $game) {
            $sets = str_getcsv(trim(str_replace('Game ' . self::fetchGameId($game) . ': ', '', $game)), ';');

            $results = [];
            foreach ($sets as $set) {
                $round = str_getcsv(trim($set), ',');
                $colors = self::getAmountPerColor($round);

                foreach ($colors as $color => $amount) {
                    if (!array_key_exists($color, $results)) {
                        $results[$color] = $amount;
                    }

                    if ($results[$color] < $amount) {
                        $results[$color] = $amount;
                    }
                }
            }

            $result += array_product($results);
        }

        return $result;
    }

    private function fetchGameId(string $game): int
    {
        return (int)strstr(str_replace('Game ', '', $game), ':', true);
    }

    private function validateGame(string $game): bool
    {
        $sets = str_getcsv(trim(str_replace('Game ' . self::fetchGameId($game) . ': ', '', $game)), ';');
        foreach ($sets as $set) {
            $round = str_getcsv(trim($set), ',');
            $valid = self::validateRound($round);
            if ($valid === false) {
                return false;
            }
        }

        return true;
    }

    private function validateRound(array $round): bool
    {
        $cubes = [
            'red' => 12,
            'green' => 13,
            'blue' => 14
        ];

        foreach ($round as $set) {
            $color = trim(preg_replace('/\d+\s+/', '', $set));
            $amount = preg_replace('/\D/', '', $set);

            if (!array_key_exists($color, $cubes)) {
                return false;
            }

            $maxAmountForColor = $cubes[$color];
            if ((int)$amount > $maxAmountForColor) {
                return false;
            }
        }

        return true;
    }

    private function getAmountPerColor(array $sets): array
    {
        $results = [];
        foreach ($sets as $set) {
            $color = trim(preg_replace('/\d+\s+/', '', $set));
            $amount = preg_replace('/\D/', '', $set);

            if (!array_key_exists($color, $results)) {
                $results[$color] = $amount;
            }

            if ($results[$color] < $amount) {
                $results[$color] = $amount;
            }
        }

        return $results;
    }
}
