<?php

declare(strict_types=1);

namespace App2024\Days;

use Day;

class Day9 extends Day
{
    private array $formattedDisk = [];

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData(PHP_EOL, 'string');
        $this->formatDisk();
    }

    public function partOne(): int
    {
        $compactedDisk = $this->compactDisk();
        return $this->getCheckSum($compactedDisk);
    }

    public function partTwo(): int
    {
        $compactedDisk = $this->compactDisk(true);
        return $this->getCheckSum($compactedDisk);
    }

    private function formatDisk(): void
    {
        $diskMap = $this->data;
        $blocks = [];
        $fileID = 0;

        $length = strlen($diskMap);

        for ($i = 0; $i < $length; $i++) {
            $fileLength = (int)$diskMap[$i];
            $i++;
            $freeSpaceLength = (int)$diskMap[$i];

            for ($j = 0; $j < $fileLength; $j++) {
                $blocks[] = $fileID;
            }

            for ($j = 0; $j < $freeSpaceLength; $j++) {
                $blocks[] = '.';
            }

            $fileID++;
        }

        $this->formattedDisk = $blocks;
    }

    private function compactDisk(bool $fullFileMove = false): array
    {
        $disk = $this->formattedDisk;

        for ($i = count($disk) - 1; $i >= 0; $i--) {
            if ($disk[$i] !== '.') {
                $length = 1;

                if ($fullFileMove === true) {
                    while ($i - $length >= 0 && $disk[$i - $length] === $disk[$i]) {
                        $length++;
                    }
                }

                $leftmostFreeSpace = -1;

                for ($j = 0; $j < $i; $j++) {
                    if ($disk[$j] === '.') {
                        $freeSpaceLength = 1;

                        if ($fullFileMove === true) {
                            while ($j + $freeSpaceLength < $i && $disk[$j + $freeSpaceLength] === '.') {
                                $freeSpaceLength++;
                            }
                        }

                        if ($length <= $freeSpaceLength) {
                            $leftmostFreeSpace = $j;
                            break;
                        }
                    }
                }

                if ($leftmostFreeSpace !== -1) {
                    for ($l = 0; $l < $length; $l++) {
                        $disk[$leftmostFreeSpace + $l] = $disk[$i - $l];
                        $disk[$i - $l] = '.';
                    }
                }

                $i -= ($length - 1);
            }
        }

        return $disk;
    }

    private function getCheckSum(array $compactedDisk): int
    {
        $checksum = 0;

        foreach ($compactedDisk as $position => $fileID) {
            if ($fileID !== '.') {
                $checksum += $position * $fileID;
            }
        }

        return $checksum;
    }
}