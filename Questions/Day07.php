<?php

namespace Questions;

class Day07 extends AbstractQuestion
{
    protected function part1(): string
    {
        return (string)$this->getBestFuelConsumption();
    }

    protected function part2(): string
    {
        return (string)$this->getBestFuelConsumption(
            fn(int $steps) => ($steps / 2) * ($steps + 1),
        );
    }

    protected function getBestFuelConsumption(?callable $costFunction = null): int
    {
        $crabPositions = $this->getSingleLineInputAsArrayOfIntegers();
        $minPosition = min($crabPositions);
        $maxPosition = max($crabPositions);

        $costs = [0];
        for ($steps = 1; $steps <= $maxPosition - $minPosition; $steps++) {
            $costs[$steps] = $costFunction ? $costFunction($steps) : $steps;
        }

        $bestFuelConsumption = null;
        for ($position = $minPosition; $position <= $maxPosition; $position++) {
            $positionFuelConsumption = 0;

            foreach ($crabPositions as $crabPosition) {
                $positionFuelConsumption += $costs[abs($crabPosition - $position)];
            }

            if ($positionFuelConsumption < $bestFuelConsumption || $bestFuelConsumption === null) {
                $bestFuelConsumption = $positionFuelConsumption;
            }
        }

        return $bestFuelConsumption;
    }
}
