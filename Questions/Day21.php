<?php

namespace Questions;

class Day21 extends AbstractQuestion
{
    protected function part1(): string
    {
        return (string)$this->game()->play();
    }

    protected function part2(): string
    {
        return (string)$this->game()->playQuantum();
    }

    protected function game(): Day21Game
    {
        return new Day21Game(
            (int)substr($this->input[0], -1),
            (int)substr($this->input[1], -1),
        );
    }
}

class Day21Game
{
    protected const QUANTUM_ROLLS = [3, 4, 4, 4, 5, 5, 5, 5, 5, 5, 6, 6, 6, 6, 6, 6, 6, 7, 7, 7, 7, 7, 7, 8, 8, 8, 9];

    protected int $rolls = 0;
    protected array $players = [];
    protected array $cache = [];

    public function __construct(int ...$positions)
    {
        foreach ($positions as $player => $position) {
            $this->players[$player] = [$position, 0];
        }
    }

    public function play(): int
    {
        $player = 0;

        while (true) {
            $score = $this->setNewScore($this->players[$player], $this->roll() + $this->roll() + $this->roll());

            $player = (int)!$player;

            if ($score > 999) {
                return $this->rolls * $this->players[$player][1];
            }
        }
    }

    protected function roll(): int
    {
        return 1 + ($this->rolls++ % 100);
    }

    protected function setNewScore(array &$player, int $roll): int
    {
        return $player[1] += $this->setNewPosition($player, $roll);
    }

    protected function setNewPosition(array &$player, int $roll): int
    {
        return $player[0] = 1 + (($player[0] + --$roll) % 10);
    }

    public function playQuantum(): int
    {
        return max(
            $this->getCachedWins(...$this->players),
        );
    }

    protected function getCachedWins(array $player0, array $player1): array
    {
        $cacheKey = implode(',', $player0) . '_' . implode(',', $player1);

        return $this->cache[$cacheKey]
            ?? $this->cache[$cacheKey] = $this->getWins($player0, $player1);
    }

    protected function getWins(array $player0, array $player1): array
    {
        $wins = [0, 0];

        foreach (self::QUANTUM_ROLLS as $roll) {
            $player0Copy = $player0;
            $this->setNewScore($player0Copy, $roll);

            if ($player0Copy[1] > 20) {
                $wins[0]++;
                continue;
            }

            $possibilityWins = $this->getCachedWins($player1, $player0Copy);

            $wins[0] += $possibilityWins[1];
            $wins[1] += $possibilityWins[0];
        }

        return $wins;
    }
}