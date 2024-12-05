<?php

declare(strict_types=1);

namespace App2023\Days;

use Day;

class Day4 extends Day
{
    private int $totalScore;
    private array $cardInstances;
    private array $scratchCards;
    private bool $needsCheck = true;

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();

        $this->totalScore = 0;
        $this->cardInstances = [];
        $this->scratchCards = [];
    }


    public function partOne(): int
    {
        if ($this->needsCheck === true) {
            self::checkCards();
        }

        return $this->totalScore;
    }

    public function partTwo(): int
    {
        if ($this->needsCheck === true) {
            self::checkCards();
        }

        return array_sum($this->cardInstances);
    }
    
    private function checkCards(): void
    {
        foreach ($this->data as $rawScratchCart) {
            $cardNr = self::fetchCardNr($rawScratchCart);

            $this->cardInstances[$cardNr] += 1;
            $scratchCard = trim(str_replace('Card ' . $cardNr . ': ', '', $rawScratchCart));
            $scratchCard = str_getcsv($scratchCard, '|');
            $numbers = str_getcsv(str_replace('  ', ' ', trim($scratchCard[0])), ' ');
            $winningNumbers = str_getcsv(str_replace('  ', ' ', trim($scratchCard[1])), ' ');

            $this->scratchCards[$cardNr] = [
                'n' => $numbers,
                'w' => $winningNumbers
            ];
        }

        foreach ($this->scratchCards as $cardNr => $scratchCard) {
            self::checkCard($cardNr, $scratchCard);
        }

        $this->needsCheck = false;
    }

    private function checkCard(int $cardNr, array $scratchCard, bool $updateScore = true): void
    {
        $score = count(array_intersect($scratchCard['n'], $scratchCard['w']));

        if ($score >= 1) {
            if ($updateScore === true) {
                $this->totalScore += 2 ** ($score - 1);
            }

            for ($i = 1; $i <= $score; $i++) {
                $copyId = $cardNr + $i;
                $this->cardInstances[$copyId] += 1;
                self::checkCard($copyId, $this->scratchCards[$copyId], false);
            }
        }
    }

    private function fetchCardNr(string $card): int
    {
        return (int)strstr(str_replace('Card ', '', $card), ':', true);
    }
}
