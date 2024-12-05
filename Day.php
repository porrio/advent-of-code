<?php

declare(strict_types=1);

abstract class Day
{
    /** @var string|array $data */
    protected $data;
    protected int $day;
    protected int $year;

    public function __construct(int $day, int $year)
    {
        $this->day = $day;
        $this->year = $year;
    }

    protected function fetchData(string $delimiter = PHP_EOL, string $type = 'array'): void
    {
        $content = file_get_contents(__DIR__ . '/' . $this->year . '/data/day' . $this->day . '.txt');

        if ($type === 'array') {
            $content = explode($delimiter, $content);
        }

        $this->data = $content;
    }

    protected function str_ends_with(string $haystack, string $needle): bool
    {
        $length = strlen($needle);

        if (!$length) {
            return true;
        }

        return substr($haystack, -$length) === $needle;
    }

    /**
     * Find the Least Common Multiple of values in array
     *
     * Note: Function is generated bij ChatGPT
     * @throws Exception
     */
    protected function lcm(array $numbers): int
    {
        // Check if the input is an array and has at least two elements
        if (count($numbers) < 2) {
            throw new Exception('Error: Please provide an array with at least two numbers.');
        }

        // Find and return the LCM of the array of numbers
        return $this->findLCMofArray($numbers);
    }

    private function findGCD(int $a, int $b = 0): int
    {
        while ($b !== 0) {
            $temp = $b;
            $b = $a % $b;
            $a = $temp;
        }

        return $a;
    }

    private function findLCMofTwo(int $a, int $b): int
    {
        return ($a * $b) / $this->findGCD($a, $b);
    }

    private function findLCMofArray(array $numbers): int
    {
        $lcm = 1;
        $count = count($numbers);

        for ($i = 0; $i < $count; $i++) {
            $lcm = $this->findLCMofTwo($lcm, $numbers[$i]);
        }

        return $lcm;
    }
}
