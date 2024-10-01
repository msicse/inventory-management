<?php
use Carbon\Carbon;


// if (!function_exists('getDateDiff')) {

// }


function getDateDiff($date): int
{
    $currentDate = Carbon::now();
    $expired = Carbon::parse($date);
    $days = $currentDate->diffInDays($expired);
    return $days;
}


function productStatus($input)
{
    $status = "";
    if ($input == 1) {
        $status = "Active";
    } elseif ($input == 2) {
        $status = "Poor";
    } else {
        $status = "Damaged";
    }

    return $status;
}