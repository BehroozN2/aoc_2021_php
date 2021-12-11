<?php

namespace Questions;

class Day11 extends AbstractQuestion
{
    protected function part1(): string
    {
        $grid = new Day11Grid($this->input);

        for ($flashes = $step = 0; $step < 100; $step++) {
            $flashes += $grid->step();
        }

        return (string)$flashes;
    }

    protected function part2(): string
    {
        $grid = new Day11Grid($this->input);
        $step = 1;

        while ($grid->step() < 100) {
            $step++;
        }

        return $step;
    }
}

class Day11Grid
{
    protected array $grid;

    public function __construct(array $input)
    {
        $this->grid = array_map(
            fn(string $line) => array_map(
                fn(string $energy) => new Day11Octopus((int)$energy),
                str_split($line),
            ),
            $input,
        );
    }

    public function step(): int
    {
        $stepFlashes = $loopFlashes = $this->stepLoop(true);

        while ($loopFlashes > 0) {
            $stepFlashes += $loopFlashes = $this->stepLoop(false);
        }

        return $stepFlashes;
    }

    protected function stepLoop(bool $isFirstLoop): int
    {
        if ($isFirstLoop) {
            array_walk(
                $this->grid,
                function (array $row) {
                    array_walk(
                        $row,
                        function (Day11Octopus $octopus) {
                            $octopus->increaseEnergy(true);
                        },
                    );
                },
            );
        }

        $loopFlashes = 0;

        foreach ($this->grid as $y => $row) {
            foreach ($row as $x => $octopus) {
                /** @var Day11Octopus $octopus */
                if ($octopus->flash()) {
                    $loopFlashes++;

                    for ($adjacentY = $y - 1; $adjacentY <= $y + 1; $adjacentY++) {
                        for ($adjacentX = $x - 1; $adjacentX <= $x + 1; $adjacentX++) {
                            if (isset($this->grid[$adjacentY][$adjacentX])) {
                                $this->grid[$adjacentY][$adjacentX]->increaseEnergy(false);
                            }
                        }
                    }
                }
            }
        }

        return $loopFlashes;
    }
}

class Day11Octopus
{
    public function __construct(public int $energy, protected bool $flashed = false)
    {
    }

    public function increaseEnergy(bool $resetFlashed): void
    {
        if ($resetFlashed && $this->flashed) {
            $this->flashed = false;
            $this->energy = 1;
        } elseif (!$this->flashed) {
            $this->energy++;
        }
    }

    public function flash(): bool
    {
        if ($this->energy > 9) {
            $this->flashed = true;
            $this->energy = 0;
            return true;
        }

        return false;
    }
}
