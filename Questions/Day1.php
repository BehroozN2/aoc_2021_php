<?php

namespace Questions;

class Day1 extends AbstractQuestion
{
    protected function part1(): string
    {
        return (string)$this->getDepthIncreasesCount(
            $this->getInputAsArrayOfIntegers()
        );
    }

    protected function part2(): string
    {
        return (string)$this->getDepthIncreasesCount(
            $this->getSlidingWindowSums(
                $this->getInputAsArrayOfIntegers(),
                -1,
                1
            )
        );
    }

    protected function getDepthIncreasesCount(array $depths): int
    {
        $increasesCount = 0;
        $depthsCount = count($depths);
        $lastDepth = $depths[$index = 0];

        while (++$index < $depthsCount) {
            if ($lastDepth < $depths[$index]) {
                $increasesCount++;
            }

            $lastDepth = $depths[$index];
        }

        return $increasesCount;
    }

    protected function getSlidingWindowSums(array $depths, int $rangeStart, int $rangeEnd): array
    {
        $depthsCount = count($depths);
        $slidingWindowSums = [];

        for ($index = abs($rangeStart); $index < $depthsCount - $rangeEnd; $index++) {
            $slidingWindowSum = 0;

            for ($sumPosition = $rangeStart; $sumPosition <= $rangeEnd; $sumPosition++) {
                $slidingWindowSum += $depths[$index + $sumPosition];
            }

            $slidingWindowSums[] = $slidingWindowSum;
        }

        return $slidingWindowSums;
    }
}