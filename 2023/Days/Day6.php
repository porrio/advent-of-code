<?php

declare(strict_types=1);


namespace App2023\Days;

use Day;

class Day6 extends Day
{
    private array $raceTimes;
    private array $distances;
    private bool $needsFetching;

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();

        $this->raceTimes = [];
        $this->distances = [];
        $this->needsFetching = true;
    }

    public function partOne(): int
    {
        if ($this->needsFetching === true) {
            self::fetchRaceTimesAndDistances();
        }

        $winningOptionsPerRace = [];
        foreach ($this->raceTimes as $raceNr => $raceTime) {
            $distance = $this->distances[$raceNr];
            $winningOptions = self::getWinningOptionsForRace((int)$raceTime, (int)$distance);
            $winningOptionsPerRace[] = $winningOptions;
        }

        return array_product($winningOptionsPerRace);
    }

    public function partTwo(): int
    {
        if ($this->needsFetching === true) {
            self::fetchRaceTimesAndDistances();
        }

        $totalRaceTime = (int)implode('', $this->raceTimes);
        $totalDistance = (int)implode('', $this->distances);
        return self::getWinningOptionsForRace($totalRaceTime, $totalDistance);
    }

    private function fetchRaceTimesAndDistances(): void
    {
        foreach ($this->data as $key => $data) {
            if ($key === 0) {
                $data = trim(str_replace('Time:', '', $data));
                $this->raceTimes = str_getcsv($data, ' ');
            }

            if ($key === 1) {
                $data = trim(str_replace('Distance:', '', $data));
                $this->distances = str_getcsv($data, ' ');
            }
        }

        $this->needsFetching = false;
    }

    private function getWinningOptionsForRace(int $time, int $recordDistance): int
    {
        $winningOptions = 0;
        for ($i = $time; $i > 0; $i--) {
            if (($time - $i) * $i > $recordDistance) {
                $winningOptions++;
            }
        }

        return $winningOptions;
    }
}
