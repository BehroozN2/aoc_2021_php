<?php

namespace Questions;

abstract class AbstractQuestion
{
    public function __construct(private int $part, protected array $input)
    {
    }

    public function __call(string $name, array $arguments): void
    {
        echo 'No solution implemented for this part.';
    }

    public function solve(): void
    {
        $startTime = microtime(true);

        echo 'Answer : ' . $this->{'part' . $this->part}();

        echo PHP_EOL . 'Runtime: ' . (microtime(true) - $startTime);
    }

    protected function getInputAsArrayOfIntegers(): array
    {
        return array_map(
            fn($integer) => (int)$integer,
            $this->input,
        );
    }

    abstract protected function part1(): string;

    abstract protected function part2(): string;
}