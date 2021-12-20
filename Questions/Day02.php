<?php

namespace Questions;

class Day02 extends AbstractQuestion
{
    protected function part1(): string
    {
        return (string)$this->getCommandsResult(
            [
                'forward' => function (Day02Course $course, int $amount) {
                    $course->horizontalPosition += $amount;
                },
                'down' => function (Day02Course $course, int $amount) {
                    $course->depth += $amount;
                },
                'up' => function (Day02Course $course, int $amount) {
                    $course->depth -= $amount;
                },
            ]
        );
    }

    protected function part2(): string
    {
        return (string)$this->getCommandsResult(
            [
                'forward' => function (Day02Course $course, int $amount) {
                    $course->horizontalPosition += $amount;
                    $course->depth += $course->aim * $amount;
                },
                'down' => function (Day02Course $course, int $amount) {
                    $course->aim += $amount;
                },
                'up' => function (Day02Course $course, int $amount) {
                    $course->aim -= $amount;
                },
            ]
        );
    }

    protected function getCommandsResult(array $commandFunctions): int
    {
        $course = new Day02Course;

        foreach ($this->input as $command) {
            [$action, $amount] = explode(' ', $command);

            if (array_key_exists($action, $commandFunctions)) {
                $commandFunctions[$action]($course, (int)$amount);
            }
        }

        return $course->horizontalPosition * $course->depth;
    }
}

class Day02Course
{
    public function __construct(
        public int $horizontalPosition = 0,
        public int $depth = 0,
        public int $aim = 0,
    ) {
    }
}