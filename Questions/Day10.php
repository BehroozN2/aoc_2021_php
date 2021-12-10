<?php

namespace Questions;

class Day10 extends AbstractQuestion
{
    protected const OPEN_CLOSE_MAPPING = [
        '(' => ')',
        '[' => ']',
        '{' => '}',
        '<' => '>',
    ];

    protected const ILLEGAL_SCORES = [
        ')' => 3,
        ']' => 57,
        '}' => 1197,
        '>' => 25137,
    ];

    protected const INCOMPLETE_SCORES = [
        '(' => 1,
        '[' => 2,
        '{' => 3,
        '<' => 4,
    ];

    protected function part1(): string
    {
        return (string)array_reduce(
            $this->input,
            fn(int $scores, string $line) => $scores + $this->getIllegalScore($line),
            0,
        );
    }

    protected function part2(): string
    {
        $incompleteScores = array_filter(
            array_map(
                fn(string $line) => $this->getIncompleteScore($line),
                $this->input,
            ),
        );

        sort($incompleteScores);

        return (string)$incompleteScores[floor(count($incompleteScores) / 2)];
    }

    protected function getIllegalScore(string $chunks): int
    {
        $illegalCharacter = $this->analyzeChunks($chunks)['illegal_character'];
        return $illegalCharacter === null ? 0 : self::ILLEGAL_SCORES[$illegalCharacter];
    }

    protected function getIncompleteScore(string $chunks): int
    {
        $result = $this->analyzeChunks($chunks);

        if ($result['illegal_character'] !== null) {
            return 0;
        }

        return array_reduce(
            array_reverse($result['remaining_openings']),
            fn(int $score, string $opening) => ($score * 5) + self::INCOMPLETE_SCORES[$opening],
            0,
        );
    }

    protected function analyzeChunks(string $chunks): array
    {
        $chunkOpeningsStack = [];
        $firstIllegalCharacter = null;

        foreach (str_split($chunks) as $chunkCharacter) {
            if (array_key_exists($chunkCharacter, self::OPEN_CLOSE_MAPPING)) {
                $chunkOpeningsStack[] = $chunkCharacter;
            } else {
                $lastChunkOpening = array_pop($chunkOpeningsStack);

                if ($chunkCharacter != self::OPEN_CLOSE_MAPPING[$lastChunkOpening]) {
                    $firstIllegalCharacter = $chunkCharacter;
                    break;
                }
            }
        }

        return [
            'remaining_openings' => $chunkOpeningsStack,
            'illegal_character' => $firstIllegalCharacter,
        ];
    }
}
