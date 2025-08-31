<?php

namespace App\Helpers;

use Carbon\Carbon;

class ArabicDate
{
    public static function format($date)
    {
        // Arabic numeral mapping
        $arabicNumerals = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

        // Parse the date with Carbon
        $carbonDate = Carbon::parse($date)->locale('ar');

        // Format the date with Arabic month names
        $formattedDate = $carbonDate->isoFormat('D MMMM، YYYY');


        // Convert Western numerals to Arabic numerals
        return strtr($formattedDate, array_combine(range(0, 9), $arabicNumerals));
    }
}
