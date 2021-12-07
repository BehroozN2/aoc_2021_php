<?php

namespace Questions;

class Day6 extends AbstractQuestion
{
    protected function part1(): string
    {
        return (string)$this->getLanternFishCount(80);
    }

    protected function part2(): string
    {
        return (string)$this->getLanternFishCount(256);
    }

    protected function getLanternFishCount(int $days): int
    {
        $lanternFishes = array_fill(0, 9, 0);

        foreach ($this->getSingleLineInputAsArrayOfIntegers() as $timer) {
            $lanternFishes[$timer]++;
        }

        for ($day = 0; $day < $days; $day++) {
            $lanternFishes[6] += $lanternFishes[] = array_shift($lanternFishes);
        }

        return array_sum($lanternFishes);
    }
}
