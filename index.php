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


$format = '%d-%m-%Y';
$date = '25-05-2012';
$parsed = strtotime($date , $format);
echo date('Y/m/d H:i:s', strtotime($parsed));