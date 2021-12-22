<?php

namespace Questions;

class Day22 extends AbstractQuestion
{
    protected function part1(): string
    {
        return (string)$this->reactor()->reboot(-50, 50);
    }

    protected function part2(): string
    {
        return (string)$this->reactor()->reboot();
    }

    protected function reactor(): Day22Reactor
    {
        return new Day22Reactor($this->input);
    }
}

class Day22Reactor
{
    protected array $rebootCuboids = [];
    protected array $gridCuboids = [];

    public function __construct(array $input)
    {
        $xStart = $xEnd = $yStart = $yEnd = $zStart = $zEnd = null;

        foreach ($input as $line) {
            $this->rebootCuboids[] = $cuboid = $this->getRebootCuboid($line);

            $xStart = $xStart === null ? $cuboid->xStart : min($xStart, $cuboid->xStart);
            $xEnd = $xEnd === null ? $cuboid->xEnd : max($xEnd, $cuboid->xEnd);
            $yStart = $yStart === null ? $cuboid->yStart : min($yStart, $cuboid->yStart);
            $yEnd = $yEnd === null ? $cuboid->yEnd : max($yEnd, $cuboid->yEnd);
            $zStart = $zStart === null ? $cuboid->zStart : min($zStart, $cuboid->zStart);
            $zEnd = $zEnd === null ? $cuboid->zEnd : max($zEnd, $cuboid->zEnd);
        }

        $this->gridCuboids[] = new Day22Cuboid(false, $xStart, $xEnd, $yStart, $yEnd, $zStart, $zEnd);
    }

    public function reboot(?int $rangeStart = null, ?int $rangeEnd = null): int
    {
        foreach ($this->rebootCuboids as $rebootCuboid) {
            /** @var Day22Cuboid $rebootCuboid */
            if (
                $rangeStart !== null &&
                $rangeEnd !== null &&
                (
                    $rebootCuboid->xStart < $rangeStart ||
                    $rebootCuboid->yStart < $rangeStart ||
                    $rebootCuboid->zStart < $rangeStart ||
                    $rebootCuboid->xEnd > $rangeEnd ||
                    $rebootCuboid->yEnd > $rangeEnd ||
                    $rebootCuboid->zEnd > $rangeEnd
                )
            ) {
                continue;
            }

            $updatedGridCuboids = [];

            foreach ($this->gridCuboids as $gridCuboid) {
                /** @var Day22Cuboid $gridCuboid */
                if (
                    $gridCuboid->xEnd >= $rebootCuboid->xStart &&
                    $gridCuboid->xStart <= $rebootCuboid->xEnd &&
                    $gridCuboid->yEnd >= $rebootCuboid->yStart &&
                    $gridCuboid->yStart <= $rebootCuboid->yEnd &&
                    $gridCuboid->zEnd >= $rebootCuboid->zStart &&
                    $gridCuboid->zStart <= $rebootCuboid->zEnd
                ) {
                    if ($gridCuboid->xStart < $rebootCuboid->xStart) {
                        $gridCuboidClone = clone $gridCuboid;
                        $gridCuboidClone->xEnd = $rebootCuboid->xStart - 1;

                        $gridCuboid->xStart = $rebootCuboid->xStart;

                        $updatedGridCuboids[] = $gridCuboidClone;
                    }

                    if ($gridCuboid->xEnd > $rebootCuboid->xEnd) {
                        $gridCuboidClone = clone $gridCuboid;
                        $gridCuboidClone->xStart = $rebootCuboid->xEnd + 1;

                        $gridCuboid->xEnd = $rebootCuboid->xEnd;

                        $updatedGridCuboids[] = $gridCuboidClone;
                    }

                    if ($gridCuboid->yStart < $rebootCuboid->yStart) {
                        $gridCuboidClone = clone $gridCuboid;
                        $gridCuboidClone->yEnd = $rebootCuboid->yStart - 1;

                        $gridCuboid->yStart = $rebootCuboid->yStart;

                        $updatedGridCuboids[] = $gridCuboidClone;
                    }

                    if ($gridCuboid->yEnd > $rebootCuboid->yEnd) {
                        $gridCuboidClone = clone $gridCuboid;
                        $gridCuboidClone->yStart = $rebootCuboid->yEnd + 1;

                        $gridCuboid->yEnd = $rebootCuboid->yEnd;

                        $updatedGridCuboids[] = $gridCuboidClone;
                    }

                    if ($gridCuboid->zStart < $rebootCuboid->zStart) {
                        $gridCuboidClone = clone $gridCuboid;
                        $gridCuboidClone->zEnd = $rebootCuboid->zStart - 1;

                        $gridCuboid->zStart = $rebootCuboid->zStart;

                        $updatedGridCuboids[] = $gridCuboidClone;
                    }

                    if ($gridCuboid->zEnd > $rebootCuboid->zEnd) {
                        $gridCuboidClone = clone $gridCuboid;
                        $gridCuboidClone->zStart = $rebootCuboid->zEnd + 1;

                        $gridCuboid->zEnd = $rebootCuboid->zEnd;

                        $updatedGridCuboids[] = $gridCuboidClone;
                    }

                    $gridCuboid->state = $rebootCuboid->state;
                }

                $updatedGridCuboids[] = $gridCuboid;
            }

            $this->gridCuboids = $updatedGridCuboids;
        }

        return array_reduce(
            $this->gridCuboids,
            fn(int $total, Day22Cuboid $cuboid) => $total + $cuboid->cubesOn(),
            0,
        );
    }

    public function getRebootCuboid(string $cuboid): Day22Cuboid
    {
        preg_match_all('/(\w+)' . str_repeat('[^-\d]+([-\d]+)[.]{2}([-\d]+)', 3) . '/', $cuboid, $matches);

        return new Day22Cuboid(
            $matches[1][0] === 'on',
            min((int)$matches[2][0], (int)$matches[3][0]),
            max((int)$matches[2][0], (int)$matches[3][0]),
            min((int)$matches[4][0], (int)$matches[5][0]),
            max((int)$matches[4][0], (int)$matches[5][0]),
            min((int)$matches[6][0], (int)$matches[7][0]),
            max((int)$matches[6][0], (int)$matches[7][0]),
        );
    }
}

class Day22Cuboid
{
    public function __construct(
        public bool $state,
        public int $xStart,
        public int $xEnd,
        public int $yStart,
        public int $yEnd,
        public int $zStart,
        public int $zEnd,
    ) {
    }

    public function cubesOn(): int
    {
        if (!$this->state) {
            return 0;
        }

        return
            ($this->xEnd - $this->xStart + 1) *
            ($this->yEnd - $this->yStart + 1) *
            ($this->zEnd - $this->zStart + 1);
    }
}