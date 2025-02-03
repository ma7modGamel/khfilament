<?php

// use NumberFormatter;

if (!function_exists('convertNumberToArabicWords')) {
    function convertNumberToArabicWords($number)
    {
        $number = trim($number);
        if ($number === "" || $number === null) {
            $number = 0;
        }

        // if (!is_numeric($number)) {
        //     throw new InvalidArgumentException("The input must be a numeric value. Received: " . var_export($number, true));
        // }
   
        $number = (float)$number;
      
        $formatter = new NumberFormatter('ar', NumberFormatter::SPELLOUT);

        return $formatter->format($number);
    }
}