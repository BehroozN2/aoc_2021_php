<?php

namespace Questions;

class Day9 extends AbstractQuestion
{
    protected function part1(): string
    {
        $heightMap = new Day9HeightMap($this->input);
        return (string)$heightMap->getLowPointRiskLevels();
    }

    protected function part2(): string
    {
        $heightMap = new Day9HeightMap($this->input);
        return (string)$heightMap->getThreeLargestBasinsMultipliedSize();
    }
}

class Day9HeightMap
{
    public array $map;

    public function __construct(array $input)
    {
        $this->map = array_map(
            fn(string $row) => array_map(
                fn(string $height) => new Day9Point((int)$height),
                str_split($row),
            ),
            $input,
        );

        foreach ($this->map as $y => $row) {
            foreach ($row as $x => $point) {
                $point->left = $this->map[$y][$x - 1] ?? null;
                $point->right = $this->map[$y][$x + 1] ?? null;
                $point->top = $this->map[$y - 1][$x] ?? null;
                $point->bottom = $this->map[$y + 1][$x] ?? null;
            }
        }
    }

    public function getLowPointRiskLevels(): int
    {
        return array_reduce(
            $this->map,
            fn(int $totalRisk, array $row) => array_reduce(
                $row,
                fn(int $rowRisk, Day9Point $point) => $rowRisk + $point->getLowPointRiskLevel(),
                $totalRisk,
            ),
            0,
        );
    }

    public function getThreeLargestBasinsMultipliedSize(): int
    {
        $basinSizes = [];
        $checkedPoints = [];

        foreach ($this->map as $row) {
            foreach ($row as $point) {
                if (0 < $basinSize = $this->getBasinSize($checkedPoints, $point)) {
                    $basinSizes[] = $basinSize;
                }
            }
        }

        rsort($basinSizes);

        return $basinSizes[0] * $basinSizes[1] * $basinSizes[2];
    }

    protected function getBasinSize(array &$checkedPoints, ?Day9Point $point): int
    {
        if (
            $point === null ||
            $point->height === 9 ||
            isset($checkedPoints[$point->id])
        ) {
            return 0;
        }

        $checkedPoints[$point->id] = true;

        return 1
            + $this->getBasinSize($checkedPoints, $point->left)
            + $this->getBasinSize($checkedPoints, $point->right)
            + $this->getBasinSize($checkedPoints, $point->top)
            + $this->getBasinSize($checkedPoints, $point->bottom);
    }
}

class Day9Point
{
    public int $id;

    public ?Day9Point $left;
    public ?Day9Point $right;
    public ?Day9Point $top;
    public ?Day9Point $bottom;

    protected static int $lastId = 0;

    public function __construct(public int $height)
    {
        $this->id = self::$lastId++;
    }

    public function getLowPointRiskLevel(): int
    {
        return $this->isLowPoint() ? $this->height + 1 : 0;
    }

    protected function isLowPoint(): bool
    {
        return (
            ($this->left === null || $this->height < $this->left->height) &&
            ($this->right === null || $this->height < $this->right->height) &&
            ($this->top === null || $this->height < $this->top->height) &&
            ($this->bottom === null || $this->height < $this->bottom->height)
        );
    }
}
