<?php

namespace Questions;

class Day18 extends AbstractQuestion
{
    protected function part1(): string
    {
        return (string)$this->math()->getMagnitude();
    }

    protected function part2(): string
    {
        return (string)$this->math()->getLargestMagnitudeOfTwo();
    }

    protected function math(): Day18Math
    {
        return new Day18Math($this->input);
    }
}

class Day18Math
{
    protected array $numbers;

    public function __construct(array $input)
    {
        $this->numbers = array_map(
            fn($line) => new Day18Pair(
                eval('return ' . $line . ';'), /* Input is valid PHP array syntax, yay! */
            ),
            $input,
        );
    }

    public function getLargestMagnitudeOfTwo(): int
    {
        $largestMagnitude = 0;

        foreach ($this->numbers as $aIndex => $a) {
            foreach ($this->numbers as $bIndex => $b) {
                if ($aIndex === $bIndex) {
                    continue;
                }

                $largestMagnitude = max(
                    $largestMagnitude,
                    $this->getMagnitude(
                        $this->addTwoNumbers(
                            unserialize(serialize($a)),
                            unserialize(serialize($b)),
                        ),
                    ),
                );
            }
        }

        return $largestMagnitude;
    }

    public function getMagnitude(null|int|Day18Pair $number = null): int
    {
        if ($number === null) {
            $number = $this->addAllNumbers();
        }

        if ($number instanceof Day18Pair) {
            return (
                ($this->getMagnitude($number->left) * 3) +
                ($this->getMagnitude($number->right) * 2)
            );
        } else {
            return $number;
        }
    }

    public function addAllNumbers(): Day18Pair
    {
        $count = count($this->numbers);

        for ($number = $this->numbers[$i = 0]; ++$i < $count;) {
            $number = $this->addTwoNumbers($number, $this->numbers[$i]);
        }

        return $number;
    }

    protected function addTwoNumbers(Day18Pair $a, Day18Pair $b): Day18Pair
    {
        return $this->reduce(
            new Day18Pair([$a, $b]),
        );
    }

    protected function reduce(Day18Pair $number): Day18Pair
    {
        $reduced = true;

        while ($reduced) {
            if ($reduced = $this->explode($number)->exploded()) {
                continue;
            }

            $reduced = $this->split($number);
        }

        return $number;
    }

    protected function explode(int|Day18Pair $number, int $nestedLevel = 0): Day18ExplodeResult
    {
        if ($number instanceof Day18Pair) {
            if ($nestedLevel === 4) {
                return new Day18ExplodeResult($number->left, $number->right, 2);
            }

            $result = $this->explode($number->left, $nestedLevel + 1);

            if ($result->exploded()) {
                if ($result->explodedNow()) {
                    $number->left = 0;
                }

                $this->addValue('right', $number->right, $result->right);

                return new Day18ExplodeResult(left: $result->left, status: 1);
            }

            $result = $this->explode($number->right, $nestedLevel + 1);

            if ($result->exploded()) {
                if ($result->explodedNow()) {
                    $number->right = 0;
                }

                $this->addValue('left', $number->left, $result->left);

                return new Day18ExplodeResult(right: $result->right, status: 1);
            }
        }

        return new Day18ExplodeResult();
    }

    protected function split(int|Day18Pair $number): bool
    {
        if ($number instanceof Day18Pair) {
            if (
                !$number->left instanceof Day18Pair &&
                $number->left > 9
            ) {
                $number->left = $this->getSplitPair($number->left);
                return true;
            } elseif ($this->split($number->left)) {
                return true;
            }

            if (
                !$number->right instanceof Day18Pair &&
                $number->right > 9
            ) {
                $number->right = $this->getSplitPair($number->right);
                return true;
            } elseif ($this->split($number->right)) {
                return true;
            }
        }

        return false;
    }

    protected function getSplitPair(int $value): Day18Pair
    {
        return new Day18Pair(
            [
                floor($value / 2),
                ceil($value / 2),
            ],
        );
    }

    protected function addValue(string $direction, int|Day18Pair &$value, int $valueToAdd): void
    {
        if ($value instanceof Day18Pair) {
            $otherDirection = $direction === 'left' ? 'right' : 'left';
            $this->addValue($direction, $value->{$otherDirection}, $valueToAdd);
        } else {
            $value += $valueToAdd;
        }
    }
}

class Day18Pair
{
    public int|Day18Pair $left;
    public int|Day18Pair $right;

    public function __construct(array $pair)
    {
        $this->left = is_array($pair[0]) ? new Day18Pair($pair[0]) : $pair[0];
        $this->right = is_array($pair[1]) ? new Day18Pair($pair[1]) : $pair[1];
    }

    public function __toString(): string
    {
        return '[' . $this->left . ',' . $this->right . ']';
    }
}

class Day18ExplodeResult
{
    public function __construct(
        public int $left = 0,
        public int $right = 0,
        protected int $status = 0,
    ) {
    }

    public function exploded(): bool
    {
        return $this->status > 0;
    }

    public function explodedNow(): bool
    {
        return $this->status === 2;
    }
}