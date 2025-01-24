<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('convertNumberToWordsPH'))
{
    function convertNumberToWordsPH($number) {
        $hyphen      = ' ';
        $conjunction = ' and ';
        $separator   = ' ';
        $negative    = 'negative ';
        $dictionary  = [
            0 => 'zero',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety',
            100 => 'Hundred',
            1000 => 'Thousand',
            1000000 => 'Million',
            1000000000 => 'Billion'
        ];

        if (!is_numeric($number)) {
            return false;
        }

        if ($number < 0) {
            return $negative . convertNumberToWordsPH(abs($number));
        }

        $pesos = floor($number);
        $centavos = round(($number - $pesos) * 100);
        $result = '';

        // Convert Pesos
        if ($pesos > 0) {
            $result .= convertToWords($pesos, $dictionary, $hyphen, ' ', $separator, true) . ' Peso' . ($pesos > 1 ? 's' : '');
        }

        // Convert Centavos with "and" before it
        if ($centavos > 0) {
            if ($pesos > 0) {
                $result .= $conjunction; // Add "and" before Centavos
            }
            $result .= convertToWords($centavos, $dictionary, $hyphen, $conjunction, $separator, false) . ' Centavo' . ($centavos > 1 ? 's' : '');
        }

        return $result;
    }
}
if(!function_exists('convertToWords'))
{
    function convertToWords($number, $dictionary, $hyphen, $conjunction, $separator, $isPesos) {
        $string = '';

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = (int) ($number / 100);
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= ($isPesos ? $conjunction : ' ') . convertToWords($remainder, $dictionary, $hyphen, $conjunction, $separator, $isPesos);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = convertToWords($numBaseUnits, $dictionary, $hyphen, $conjunction, $separator, $isPesos) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= convertToWords($remainder, $dictionary, $hyphen, $conjunction, $separator, $isPesos);
                }
                break;
        }

        return $string;
    }
}

