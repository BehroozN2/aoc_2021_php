<?php

namespace Questions;

class Day12 extends AbstractQuestion
{
    protected function part1(): string
    {
        $pathFinder = new Day12PathFinder($this->input);
        return (string)$pathFinder->getOneVisitSmallNodesPathCount();
    }

    protected function part2(): string
    {
        $pathFinder = new Day12PathFinder($this->input);
        return (string)$pathFinder->getTwoVisitsOnOnlyOneSmallNodePathCount();
    }
}

class Day12PathFinder
{
    protected array $connections = [];
    protected array $isNodeSmall = [];

    public function __construct(array $input)
    {
        foreach ($input as $line) {
            [$node1, $node2] = explode('-', $line);
            $this->connections[$node1][] = $node2;
            $this->connections[$node2][] = $node1;
        }

        foreach (array_keys($this->connections) as $node) {
            $this->isNodeSmall[$node] = strtolower($node) === $node;
        }
    }

    public function getOneVisitSmallNodesPathCount(): int
    {
        return count($this->getPaths('start'));
    }

    public function getTwoVisitsOnOnlyOneSmallNodePathCount(): int
    {
        $allPaths = [];

        foreach ($this->isNodeSmall as $smallNode => $isNodeSmall) {
            if (
                !$isNodeSmall ||
                $smallNode === 'start' ||
                $smallNode === 'end'
            ) {
                continue;
            }

            $paths = $this->getPaths('start', [], $smallNode);

            foreach ($paths as $path) {
                $allPaths[implode('-', $path)] = true;
            }
        }

        return count($allPaths);
    }

    protected function getPaths(string $currentNode, array $path = [], ?string $smallNodeToVisitTwice = null): array
    {
        if ($currentNode === 'end') {
            return [['end']];
        }

        $paths = [];

        foreach ($this->connections[$currentNode] as $newNode) {
            $newPath = [...$path, $currentNode];
            $newNodeCanVisitTwice = $newNode === $smallNodeToVisitTwice;

            if (
                (
                    $newNodeCanVisitTwice &&
                    $this->getValueCountInArray($newPath, $newNode) > 1
                ) ||
                (
                    !$newNodeCanVisitTwice &&
                    $this->isNodeSmall[$newNode] &&
                    in_array($newNode, $newPath)
                )
            ) {
                continue;
            }

            $subPaths = $this->getPaths($newNode, $newPath, $smallNodeToVisitTwice);

            foreach ($subPaths as $subPath) {
                $paths[] = [$currentNode, ...$subPath];
            }
        }

        return $paths;
    }

    protected function getValueCountInArray(array $array, string $value): int
    {
        return count(
            array_keys($array, $value),
        );
    }
}