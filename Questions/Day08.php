<?php

namespace Questions;

class Day08 extends AbstractQuestion
{
    protected function part1(): string
    {
        return (string)array_reduce(
            $this->getDisplays(),
            fn(int $total, Day08Display $display) => $total +
                count(
                    array_filter(
                        $display->outputDigits,
                        fn($outputDigit) => in_array(strlen($outputDigit), [2, 3, 4, 7]),
                    ),
                ),
            0,
        );
    }

    protected function part2(): string
    {
        return (string)array_reduce(
            $this->getDisplays(),
            fn(int $total, Day08Display $display) => $total + $display->getOutputValue(),
            0,
        );
    }

    protected function getDisplays(): array
    {
        return array_map(
            fn($inputLine) => new Day08Display($inputLine),
            $this->input,
        );
    }
}

class Day08Display
{
    public array $signalPatterns; /* Holds 10 unique signal patterns from the input */
    public array $outputDigits; /* Holds 4 output digits from the input */

    protected array $sortedSignalPatterns = []; /* Sorted by length */
    protected array $digits = []; /* Strings for 0-9 digits */
    protected array $segmentsMap = []; /* Correct a-g mapping */

    public function __construct(string $input)
    {
        [$signalPatterns, $outputDigits] = explode(' | ', $input);
        $this->signalPatterns = explode(' ', $signalPatterns);
        $this->outputDigits = explode(' ', $outputDigits);
    }

    public function getOutputValue(): int
    {
        $this->setSortedSignalPatterns();

        /* 1, 4, 7 and 8 can be found right away by their length */
        $this->digits[1] = $this->sortedSignalPatterns[0];
        $this->digits[4] = $this->sortedSignalPatterns[2];
        $this->digits[7] = $this->sortedSignalPatterns[1];
        $this->digits[8] = $this->sortedSignalPatterns[9];

        /* This will find 6 and segment a, c and f */
        $this->loopOnDigitCharacters(
            $this->digits[7],
            function (string $character) {
                if (str_contains($this->digits[1], $character)) {
                    /* This looks at shared characters of 1 and 7 (segment c and f) */
                    $counterFor069 = 0;

                    $candidatesFor069 = [
                        $this->sortedSignalPatterns[6],
                        $this->sortedSignalPatterns[7],
                        $this->sortedSignalPatterns[8],
                    ];

                    foreach ($candidatesFor069 as $candidateFor069) {
                        if (str_contains($candidateFor069, $character)) {
                            $counterFor069++;
                        } else {
                            /* 0 and 9 both have segment c, but 6 misses segment c, so both 6 and segment c are found here */
                            $this->digits[6] = $candidateFor069;
                            $this->segmentsMap['c'] = $character;
                        }
                    }

                    if ($counterFor069 === 3) {
                        /* Character found in all of 0, 6 and 9 is segment f */
                        $this->segmentsMap['f'] = $character;
                    }
                } else {
                    /* When a character in 7 cannot be found in 1, it means it is for segment a */
                    $this->segmentsMap['a'] = $character;
                }
            },
        );

        /* This will find 3 */
        $candidatesFor235 = [
            $this->sortedSignalPatterns[3],
            $this->sortedSignalPatterns[4],
            $this->sortedSignalPatterns[5],
        ];

        foreach ($candidatesFor235 as $candidateFor235) {
            if (
                str_contains($candidateFor235, $this->segmentsMap['c']) &&
                str_contains($candidateFor235, $this->segmentsMap['f'])
            ) {
                /* Between these candidates, only 3 has both segment c and f */
                $this->digits[3] = $candidateFor235;
            }
        }

        /* This will find segment b and d */
        $this->loopOnDigitCharacters(
            $this->digits[4],
            function (string $character) {
                if (!in_array($character, $this->segmentsMap)) {
                    /* If character is not in segments map yet, it is either segment b or d (c and f are already found) */
                    if (str_contains($this->digits[3], $character)) {
                        /* If character is also in 3, then it is segment d */
                        $this->segmentsMap['d'] = $character;
                    } else {
                        /* Else it will be segment b */
                        $this->segmentsMap['b'] = $character;
                    }
                }
            },
        );

        /* This will find segment g */
        $this->loopOnDigitCharacters(
            $this->digits[3],
            function (string $character) {
                if (!in_array($character, $this->segmentsMap)) {
                    /* If character is not in segments map yet, it is segment g (a, c, d and f are already found) */
                    $this->segmentsMap['g'] = $character;
                }
            },
        );

        /* This will find segment e (the last one) */
        $this->loopOnDigitCharacters(
            $this->digits[6],
            function (string $character) {
                if (!in_array($character, $this->segmentsMap)) {
                    /* If character is not in segments map yet, it is segment e (a, b, d, f and g are already found) */
                    $this->segmentsMap['e'] = $character;
                }
            },
        );

        /* Now with a full segments map, any digits can be built! Lets build the missing ones: 0, 2, 5, 9 */
        $this->digits[0] = $this->getDigitFromSegmentsMap(['a', 'b', 'c', 'e', 'f', 'g']);
        $this->digits[2] = $this->getDigitFromSegmentsMap(['a', 'c', 'd', 'e', 'g']);
        $this->digits[5] = $this->getDigitFromSegmentsMap(['a', 'b', 'd', 'f', 'g']);
        $this->digits[9] = $this->getDigitFromSegmentsMap(['a', 'b', 'c', 'd', 'f', 'g']);

        /* Now with value for all digits known, lets analyze the output digits and return their value */
        return (int)array_reduce(
            $this->outputDigits,
            fn(string $outputValue, string $outputDigit) => $outputValue .
                array_search(
                    $this->getSortedDigit($outputDigit),
                    $this->digits,
                ),
            '',
        );
    }

    protected function setSortedSignalPatterns(): void
    {
        $this->sortedSignalPatterns = array_map(
            fn(string $signalPattern) => $this->getSortedDigit($signalPattern),
            $this->signalPatterns,
        );

        usort(
            $this->sortedSignalPatterns,
            fn($a, $b) => strlen($a) - strlen($b),
        );
    }

    protected function getSortedDigit(string $digit): string
    {
        $sortedDigit = str_split($digit);
        sort($sortedDigit);
        return implode('', $sortedDigit);
    }

    protected function getDigitFromSegmentsMap(array $segments): string
    {
        $digit = array_map(
            fn($segment) => $this->segmentsMap[$segment],
            $segments,
        );

        return $this->getSortedDigit(
            implode(
                '',
                $digit,
            ),
        );
    }

    protected function loopOnDigitCharacters(string $digit, callable $function): void
    {
        foreach (str_split($digit) as $character) {
            $function($character);
        }
    }
}
