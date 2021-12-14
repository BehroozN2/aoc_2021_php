<?php

namespace Questions;

class Day14 extends AbstractQuestion
{
    protected function part1(): string
    {
        $polymer = new Day14Polymer($this->input);
        return (string)$polymer->insertPairs(10);
    }

    protected function part2(): string
    {
        $polymer = new Day14Polymer($this->input);
        return (string)$polymer->insertPairs(40);
    }
}

class Day14Polymer
{
    protected string $template;
    protected array $pairInsertions = [];

    public function __construct(array $input)
    {
        foreach ($input as $lineNumber => $line) {
            if ($lineNumber === 0) {
                $this->template = $line;
            } elseif ($lineNumber > 1) {
                $pairInsertion = explode(' -> ', $line);
                $this->pairInsertions[$pairInsertion[0]] = $pairInsertion[1];
            }
        }
    }

    public function insertPairs(int $times): int
    {
        $templatePairs = $this->getInitialTemplatePairs();

        for ($t = 0; $t++ < $times; $templatePairs = $newTemplatePairs) {
            $newTemplatePairs = [];

            foreach ($templatePairs as $templatePair => $templatePairCount) {
                $this->arrayAddOrSet(
                    $newTemplatePairs,
                    $templatePair[0] . $this->pairInsertions[$templatePair],
                    $templatePairCount,
                );

                $this->arrayAddOrSet(
                    $newTemplatePairs,
                    $this->pairInsertions[$templatePair] . $templatePair[1],
                    $templatePairCount,
                );
            }
        }

        return $this->getMinMaxElementsDiff($templatePairs);
    }

    protected function getMinMaxElementsDiff($templatePairs): int
    {
        $elements = [
            $this->getLastElement() => 1,
        ];

        foreach ($templatePairs as $templatePair => $templatePairCount) {
            $this->arrayAddOrSet(
                $elements,
                $templatePair[0],
                $templatePairCount,
            );
        }

        return max($elements) - min($elements);
    }

    protected function getInitialTemplatePairs(): array
    {
        $templatePairs = [];
        $templateLength = strlen($this->template);

        for ($position = 1; $position < $templateLength; $position++) {
            $this->arrayAddOrSet(
                $templatePairs,
                $this->template[$position - 1] . $this->template[$position],
                1,
            );
        }

        return $templatePairs;
    }

    protected function getLastElement(): string
    {
        return substr($this->template, -1);
    }

    protected function arrayAddOrSet(array &$array, string $key, int $value): void
    {
        if (isset($array[$key])) {
            $array[$key] += $value;
        } else {
            $array[$key] = $value;
        }
    }
}