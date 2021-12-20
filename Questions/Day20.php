<?php

namespace Questions;

class Day20 extends AbstractQuestion
{
    protected function part1(): string
    {
        return (string)$this
            ->imageEnhancer()
            ->doubleEnhance(1)
            ->count();
    }

    protected function part2(): string
    {
        return (string)$this
            ->imageEnhancer()
            ->doubleEnhance(25)
            ->count();
    }

    protected function imageEnhancer(): Day20ImageEnhancer
    {
        return new Day20ImageEnhancer($this->input);
    }
}

class Day20ImageEnhancer
{
    protected const EXTRA_EDGE = 4;

    protected array $algorithm;
    protected array $image = [];
    protected array $imageSize = [
        'min_rows' => 0,
        'max_rows' => 0,

        'min_cols' => 0,
        'max_cols' => 0,
    ];

    public function __construct(array $input)
    {
        $this->algorithm = $this->stringToBinaryArray($input[0]);

        foreach ($input as $lineNumber => $line) {
            if ($lineNumber > 1) {
                $this->image[] = $this->stringToBinaryArray($line);
            }
        }

        $this->imageSize['max_rows'] = count($this->image);
        $this->imageSize['max_cols'] = count($this->image[0]);
    }

    public function count(): int
    {
        return array_sum(
            array_map(
                fn(array $row) => array_sum($row),
                $this->image,
            ),
        );
    }

    public function doubleEnhance(int $times): Day20ImageEnhancer
    {
        while ($times--) {
            $this
                ->enhanceImage()
                ->enhanceImage()
                ->crop();
        }

        return $this;
    }

    protected function enhanceImage(): static
    {
        $this->imageSize['min_rows'] -= self::EXTRA_EDGE - 1;
        $this->imageSize['max_rows'] += self::EXTRA_EDGE - 1;

        $this->imageSize['min_cols'] -= self::EXTRA_EDGE - 1;
        $this->imageSize['max_cols'] += self::EXTRA_EDGE - 1;

        $image = $this->image;

        for ($row = $this->imageSize['min_rows']; $row < $this->imageSize['max_rows']; $row++) {
            for ($col = $this->imageSize['min_cols']; $col < $this->imageSize['max_cols']; $col++) {
                $this->enhancePixel($image, $row, $col);
            }
        }

        $this->image = $image;

        return $this;
    }

    protected function enhancePixel(array &$image, int $row, int $col): void
    {
        $binary = '';

        for ($rowAddition = -1; $rowAddition <= 1; $rowAddition++) {
            for ($colAddition = -1; $colAddition <= 1; $colAddition++) {
                $binary .= $this->image[$row + $rowAddition][$col + $colAddition] ?? '0';
            }
        }

        $image[$row][$col] = $this->algorithm[bindec($binary)];
    }

    protected function crop(): static
    {
        $minRows = $this->imageSize['min_rows'] + self::EXTRA_EDGE;
        $maxRows = $this->imageSize['max_rows'] - self::EXTRA_EDGE;

        $minCols = $this->imageSize['min_cols'] + self::EXTRA_EDGE;
        $maxCols = $this->imageSize['max_cols'] - self::EXTRA_EDGE;

        $croppedImage = [];

        for ($row = $minRows; $row < $maxRows; $row++) {
            $croppedImage[$row] = [];

            for ($col = $minCols; $col < $maxCols; $col++) {
                if ($this->image[$row][$col]) {
                    $croppedImage[$row][$col] = $this->image[$row][$col];
                }
            }
        }

        $this->image = $croppedImage;

        return $this;
    }

    protected function stringToBinaryArray(string $string): array
    {
        return array_map(
            fn(string $character) => (int)($character === '#'),
            str_split($string),
        );
    }
}