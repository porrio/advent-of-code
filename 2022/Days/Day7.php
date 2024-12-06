<?php

declare(strict_types=1);

namespace App2022\Days;

use Day;

class Day7 extends Day
{
    private array $directorySizes = [];
    private string $currentDirectory = '/';
    private array $commands = [];

    public function __construct(int $day, int $year)
    {
        parent::__construct($day, $year);
        parent::fetchData();

        $this->parseCommands();
        $this->runScript();
    }

    public function partOne(): int
    {
        return array_sum(
            array_filter(
                $this->directorySizes, function ($size) {
                    return $size <= 100000;
                }
            )
        );
    }

    public function partTwo(): int
    {
        $diskSpace = 70000000;
        $updateSize = 30000000;
        $free = $diskSpace - $this->directorySizes['/'];
        $needed = $updateSize - $free;

        return min(
            array_filter(
                $this->directorySizes, function ($size) use ($needed) {
                    return $size >= $needed;
                }
            )
        );
    }

    private function runScript()
    {
        foreach ($this->commands as $command) {
            [$command, $arg] = explode(' ', $command, 2);

            if ($arg === null) {
                continue;
            }

            switch ($command) {
                case 'cd':
                    $this->currentDirectory = $this->cd($this->currentDirectory, $arg);
                    break;
                case 'ls':
                    $contents = $this->ls($arg);
                    $this->updateDirectorySize($this->currentDirectory, $contents);
                    break;
            }
        }
    }

    private function parseCommands()
    {
        $commands = [];
        $currentCommand = '';

        foreach ($this->data as $line) {
            if (strpos($line, '$') === 0) {
                $currentCommand = substr($line, 1);
                $commands[] = ltrim($currentCommand);
            } else {
                $commands[] = ltrim($currentCommand . ' ' . $line);
            }
        }

        $this->commands = $commands;
    }

    function cd(string $currentDirectory, string $arg): string
    {
        if ($arg === '/') {
            return '/';
        } elseif ($arg === '..') {
            return dirname($currentDirectory);
        }

        return $currentDirectory . '/' . $arg;
    }

    function ls(string $output): array
    {
        $contents = explode(PHP_EOL, $output);
        $files = [];

        foreach ($contents as $content) {
            $parts = explode(' ', $content);
            $size = (int)$parts[0];
            $files[] = $size;
        }

        return $files;
    }

    function updateDirectorySize(string $directory, array $contents): void
    {
        $totalSize = array_sum($contents);
        $this->directorySizes[$directory] += $totalSize;

        while ($directory !== '/') {
            $directory = dirname($directory);
            $this->directorySizes[$directory] += $totalSize;
        }
    }
}