<?php

declare(strict_types=1);

namespace App2023\Days;

use Day;

class Day5 extends Day
{
    private array $almanac;
    private bool $needsCleaning;

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData(PHP_EOL . PHP_EOL);

        $this->almanac = [];
        $this->needsCleaning = true;
    }
    
    public function partOne(): int
    {
        if ($this->needsCleaning === true) {
            self::cleanData();
        }

        $seeds = $this->almanac['seeds'];
        $lowestLocation = null;

        foreach ($seeds as $seed) {
            $location = self::findLocationForSeed((int)$seed);
            if ($lowestLocation === null) {
                $lowestLocation = $location;
            }

            if ($location < $lowestLocation) {
                $lowestLocation = $location;
            }
        }

        return (int)$lowestLocation;
    }

    public function partTwo(): int
    {
        if ($this->needsCleaning === true) {
            self::cleanData();
        }

        $seedStarts = [];
        foreach ($this->almanac['seeds'] as $key => $seed) {
            if ($key % 2 !== 0) {
                continue;
            }

            $seedStarts[] = $seed;
        }

        $locationRangeMinMax = 0;
        foreach ($this->almanac['humidity-to-location map'] as $possibleLocation) {
            $locationRange = $possibleLocation['srs'] + $possibleLocation['rl'];
            if ($locationRange < $locationRangeMinMax) {
                $locationRangeMinMax = $locationRange;
            }

            if ($locationRangeMinMax === 0) {
                $locationRangeMinMax = $locationRange;
            }
        }

        $step = 10000;
        $guessedLocation = self::loop((int)min($seedStarts), $locationRangeMinMax, $step);
        return self::loop($guessedLocation - $step, $guessedLocation, 1);
    }

    private function cleanData(): void
    {
        foreach ($this->data as $map) {
            $mappingName = strstr($map, ':', true);
            $mapping = str_replace(':', '', strstr($map, ':'));

            if ($mappingName === 'seeds') {
                $mapping = str_getcsv(trim($mapping), ' ');
            } else {
                $values = str_getcsv(trim($mapping), PHP_EOL);

                $mapping = [];
                foreach ($values as $mapper) {
                    $mapperValues = explode(' ', $mapper);

                    if ($mapperValues[2] > 0) {
                        $mapperValues[2] -= 1;
                    }

                    $mapping[] = [
                        'drs' => $mapperValues[0],
                        'srs' => $mapperValues[1],
                        'rl' => $mapperValues[2],
                    ];
                }
            }
            $this->almanac[$mappingName] = $mapping;
        }

        $this->needsCleaning = false;
    }
    
    private function findLocationForSeed(int $seed): int
    {
        $soil = self::findCorrespondingNrInMapping($seed, $this->almanac['seed-to-soil map']);
        $fertilizer = self::findCorrespondingNrInMapping($soil, $this->almanac['soil-to-fertilizer map']);
        $water = self::findCorrespondingNrInMapping($fertilizer, $this->almanac['fertilizer-to-water map']);
        $light = self::findCorrespondingNrInMapping($water, $this->almanac['water-to-light map']);
        $temperature = self::findCorrespondingNrInMapping($light, $this->almanac['light-to-temperature map']);
        $humidity = self::findCorrespondingNrInMapping($temperature, $this->almanac['temperature-to-humidity map']);

        return self::findCorrespondingNrInMapping($humidity, $this->almanac['humidity-to-location map']);
    }

    private function findCorrespondingNrInMapping(int $nr, array $mapping): int
    {
        $correspondingNr = $nr;

        foreach ($mapping as $map) {
            $sourceRangeStart = $map['srs'];
            $rangeLength = $map['rl'];

            if ($nr >= $sourceRangeStart && $nr <= $sourceRangeStart + $rangeLength) {
                $diff = $nr - $sourceRangeStart;
                $correspondingNr = $map['drs'] + $diff;
                break;
            }
        }

        return $correspondingNr;
    }

    private function loop(int $start, int $end, int $step): ?int
    {
        $lowestLocation = null;

        for ($i = $start; $i <= $end; $i += $step) {
            $lowestLocation = self::reverseCalculateSeedFromLocation($i);

            if ($lowestLocation !== null) {
                return $lowestLocation;
            }
        }

        return $lowestLocation;
    }

    private function reverseCalculateSeedFromLocation(int $location): ?int
    {
        $humidity = self::findCorrespondingNrInMappingReversed($location, $this->almanac['humidity-to-location map']);
        $temperature = self::findCorrespondingNrInMappingReversed($humidity, $this->almanac['temperature-to-humidity map']);
        $light = self::findCorrespondingNrInMappingReversed($temperature, $this->almanac['light-to-temperature map']);
        $water = self::findCorrespondingNrInMappingReversed($light, $this->almanac['water-to-light map']);
        $fertilizer = self::findCorrespondingNrInMappingReversed($water, $this->almanac['fertilizer-to-water map']);
        $soil = self::findCorrespondingNrInMappingReversed($fertilizer, $this->almanac['soil-to-fertilizer map']);
        $seed = self::findCorrespondingNrInMappingReversed($soil, $this->almanac['seed-to-soil map']);

        $seeds = $this->almanac['seeds'];

        foreach ($seeds as $key => $checkSeed) {
            if ($key % 2 !== 0) {
                continue;
            }

            $seedRange = $seeds[$key + 1] + 1;

            if ($seed >= $checkSeed && $seed <= $checkSeed + $seedRange) {
                return $location;
            }
        }

        return null;
    }

    private function findCorrespondingNrInMappingReversed(int $nr, array $mapping): int
    {
        $correspondingNr = $nr;

        foreach ($mapping as $map) {
            $sourceRangeStart = $map['drs'];

            if ($nr >= $sourceRangeStart && $nr <= $sourceRangeStart + $map['rl']) {
                $correspondingNr = $map['srs'] + ($nr - $sourceRangeStart);
                break;
            }
        }

        return $correspondingNr;
    }
}
