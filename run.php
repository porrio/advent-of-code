<?php

declare(strict_types=1);

autoloadDays();

$args = getopt('y::d::');
$year = array_key_exists('y', $args) ? (int)$args['y'] : 0;
$day = array_key_exists('d', $args) ? (int)$args['d'] : 0;

if ($year !== 0 && $day !== 0) {
    $className = 'App' . $year . '\Days\Day' . $day;

    if (!class_exists($className)) {
        outputResults($day);
        die;
    }

    run($year, $day);
} elseif ($year !== 0) {
    $start = microtime(true);
    for ($i=1; $i <= 25; $i++) {
        run($year, $i);
    }
    echo 'Total runtime : ' . floor((microtime(true) - $start) * 1000) . 'ms' . PHP_EOL;
} else {
    echo 'please provide at least a year like this -y=2024';
}

function run(int $year, int $day): void
{
    $className = 'App' . $year . '\Days\Day' . $day;

    if (!class_exists($className)) {
        echo sprintf('Year %s and day %s not implemented yet', $year, $day) . PHP_EOL;
        return;
    }

    $start = microtime(true);

    $dayClass = new $className($day, $year);

    $result1 = $dayClass->partOne();
    $result2 = $dayClass->partTwo();

    outputResults($day, $year, $result1, $result2, $start);
}

function autoloadDays(): void
{
    $abstractDay = __DIR__ . '/Day.php';

    if (is_file($abstractDay)) {
        include $abstractDay;
    }

    $currentYear = date('Y');
    for ($year = 2022; $year <= $currentYear; $year++) {
        $dir = __DIR__ . '/' . $year . '/Days';

        for ($i = 1; $i <= 25; $i++) {
            $dayFile = $dir . '/Day' . $i . '.php';
            if (is_file($dayFile)) {
                include $dayFile;
            }
        }
    }
}

function outputResults(int $day = 0, int $year = 0, int $result1 = 0, int $result2 = 0, $start = null): void
{
    $start ??= microtime(true);
    $runtime = floor((microtime(true) - $start) * 1000) . ' ms ';
    $memory = round(memory_get_peak_usage() / 1024 / 1024, 2) . ' mb';
    $output = "\033[32m|----------\033[93m Advent of Code " . $year . "\033[32m-----------|";
    $width = strlen($output) - 9;
    $output .= PHP_EOL . '|%s|%s|%s|%s';

    $day = "           \033[32mDay: \033[93m" . $day . "\033[32m";
    $fill = $width - strlen($day) + 7;
    $day .= str_repeat(' ' , $fill) . '|' . PHP_EOL;

    $part1 = '           ' . "\033[32mPart one:\033[93m " . $result1 . "\033[32m";
    $fill = $width - strlen($part1) + 7;
    $part1 .= str_repeat(' ' , $fill ) . '|' . PHP_EOL;

    $part2 = '           ' . "\033[32mPart two:\033[93m " . $result2 . "\033[32m";
    $fill = $width - strlen($part2) + 7;
    $part2 .= str_repeat(' ' , $fill) . '|' . PHP_EOL;

    $footer = "\033[32m----------\033[93m " . $runtime . $memory . "\033[32m ";
    $fill = $width - strlen($footer) + 7;
    $footer .= str_repeat('-' , $fill) . '|' . PHP_EOL;

    echo sprintf($output, $day, $part1, $part2, $footer);
}
