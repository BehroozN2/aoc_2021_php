<?php

namespace Questions;

class Day17 extends AbstractQuestion
{
    protected function part1(): string
    {
        return (string)$this->evaluate()->highestReachedY;
    }

    protected function part2(): string
    {
        return (string)$this->evaluate()->reachedCount;
    }

    protected function evaluate(): Day17Probe
    {
        return (new Day17Probe($this->input[0]))->evaluate();
    }
}

class Day17Probe
{
    public int $reachedCount = 0;
    public int $highestReachedY = 0;

    protected int $targetLeft;
    protected int $targetRight;
    protected int $targetTop;
    protected int $targetBottom;

    public function __construct(string $input)
    {
        [$xRange, $yRange] = explode(', y=', substr($input, 15));
        [$this->targetLeft, $this->targetRight] = $this->parseBoundaries($xRange);
        [$this->targetBottom, $this->targetTop] = $this->parseBoundaries($yRange);
    }

    public function evaluate(): static
    {
        [$xCandidates, $yCandidates] = $this->getCandidates();

        foreach ($xCandidates as $x) {
            foreach ($yCandidates as $y) {
                $this->evaluateTrajectory($x, $y);
            }
        }

        return $this;
    }

    protected function evaluateTrajectory(int $xVelocity, int $yVelocity): void
    {
        $x = $y = $highestY = 0;

        while ($x <= $this->targetRight && $y >= $this->targetBottom) {
            if ($x >= $this->targetLeft && $y <= $this->targetTop) {
                $this->reachedCount++;
                $this->highestReachedY = max($this->highestReachedY, $highestY);
                break;
            }

            $x += $xVelocity;
            $y += $yVelocity;

            $highestY = max($highestY, $y);

            $yVelocity--;
            $xVelocity -= (int)($xVelocity > 0);
        }
    }

    protected function getCandidates(): array
    {
        $minX = 0;

        while (($minX / 2) * ($minX + 1) < $this->targetLeft) {
            $minX++;
        }

        return [
            range($minX, $this->targetRight),
            range($this->targetBottom, -$this->targetBottom - 1),
        ];
    }

    protected function parseBoundaries(string $boundaries): array
    {
        $numbers = array_map(
            fn(string $number) => (int)$number,
            explode('..', $boundaries),
        );

        return [
            min($numbers),
            max($numbers),
        ];
    }
}