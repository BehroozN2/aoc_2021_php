<?php

namespace Questions;

class Day5 extends AbstractQuestion
{
    protected function part1(): string
    {
        return (string)$this->getOverlapsCount(true);
    }

    protected function part2(): string
    {
        return (string)$this->getOverlapsCount(false);
    }

    protected function getOverlapsCount(bool $onlyHorizontalOrVertical): int
    {
        $points = [];

        foreach ($this->getCoordinatePairs() as $coordinatePair) {
            /** @var Day5CoordinatePair $coordinatePair */
            $isHorizontalOrVertical = $coordinatePair->isHorizontalOrVertical();

            if (!$isHorizontalOrVertical && $onlyHorizontalOrVertical) {
                continue;
            }

            $xRange = range($coordinatePair->x1, $coordinatePair->x2);
            $yRange = range($coordinatePair->y1, $coordinatePair->y2);

            foreach ($xRange as $xIndex => $x) {
                if ($isHorizontalOrVertical) {
                    foreach ($yRange as $y) {
                        $points[] = $x . ',' . $y;
                    }
                } else {
                    $points[] = $x . ',' . $yRange[$xIndex];
                }
            }
        }

        return count(
            array_filter(
                array_count_values($points),
                fn(int $count) => $count > 1,
            ),
        );
    }

    protected function getCoordinatePairs(): array
    {
        return array_map(
            fn(string $line) => new Day5CoordinatePair($line),
            $this->input,
        );
    }
}

class Day5CoordinatePair
{
    public int $x1;
    public int $y1;

    public int $x2;
    public int $y2;

    public function __construct(string $coordinatePairLine)
    {
        $coordinatePair = array_map(
            fn(string $coordinate) => explode(',', $coordinate),
            explode(' -> ', $coordinatePairLine),
        );

        $this->x1 = (int)$coordinatePair[0][0];
        $this->y1 = (int)$coordinatePair[0][1];

        $this->x2 = (int)$coordinatePair[1][0];
        $this->y2 = (int)$coordinatePair[1][1];
    }

    public function isHorizontalOrVertical(): bool
    {
        return $this->y1 === $this->y2 || $this->x1 === $this->x2;
    }
}