<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');


include_once './config/config.php';
include_once './config/Database.php';
include_once './models/Prospect.php';
include_once './utilities/Response.php';


//FORMAT DATES
//$dates = ['publish_date','closing_date','created_at'];
//
//foreach ($dates as $date){
//
//
//}
//$timeZone = 'Africa/Harare';  // +2 hours
//date_default_timezone_set($timeZone);
//
//$dateSrc = '22/3/2021 10:00';
//$dateTime = new DateTime($dateSrc);
//


$oldDate = '2021-04-01T09:55:20';
$newDate = new DateTime($oldDate);
$newDate->add(new DateInterval('P14D')); // P1D means a period of 1 day
$fomattedDate = $newDate->format('Y-m-d h:i');
echo $fomattedDate;
