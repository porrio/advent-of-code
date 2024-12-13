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
        $compactedDisk = $this->compactDisk1();
        return $this->getCheckSum($compactedDisk);
    }

    public function partTwo(): int
    {
        $compactedDisk = $this->compactDisk2();
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

    private function compactDisk1(): array
    {
        $formattedDisk = $this->formattedDisk;
        $fileBlocks  = [];
        $freeSpaces = [];

        foreach ($formattedDisk as $i => $block) {
            if ($block === '.') {
                $freeSpaces[] = $i;
            } else {
                $fileBlocks[] = $i;
            }
        }

        while (!empty($fileBlocks) && !empty($freeSpaces)) {
            $filePos = array_pop($fileBlocks);
            $freePos = array_shift($freeSpaces);

            if ($filePos > $freePos) {
                $formattedDisk[$freePos] = $formattedDisk[$filePos];
                $formattedDisk[$filePos] = '.';

                $freeSpaces[] = $filePos;
            } else {
                break;
            }
        }

        return $formattedDisk;
    }

    private function compactDisk2(): array
    {
        $disk = $this->formattedDisk;
        $size = count($disk);

        for ($i = $size - 1; $i >= 0; $i--) {
            if ($disk[$i] !== '.') {
                $length = 1;

                while ($i - $length >= 0 && $disk[$i - $length] === $disk[$i]) {
                    $length++;
                }

                $leftmostFreeSpace = -1;

                for ($j = 0; $j < $i; $j++) {
                    if ($disk[$j] === '.') {
                        $freeSpaceLength = 1;

                        while ($j + $freeSpaceLength < $i && $disk[$j + $freeSpaceLength] === '.') {
                            $freeSpaceLength++;
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