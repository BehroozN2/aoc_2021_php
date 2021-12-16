<?php

namespace Questions;

class Day16 extends AbstractQuestion
{
    protected function part1(): string
    {
        $bits = new Day16BITS($this->input[0]);
        return (string)$bits->versionSum;
    }

    protected function part2(): string
    {
        $bits = new Day16BITS($this->input[0]);
        return (string)$bits->getValue();
    }
}

class Day16BITS
{
    public int $versionSum = 0;
    protected Day16Packet $packet;

    public function __construct(string $input)
    {
        $binaryString = $this->hexadecimalToBinary($input);
        $this->packet = $this->parsePackets($binaryString);
    }

    public function getValue(?Day16Packet $packet = null): int
    {
        return ($packet ?? $this->packet)->getValue();
    }

    protected function parsePackets(string &$binaryString): Day16Packet
    {
        $this->versionSum += $version = $this->cutString($binaryString, 3);
        $type = $this->cutString($binaryString, 3);

        $value = null;
        $packets = [];

        if ($type === 4) {
            $valueBinaryString = '';

            while ($group = $this->cutString($binaryString, 5, false)) {
                $valueBinaryString .= substr($group, 1);

                if ((int)$group[0] === 0) {
                    break;
                }
            }

            $value = bindec($valueBinaryString);
        } elseif ($this->cutString($binaryString, 1)) {
            for ($subPacketsCounter = $this->cutString($binaryString, 11); $subPacketsCounter--;) {
                $packets[] = $this->parsePackets($binaryString);
            }
        } else {
            $subPacketBinaryString = $this->cutString(
                $binaryString,
                $this->cutString($binaryString, 15),
                false
            );

            while (strlen($subPacketBinaryString)) {
                $packets[] = $this->parsePackets($subPacketBinaryString);
            }
        }

        return new Day16Packet(
            $version,
            $type,
            $value,
            $packets,
        );
    }

    protected function cutString(&$string, int $length, bool $convertToDecimal = true): string|int
    {
        $cut = substr($string, 0, $length);
        $string = substr($string, $length);
        return $convertToDecimal ? bindec($cut) : $cut;
    }

    protected function hexadecimalToBinary(string $hexadecimal): string
    {
        /* Have to use this stupid method because PHP's base_convert() fails on large numbers! */
        return strtr(
            $hexadecimal,
            [
                '0' => '0000',
                '1' => '0001',
                '2' => '0010',
                '3' => '0011',
                '4' => '0100',
                '5' => '0101',
                '6' => '0110',
                '7' => '0111',
                '8' => '1000',
                '9' => '1001',
                'A' => '1010',
                'B' => '1011',
                'C' => '1100',
                'D' => '1101',
                'E' => '1110',
                'F' => '1111',
            ],
        );
    }
}

class Day16Packet
{
    public function __construct(
        public int $version,
        public int $type,
        public ?int $value,
        public array $packets,
    ) {
    }

    public function getValue(): int
    {
        if ($this->type === 4) {
            $value = $this->value;
        } else {
            $values = array_map(
                fn($subPacket) => $subPacket->getValue(),
                $this->packets,
            );

            if ($this->type === 0) {
                $value = array_sum($values);
            } elseif ($this->type === 1) {
                $value = array_product($values);
            } elseif ($this->type === 2) {
                $value = min($values);
            } elseif ($this->type === 3) {
                $value = max($values);
            } elseif ($this->type === 5) {
                $value = (int)($values[0] > $values[1]);
            } elseif ($this->type === 6) {
                $value = (int)($values[0] < $values[1]);
            } elseif ($this->type === 7) {
                $value = (int)($values[0] === $values[1]);
            }
        }

        return $value ?? 0;
    }
}
