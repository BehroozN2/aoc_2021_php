<?php

namespace Questions;

/*
 * While my brute forcing solution technically works, it will take forever.
 * So I manually solved it (cheated?) using this logic:
 * https://github.com/mrphlip/aoc/blob/master/2021/24.md
 *
 * My input's final conditions:
 * D = E
 * F + 1 = G
 * H + 2 = I
 * C + 7 = J
 * K - 1 = L
 * B + 4 = M
 * A - 2 = N
 *
 *      ABCDEFGHIJKLMN
 * Max: 95299897999897
 * Min: 31111121382151
 */

class Day24 extends AbstractQuestion
{
    protected function part1(): string
    {
        $alu = $this->alu();

        for ($number = 99999999999999; $number > 11111111111110; $number--) {
            $stringNumber = (string)$number;

            if (str_contains($stringNumber, '0')) {
                continue;
            }

            if ($alu->isModelNumberValid($stringNumber)) {
                return $stringNumber;
            }
        }

        return 'Result not found!';
    }

    protected function part2(): string
    {
        $alu = $this->alu();

        for ($number = 11111111111111; $number < 100000000000000; $number++) {
            $stringNumber = (string)$number;

            if (str_contains($stringNumber, '0')) {
                continue;
            }

            if ($alu->isModelNumberValid($stringNumber)) {
                return $stringNumber;
            }
        }

        return 'Result not found!';
    }

    protected function alu(): Day24ALU
    {
        return new Day24ALU($this->input);
    }
}

class Day24ALU
{
    protected string $code;

    public function __construct(protected array $instructions)
    {
        $this->code = '$inputCounter = $w = $x = $y = $z = 0;';

        foreach ($instructions as $instruction) {
            $this->code .= $this->getInstructionCode($instruction);
        }
    }

    protected function getInstructionCode(string $instruction): string
    {
        @[$command, $a, $b] = explode(' ', $instruction);

        $a = '$' . $a;

        if (isset($b) && !is_numeric($b)) {
            $b = '$' . $b;
        }

        if ($command === 'inp') {
            return $a . ' = (int)$input[$inputCounter++];';
        }

        if ($command === 'add') {
            return $a . ' += ' . $b . ';';
        }

        if ($command === 'mul') {
            return $a . ' *= ' . $b . ';';
        }

        if ($command === 'div') {
            return $a . ' = ' . $b . ' ? (int)(' . $a . ' / ' . $b . ') : 0;';
        }

        if ($command === 'mod') {
            return $a . ' %= ' . $b . ';';
        }

        if ($command === 'eql') {
            return $a . ' = (int)(' . $a . ' === ' . $b . ');';
        }

        return '';
    }

    public function isModelNumberValid(string $input): bool
    {
        return eval($this->code . 'return $z === 0;');
    }
}
