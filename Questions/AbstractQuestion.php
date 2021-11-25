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
        echo $this->{'part' . $this->part}();
    }

    abstract protected function part1(): string;

    abstract protected function part2(): string;
}