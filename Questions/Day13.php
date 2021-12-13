<?php

namespace Questions;

class Day13 extends AbstractQuestion
{
    protected function part1(): string
    {
        $paper = new Day13Paper($this->input);

        $paper->fold(1);

        return (string)$paper->getFilledCount();
    }

    protected function part2(): string
    {
        $paper = new Day13Paper($this->input);

        $paper->fold();

        return (string)$paper;
    }
}

class Day13Paper
{
    protected array $paper = [];
    protected array $folds = [];

    protected int $rowsCount = 0;
    protected int $columnsCount = 0;

    public function __construct(array $input)
    {
        $inputIsPaper = true;

        foreach ($input as $line) {
            if (empty($line)) {
                $inputIsPaper = false;
                continue;
            }

            if ($inputIsPaper) {
                [$x, $y] = explode(',', $line);
                $this->paper[$y][$x] = '#';

                $this->columnsCount = max($this->columnsCount, (int)$x + 1);
                $this->rowsCount = max($this->rowsCount, (int)$y + 1);
            } else {
                $fold = explode(
                    '=',
                    substr($line, 11),
                );

                $this->folds[] = [$fold[0], (int)$fold[1]];
            }
        }
    }

    public function fold(?int $times = null): void
    {
        foreach ($this->folds as $index => $fold) {
            if ($times !== null && $index >= $times) {
                break;
            }

            $this->foldOnce(...$fold);
        }
    }

    protected function foldOnce(string $foldDirection, int $foldPosition): void
    {
        if ($foldDirection === 'y') {
            for ($y = $foldPosition + 1; $y < $this->rowsCount; $y++) {
                foreach ($this->paper[$y] ?? [] as $x => $ignored) {
                    $this->paper[$foldPosition - ($y - $foldPosition)][$x] = '#';
                }
            }

            $this->paper = array_filter(
                $this->paper,
                fn($key) => (int)$key < $foldPosition,
                ARRAY_FILTER_USE_KEY,
            );

            $this->rowsCount = $foldPosition;
        } else {
            for ($x = $foldPosition + 1; $x < $this->columnsCount; $x++) {
                foreach ($this->paper as $y => $row) {
                    if (isset($row[$x])) {
                        $this->paper[$y][$foldPosition - ($x - $foldPosition)] = '#';
                    }
                }
            }

            $this->paper = array_map(
                fn($row) => array_filter(
                    $row,
                    fn($key) => (int)$key < $foldPosition,
                    ARRAY_FILTER_USE_KEY,
                ),
                $this->paper,
            );

            $this->columnsCount = $foldPosition;
        }
    }

    public function getFilledCount(): int
    {
        return array_reduce(
            $this->paper,
            fn(int $total, array $row) => $total + count($row),
            0,
        );
    }

    public function __toString(): string
    {
        $string = PHP_EOL . PHP_EOL;

        for ($y = 0; $y <= $this->rowsCount; $y++) {
            for ($x = 0; $x <= $this->columnsCount; $x++) {
                $string .= $this->paper[$y][$x] ?? ' ';
            }

            $string .= PHP_EOL;
        }

        return $string;
    }
}