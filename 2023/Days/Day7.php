<?php

declare(strict_types=1);

namespace App2023\Days;

use Day;

class Day7 extends Day
{
    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();
    }

    public function partOne(): int
    {
        return $this->getTotalScore();
    }

    public function partTwo(): int
    {
        return $this->getTotalScore(true);
    }

    private function getTotalScore($useJokers = false): int
    {
        $analyzedHands = [];
        foreach ($this->data as $hand) {
            $hand = str_getcsv($hand, ' ');
            $analyzedHand = [
                'hand' => $hand[0],
                'bid' => $hand[1],
                'score' => self::getHandScore($hand[0], $useJokers),
            ];

            $analyzedHands[] = $analyzedHand;
        }

        //Sorteren op score bij gelijkspel checken we de 1e kaart anders de 2e etc. ASC
        usort($analyzedHands, function($a, $b) use ($useJokers) {
            return $a['score'] <=> $b['score'] ?:
                self::getCardScore(str_split($a['hand'])[0], $useJokers) <=> self::getCardScore(str_split($b['hand'])[0], $useJokers) ?:
                self::getCardScore(str_split($a['hand'])[1], $useJokers) <=> self::getCardScore(str_split($b['hand'])[1], $useJokers) ?:
                self::getCardScore(str_split($a['hand'])[2], $useJokers) <=> self::getCardScore(str_split($b['hand'])[2], $useJokers) ?:
                self::getCardScore(str_split($a['hand'])[3], $useJokers) <=> self::getCardScore(str_split($b['hand'])[3], $useJokers) ?:
                self::getCardScore(str_split($a['hand'])[4], $useJokers) <=> self::getCardScore(str_split($b['hand'])[4], $useJokers);
        });

        $totalScore = 0;
        foreach ($analyzedHands as $rank => $analyzedHand) {
            $totalScore += ($analyzedHand['bid'] * ($rank + 1));
        }

        return $totalScore;
    }

    private function getCardScore($card, bool $useJokers = false): int
    {
        $cards = [
            'A' => 14,
            'K' => 13,
            'Q' => 12,
            'J' => $useJokers === true ? 1 : 11,
            'T' => 10,
        ];

        if (array_key_exists($card, $cards)) {
            return $cards[$card];
        }

        return (int)$card;
    }
    
    private function getHandScore(string $hand, bool $useJokers = false): int
    {
        $handCards = str_split($hand);
        $values = array_count_values($handCards);

        //sorteer op meest voorkomend en bij gelijk sorteert hij op card score DESC
        uksort($values, function($a, $b) use($values) {
            return $values[$b] <=> $values[$a] ?:
                self::getCardScore($b) <=> self::getCardScore($a);
        });

        $score = 0;
        $fiveOfAKind = 6;
        $fourOfAKind = 5;
        $threeOfAKind = 3;//full house = 3 + 1
        $pair = 1; //2 pair = 2

        $jokersUsed = false;
        foreach ($values as $card => $amount) {
            if ($useJokers === true && $card === 'J') {
                if ($amount === 5) {
                    return $fiveOfAKind;
                }

                continue;
            }

            if ($useJokers === true && array_key_exists('J', $values) && $jokersUsed === false) {
                $amount += $values['J'];
                $jokersUsed = true;
            }

            if ($amount === 5) {
                return $fiveOfAKind;
            }

            if ($amount === 4) {
                return $fourOfAKind;
            }

            if ($amount === 3) {
                $score += $threeOfAKind;
            }

            if ($amount === 2) {
                $score += $pair;
            }
        }

        return $score;
    }
}