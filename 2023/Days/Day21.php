<?php

declare(strict_types=1);

namespace App2023\Days;

use Day;

class Day21 extends Day
{
    private array $garden;
    private bool $expandGarden;
    private array $visited;
    private int $rows;
    private int $cols;
    private array $validPoints;

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();

        $this->garden = [];
        self::fetchGarden();
        $this->expandGarden = false;
        $this->rows = count($this->garden) - 1;
        $this->cols = count($this->garden[0]) - 1;
        $this->steps = 0;
        $this->visited = [];
        $this->validPoints = ['.', 'S'];
    }

    public function partOne(): int
    {
        $steps = 64;
        $this->visited = [];
        $this->expandGarden = false;
        $this->setStep($this->findStartPoint());

        for ($i = 1; $i < $steps; $i++) {
            $this->setSteps();
        }

        return count($this->findAllGardenPlots());
    }

    public function partTwo(): int
    {
        $this->expandGarden = true;
        $this->garden = [];
        $this->visited = [];
        self::fetchGarden();

        $steps = 100;
        $this->setStep($this->findStartPoint());

        for ($i = 1; $i < $steps; $i++) {
            $this->setSteps();
        }

        return count($this->findAllGardenPlots());
    }

    private function fetchGarden(): void
    {
        foreach ($this->data as $row) {
            $row = str_split($row);
            $this->garden[] = $row;
        }
    }

    private function findStartPoint(): array
    {
        foreach ($this->data as $x => $row) {
            if (strpos($row, 'S') !== false) {
                return [$x, strpos($row, 'S')];
            }
        }

        return [0, 0];
    }

    private function setStep(array $plot)
    {
        [$x, $y] = $plot;

        if ($this->expandGarden === true) {
            $ox = $this->getX($x);//x of original garden
            $oy = $this->getY($y);//y of original garden

            $nx = $this->getX($ox - 1);
            if (!isset($this->visited[$x - 1][$y]) && in_array($this->garden[$nx][$oy], $this->validPoints)) {
                $this->visited[$x - 1][$y] = 1;
            }

            $nx = $this->getX($ox + 1);
            if (!isset($this->visited[$x + 1][$y]) && in_array($this->garden[$nx][$oy], $this->validPoints)) {
                $this->visited[$x + 1][$y] = 1;
            }

            $ny = $this->getY($oy - 1);
            if (!isset($this->visited[$x][$y - 1]) && in_array($this->garden[$ox][$ny], $this->validPoints)) {
                $this->visited[$x][$y - 1] = 1;
            }

            $ny = $this->getY($oy + 1);
            if (!isset($this->visited[$x][$y + 1]) && in_array($this->garden[$ox][$ny], $this->validPoints)) {
                $this->visited[$x][$y + 1] = 1;
            }

            unset($this->visited[$x][$y]);
        } else {
            if ($x - 1 >= 0 && in_array($this->garden[$x - 1][$y], $this->validPoints)) {
                $this->garden[$x - 1][$y] = 'O';
            }
        
            if ($x + 1 <= count($this->garden) && in_array($this->garden[$x + 1][$y], $this->validPoints)) {
                $this->garden[$x + 1][$y] = 'O';
            }
        
            if ($y - 1 >= 0 && in_array($this->garden[$x][$y - 1], $this->validPoints)) {
                $this->garden[$x][$y - 1] = 'O';
            }
        
            if ($y + 1 >= 0 && in_array($this->garden[$x][$y + 1], $this->validPoints)) {
                $this->garden[$x][$y + 1] = 'O';
            }
        
            $this->garden[$x][$y] = '.';
        }
    }

    private function setSteps(): void
    {
        foreach ($this->findAllGardenPlots() as $plot) {
            $this->setStep($plot);
        }
    }

    private function findAllGardenPlots(): array
    {
        $gardenPlots = [];

        if ($this->expandGarden === false) {
            for ($i = 0; $i < count($this->garden); $i++) {
                for ($j = 0; $j < count($this->garden[0]); $j++) {
                    if ($this->garden[$i][$j] === 'O') {
                        $gardenPlots[] = [$i, $j];
                    }
                }
            }
        } else {
            foreach ($this->visited as $x => $rows) {
                foreach ($rows as $y => $plot) {
                    $gardenPlots[] = [$x, $y];
                }
            }
        }

        return $gardenPlots;
    }

    private function getX(int $x): int
    {
        if ($x < 0) {
            $x = ($x % ($this->rows + 1) + ($this->rows + 1)) % ($this->rows + 1);
        } elseif ($x > $this->rows) {
            $x = $x % ($this->rows + 1);
        }

        return $x;
    }

    private function getY(int $y): int
    {
        if ($y < 0) {
            $y = ($y % ($this->cols + 1) + ($this->cols + 1)) % ($this->cols + 1);
        } elseif ($y > $this->cols) {
            $y = $y % ($this->cols + 1);
        }

        return $y;
    }
}
