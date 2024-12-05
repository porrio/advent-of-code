<?php

declare(strict_types=1);

namespace App2023\Days;

use Day;

class Day13 extends Day
{
    private bool $repairSmudge;

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData(PHP_EOL . PHP_EOL);

        $this->repairSmudge = false;
    }

    public function partOne(): int
    {
        return self::summarizeReflections();
    }

    public function partTwo(): int
    {
        $this->repairSmudge = true;
        return self::summarizeReflections();
    }

    private function summarizeReflections(): int
    {
        $total = 0;

        foreach ($this->data as $pattern) {
            $reflectionLines = self::findReflectionLine($pattern);
            $total += $reflectionLines['vertical'] + (100 * $reflectionLines['horizontal']);
        }

        return $total;
    }

    private function findReflectionLine(string $pattern): array
    {
        $rows = explode(PHP_EOL, $pattern);
        $cols = strlen($rows[0]);

        $rows = $this->repairSmudge ? self::repairSmudge($rows) : $rows;

        //create array with columns as row
        $columns = [];
        for ($j = 0; $j < $cols; $j++) {
            $col = '';
            for ($i = 0; $i < count($rows); $i++) {
                $col .= substr($rows[$i], $j, 1);
            }

            $columns[] = $col;
        }



        $vertical = 0;
        $horizontal = self::findFoldLine($rows);

        if ($horizontal === 0) {
            $columns = $this->repairSmudge ? self::repairSmudge($columns) : $columns;
            $vertical = self::findFoldLine($columns);
        }

        return [
            'horizontal' => $horizontal,
            'vertical' => $vertical,
        ];
    }
    
    private function findFoldLine(array $rows): int
    {
        $foldLine = 0;
        $prev = '';
        foreach ($rows as $key => $row) {
            if ($row === $prev) {
                $foldLength = 0;

                for ($i = 0; $i <= $key; $i++) {
                    $comparedRow = $rows[$key - ($i + 1)] === $rows[$key + $i];

                    if ($comparedRow) {
                        $foldLength++;
                    }
                }

                if ($foldLength === $key || $foldLength === count($rows) - $key + 1) {
                    return $key;
                }
            }

            $prev = $row;
        }

        return $foldLine;
    }

    private function repairSmudge(array $rows): array
    {
        //TODO implement die shit ff goed
        foreach ($rows as $row) {
            for ($i = 0; $i < count($rows); $i++) {

                $isSmudged = self::diff(str_split($row), str_split($rows[$i])) === 1;

                if ($isSmudged) {
                    $rows[$i] = $row;
                    return $rows;
                }
            }
        }

        return $rows;
    }

    private function diff(array $a, array $b): int
    {
        $diff = 0;
        foreach ($a as $key => $value) {
            if ($b[$key] !== $value) {
                $diff++;
            }
        }

        return $diff;
    }
}