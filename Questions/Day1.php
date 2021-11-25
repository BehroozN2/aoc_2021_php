<?php

namespace Questions;

class Day1 extends AbstractQuestion
{
    protected function part1(): string
    {
        return implode(', ', $this->inputArray);
    }

    protected function part2(): string
    {
        return implode(', ', $this->inputArray);
    }
}