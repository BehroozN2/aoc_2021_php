<?php

namespace Questions;

class Day04 extends AbstractQuestion
{
    protected function part1(): string
    {
        [$numbers, $boards] = $this->parseInput();

        foreach ($numbers as $number) {
            foreach ($boards as $board) {
                /** @var Day04Board $board */
                $board->mark($number);

                if ($board->hasWon()) {
                    return (string)($board->getUnmarkedSum() * $number);
                }
            }
        }
    }

    protected function part2(): string
    {
        [$numbers, $boards] = $this->parseInput();

        $lastWonBoardUnmarkedSum = 0;

        foreach ($numbers as $number) {
            foreach ($boards as &$board) {
                /** @var Day04Board $board */
                $board->mark($number);

                if ($board->hasWon()) {
                    $lastWonBoardUnmarkedSum = $board->getUnmarkedSum();
                    $board = null;
                }
            }

            $boards = array_filter($boards);

            if (count($boards) === 0) {
                return (string)($lastWonBoardUnmarkedSum * $number);
            }
        }
    }

    protected function parseInput(): array
    {
        $input = $this->input;

        $numbers = array_map(
            fn($integer) => (int)$integer,
            explode(
                ',',
                array_shift($input),
            ),
        );

        $boards = array_map(
            fn($board) => new Day04Board($board),
            array_chunk(
                array_map(
                    fn($row) => array_map(
                        fn($integer) => (int)trim($integer),
                        str_split($row, 3),
                    ),
                    array_filter($input),
                ),
                5,
            ),
        );

        return [$numbers, $boards];
    }
}

class Day04Board
{
    protected array $marked;
    protected int $rowsCount;
    protected int $columnsCount;

    public function __construct(protected array $board)
    {
        $this->rowsCount = count($this->board);
        $this->columnsCount = count($this->board[0]);

        $this->marked = array_fill(
            0,
            $this->rowsCount,
            array_fill(
                0,
                $this->columnsCount,
                0,
            ),
        );
    }

    public function mark(int $number)
    {
        foreach ($this->board as $rowPosition => $row) {
            foreach ($row as $columnPosition => $column) {
                if ($number === $column) {
                    $this->marked[$rowPosition][$columnPosition] = 1;
                }
            }
        }
    }

    public function hasWon(): bool
    {
        foreach ($this->marked as $markedRow) {
            if (array_sum($markedRow) === $this->rowsCount) {
                return true;
            }
        }

        for ($columnPosition = 0; $columnPosition < $this->columnsCount; $columnPosition++) {
            $markedColumn = array_column($this->marked, $columnPosition);

            if (array_sum($markedColumn) === $this->columnsCount) {
                return true;
            }
        }

        return false;
    }

    public function getUnmarkedSum(): int
    {
        $unmarkedSum = 0;

        foreach ($this->marked as $rowPosition => $markedRow) {
            foreach ($markedRow as $columnPosition => $markedColumn) {
                if ($markedColumn === 0) {
                    $unmarkedSum += $this->board[$rowPosition][$columnPosition];
                }
            }
        }

        return $unmarkedSum;
    }
}