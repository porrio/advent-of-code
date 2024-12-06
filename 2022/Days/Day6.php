<?php

declare(strict_types=1);

namespace App2022\Days;

use Day;

class Day6 extends Day
{
    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData(PHP_EOL, 'string');
    }

    public function partOne(): int
    {
        return $this->findStartOfPacketMarker(4);
    }

    public function partTwo(): int
    {
        return $this->findStartOfPacketMarker(14);
    }

    function findStartOfPacketMarker(int $markerLength): int
    {
        $window = [];

        for ($i = 0, $len = strlen($this->data); $i < $len; $i++) {
            $window[] = $this->data[$i];

            if (count($window) > $markerLength) {
                array_shift($window);
            }

            if (count($window) === $markerLength && count(array_unique($window)) === $markerLength) {
                return $i + 1;
            }
        }

        return -1;
    }
}
