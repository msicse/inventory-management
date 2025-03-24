<?php
use Carbon\Carbon;
use App\Models\Stock;


// if (!function_exists('getDateDiff')) {

// }


if (!function_exists('formatDate')) {
    function formatDate($date)
    {
        if($date){
            return Carbon::parse($date)->format('d M Y'); // Change to your desired format
        }

    }
}


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


function pending_tag() {
    return Stock::where('asset_tag', null)->count();
}
