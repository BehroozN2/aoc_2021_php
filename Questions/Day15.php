<?php

namespace Questions;

use SplMinHeap;

class Day15 extends AbstractQuestion
{
    protected function part1(): string
    {
        $aStar = new Day15AStar(
            $this->getGrid($this->input),
        );

        return (string)$aStar->getShortestPathCost();
    }

    protected function part2(): string
    {
        $aStar = new Day15AStar(
            $this->getLargerGrid($this->input),
        );

        return (string)$aStar->getShortestPathCost();
    }

    protected function getGrid(array $input): array
    {
        $grid = [];

        foreach ($input as $y => $row) {
            foreach (str_split($row) as $x => $cost) {
                $grid[$y][$x] = new Day15Node((int)$x, (int)$y, (int)$cost);
            }
        }

        return $grid;
    }

    protected function getLargerGrid(array $input): array
    {
        $expandedInput = [];

        /* Expand each line 4x to the right */
        foreach ($input as $line) {
            $expandedLine = $line;

            for ($n = 1; $n < 5; $n++) {
                $expandedLine .= $this->expandLine($line, $n);
            }

            $expandedInput[] = $expandedLine;
        }

        /* Expand all lines 4x downwards */
        $expandedInputCount = count($expandedInput);

        foreach ($expandedInput as $y => $expandedLine) {
            for ($n = 1; $n < 5; $n++) {
                $expandedInput[$y + ($n * $expandedInputCount)] = $this->expandLine($expandedLine, $n);
            }
        }

        return $this->getGrid($expandedInput);
    }

    protected function expandLine(string $line, int $expand): string
    {
        $replaceFrom = $replaceTo = range(1, 9);

        for ($n = 0; $n < $expand; $n++) {
            $replaceTo[] = array_shift($replaceTo);
        }

        return strtr(
            $line,
            array_combine($replaceFrom, $replaceTo),
        );
    }
}

/**
 * https://en.wikipedia.org/wiki/A*_search_algorithm#Pseudocode
 */
class Day15AStar
{
    protected Day15Node $start;
    protected Day15Node $goal;

    public function __construct(
        protected array $grid,
        protected $openSet = new Day15SplMinHeap(),
    ) {
        $this->goal = $this->grid[count($this->grid) - 1][count($this->grid[0]) - 1];
        $this->start = $this->grid[0][0];
        $this->start->gScore = 0;
    }

    public function getShortestPathCost(): ?int
    {
        $this->openSet->insert($this->start);

        while (!$this->openSet->isEmpty()) {
            /** @var Day15Node $current */
            $current = $this->openSet->extract();

            if ($current === $this->goal) {
                return $current->gScore;
            }

            foreach ($this->getNeighbors($current) as $neighbor) {
                /** @var Day15Node $neighbor */
                $neighborFirstVisit = $neighbor->gScore === null;
                $tentativeGScore = $current->gScore + $neighbor->cost;

                if ($neighborFirstVisit || $neighbor->gScore > $tentativeGScore) {
                    $neighbor->cameFrom = $current;
                    $neighbor->gScore = $tentativeGScore;

                    /* $tentativeGScore + Manhattan distance */
                    $neighbor->fScore = $tentativeGScore
                        + abs($this->goal->y - $neighbor->y)
                        + abs($this->goal->x - $neighbor->x);

                    if ($neighborFirstVisit) {
                        $this->openSet->insert($neighbor);
                    }
                }
            }
        }

        return null;
    }

    protected function getNeighbors(Day15Node $node): array
    {
        return array_filter(
            [
                $this->grid[$node->y - 1][$node->x] ?? null,
                $this->grid[$node->y + 1][$node->x] ?? null,
                $this->grid[$node->y][$node->x - 1] ?? null,
                $this->grid[$node->y][$node->x + 1] ?? null,
            ],
        );
    }
}


class Day15Node
{
    /* Cost of the cheapest path from start */
    public ?int $gScore = null;

    /* Current best guess if path goes through this node */
    public int $fScore = 0;

    /* Node immediately preceding on the cheapest path */
    public Day15Node $cameFrom;

    public function __construct(
        public int $x,
        public int $y,
        public int $cost,
    ) {
    }
}

/**
 * Using a min heap as suggested by algorithm to have O(1) when finding the node with lowest fScore
 */
class Day15SplMinHeap extends SplMinHeap
{
    protected function compare(mixed $value1, mixed $value2): int
    {
        /** @var Day15Node $value1 */
        /** @var Day15Node $value2 */
        return $value2->fScore - $value1->fScore;
    }
}