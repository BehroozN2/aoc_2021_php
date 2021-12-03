<?php

namespace Questions;

class Day3 extends AbstractQuestion
{
    protected function part1(): string
    {
        $diagnosticReport = $this->getDiagnosticReport();
        $bitsCount = count($diagnosticReport[0]);
        $gammaRate = $epsilonRate = '';

        for ($bitColumn = 0; $bitColumn < $bitsCount; $bitColumn++) {
            $gammaRate .= $mostCommonBit = $this->getMostCommonBit($diagnosticReport, $bitColumn);
            $epsilonRate .= (int)!$mostCommonBit;
        }

        return (string)(bindec($gammaRate) * bindec($epsilonRate));
    }

    protected function part2(): string
    {
        $oxygenDiagnosticReport = $co2DiagnosticReport = $this->getDiagnosticReport();
        $bitsCount = count($oxygenDiagnosticReport[0]);

        for ($bitColumn = 0; $bitColumn < $bitsCount; $bitColumn++) {
            if (count($oxygenDiagnosticReport) > 1) {
                $oxygenMostCommonBit = $this->getMostCommonBit($oxygenDiagnosticReport, $bitColumn);

                $oxygenDiagnosticReport = array_filter(
                    $oxygenDiagnosticReport,
                    fn($bits) => $bits[$bitColumn] === $oxygenMostCommonBit,
                );
            }

            if (count($co2DiagnosticReport) > 1) {
                $co2MostCommonBit = $this->getMostCommonBit($co2DiagnosticReport, $bitColumn);

                $co2DiagnosticReport = array_filter(
                    $co2DiagnosticReport,
                    fn($bits) => $bits[$bitColumn] !== $co2MostCommonBit,
                );
            }
        }

        $oxygenGeneratorRating = implode('', reset($oxygenDiagnosticReport));
        $co2ScrubberRating = implode('', reset($co2DiagnosticReport));

        return (string)(bindec($oxygenGeneratorRating) * bindec($co2ScrubberRating));
    }

    protected function getDiagnosticReport(): array
    {
        return array_map(
            fn($binaryString) => array_map(
                fn($bit) => (int)$bit,
                str_split($binaryString),
            ),
            $this->input,
        );
    }

    protected function getMostCommonBit(array $diagnosticReport, int $column): int
    {
        $columnSum = array_sum(
            array_column($diagnosticReport, $column)
        );

        return (int)($columnSum >= count($diagnosticReport) / 2);
    }
}