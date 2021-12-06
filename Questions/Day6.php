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

        foreach (explode(',', $this->input[0]) as $timer) {
            $lanternFishes[(int)$timer]++;
        }

        for ($day = 0; $day < $days; $day++) {
            $lanternFishesCopy = $lanternFishes;

            for ($timer = 0; $timer < 8; $timer++) {
                $lanternFishes[$timer] = $lanternFishesCopy[$timer + 1];
            }

            $lanternFishes[6] += $lanternFishes[8] = $lanternFishesCopy[0];
        }

        return array_sum($lanternFishes);
    }
}
