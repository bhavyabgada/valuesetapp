<?php
if (! function_exists('getCurrentDateTime')) {
    function getCurrentDateTime() {
        $date_time_obj = Carbon\Carbon::now();
        $date_time = $date_time_obj->toDateTimeString(); //This will output in the usual format of Y-m-d H:i:s
        return $date_time;
    }
}

if (! function_exists('formatDate')) {
    function formatDate($date) {
        $date_time_obj = Carbon\Carbon::parse($date);
        $date_time = $date_time_obj->toDateTimeString(); //This will output in the usual format of Y-m-d H:i:s
        return $date_time;
    }
}
