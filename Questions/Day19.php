<?php

namespace Questions;

class Day19 extends AbstractQuestion
{
    protected function part1(): string
    {
        $uniqueBeacons = [];

        foreach ($this->findScanners() as $foundScanner) {
            /** @var Day19Scanner $foundScanner */
            foreach ($foundScanner->beacons as $beacon) {
                $uniqueBeacons[$beacon[0] . ',' . $beacon[1] . ',' . $beacon[2]] = true;
            }
        }

        return (string)count($uniqueBeacons);
    }

    protected function part2(): string
    {
        $maxDistance = 0;

        foreach ($foundScanners = $this->findScanners() as $a) {
            foreach ($foundScanners as $b) {
                /** @var Day19Scanner $a */
                /** @var Day19Scanner $b */
                $maxDistance = max(
                    $maxDistance,
                    abs($a->position[0] - $b->position[0]) +
                    abs($a->position[1] - $b->position[1]) +
                    abs($a->position[2] - $b->position[2])
                );
            }
        }

        return (string)$maxDistance;
    }

    protected function findScanners(): array
    {
        $scanners = $this->getScanners();
        $foundScanners[] = array_shift($scanners);

        while (count($scanners) > 0) {
            $scanners = array_filter(
                $scanners,
                function (Day19Scanner $scanner) use (&$foundScanners) {
                    foreach ($foundScanners as $foundScanner) {
                        /** @var Day19Scanner $foundScanner */
                        $overlappingScanner = $foundScanner->getOverlappingScanner($scanner);

                        if ($overlappingScanner !== null) {
                            $foundScanners[] = $overlappingScanner;
                            return false;
                        }
                    }

                    return true;
                },
            );
        }

        return $foundScanners;
    }

    protected function getScanners(): array
    {
        $scannerCounter = 0;
        $scanners = [];

        foreach ($this->input as $line) {
            if (empty($line)) {
                $scannerCounter++;
                continue;
            }

            if (str_starts_with($line, '---')) {
                continue;
            }

            $scanners[$scannerCounter][] = array_map(
                fn(string $number) => (int)$number,
                explode(',', $line),
            );
        }

        return array_map(
            fn(array $beacons) => new Day19Scanner($beacons),
            $scanners,
        );
    }
}

class Day19Scanner
{
    public array $position = [0, 0, 0];
    public array $orientations = [];

    protected const POSITION_MAPS = [
        [
            [0, 1, 2],
            [1, 2, 0],
            [2, 0, 1],
        ],
        [
            [0, 2, 1],
            [1, 0, 2],
            [2, 1, 0],
        ],
    ];

    protected const SIGN_MAPS = [
        [
            [1, 1, 1],
            [-1, -1, 1],
            [-1, 1, -1],
            [1, -1, -1],
        ],
        [
            [-1, 1, 1],
            [1, -1, 1],
            [1, 1, -1],
            [-1, -1, -1],
        ],
    ];

    public function __construct(public array $beacons)
    {
        foreach (self::POSITION_MAPS[0] as $positionMap) {
            foreach (self::SIGN_MAPS[0] as $signMap) {
                $this->orientations[] = $this->getOrientationBeacons($this->beacons, $positionMap, $signMap);
            }
        }


        foreach (self::POSITION_MAPS[1] as $positionMap) {
            foreach (self::SIGN_MAPS[1] as $signMap) {
                $this->orientations[] = $this->getOrientationBeacons($this->beacons, $positionMap, $signMap);
            }
        }
    }

    protected function getOrientationBeacons(array $beacons, array $positionMap, array $signMap): array
    {
        return array_map(
            fn($beacon) => [
                $beacon[$positionMap[0]] * $signMap[0],
                $beacon[$positionMap[1]] * $signMap[1],
                $beacon[$positionMap[2]] * $signMap[2],
            ],
            $beacons,
        );
    }

    public function getOverlappingScanner(Day19Scanner $scanner): ?Day19Scanner
    {
        foreach ($this->beacons as $beacon) {
            foreach ($scanner->orientations as $scannerOrientation) {
                foreach ($scannerOrientation as $scannerBeacon) {
                    $scannerTestPosition = $this->subtractArrays($beacon, $scannerBeacon);

                    $scannerTestBeacons = array_map(
                        fn(array $scannerTestBeacon) => $this->addArrays($scannerTestBeacon, $scannerTestPosition),
                        $scannerOrientation,
                    );

                    $overlapCount = 0;

                    foreach ($scannerTestBeacons as $scannerTestBeacon) {
                        if (12 === $overlapCount += (int)$this->hasBeacon($scannerTestBeacon)) {
                            $scanner->position = $scannerTestPosition;
                            $scanner->beacons = $scannerTestBeacons;
                            return $scanner;
                        }
                    }
                }
            }
        }

        return null;
    }

    protected function subtractArrays(array $a, array $b): array
    {
        return array_map(
            fn(int $a, int $b) => $a - $b,
            $a,
            $b,
        );
    }

    protected function addArrays(array $a, array $b): array
    {
        return array_map(
            fn(int $a, int $b) => $a + $b,
            $a,
            $b,
        );
    }

    protected function hasBeacon(array $beacon): bool
    {
        return in_array($beacon, $this->beacons);
    }
}