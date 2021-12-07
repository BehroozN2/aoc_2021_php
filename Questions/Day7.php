<?php

namespace Questions;

class Day7 extends AbstractQuestion
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
            $positionsFuelConsumption = 0;

            foreach ($crabPositions as $crabPosition) {
                $positionsFuelConsumption += $costs[abs($crabPosition - $position)];
            }

            if ($positionsFuelConsumption < $bestFuelConsumption || $bestFuelConsumption === null) {
                $bestFuelConsumption = $positionsFuelConsumption;
            }
        }

        return $bestFuelConsumption;
    }
}
