<?php

namespace Questions;

class Day25 extends AbstractQuestion
{
    protected function part1(): string
    {
        return (string)$this->grid()->stepsUntilStop();
    }

    protected function part2(): string
    {
        return 'There is no part 2!';
    }

    protected function grid(): Day25Grid
    {
        return new Day25Grid($this->input);
    }
}

class Day25Grid
{
    protected array $grid;
    protected int $rowsCount;
    protected int $colsCount;

    public function __construct(array $input)
    {
        $this->grid = array_map(
            fn(string $line) => str_split($line),
            $input,
        );

        $this->rowsCount = count($this->grid);
        $this->colsCount = count($this->grid[0]);
    }

    public function stepsUntilStop(): int
    {
        $step = 1;

        while ($this->grid !== $this->grid = $this->step()) {
            $step++;
        }

        return $step;
    }

    protected function step(): array
    {
        $grid = $this->grid;

        $this->move($grid, $grid, '>', 0, 1);
        $this->move($grid, $grid, 'v', 1, 0);

        return $grid;
    }

    protected function move(array &$grid, array $originalGrid, string $character, int $rowAddition, int $colAddition): void
    {
        for ($row = 0; $row < $this->rowsCount; $row++) {
            for ($col = 0; $col < $this->colsCount; $col++) {
                $rowNext = ($row + $rowAddition) % $this->rowsCount;
                $colNext = ($col + $colAddition) % $this->colsCount;

                if (
                    $originalGrid[$row][$col] === $character &&
                    $originalGrid[$rowNext][$colNext] === '.'
                ) {
                    $grid[$row][$col] = '.';
                    $grid[$rowNext][$colNext] = $character;
                }
            }
        }
    }
}
