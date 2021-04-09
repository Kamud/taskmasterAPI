<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');
include_once ('../config/config.php');
include_once ('../config/Database.php');
include_once ('../models/Task.php');
include_once ('../models/Prospect.php');
include_once ('../models/Assignment.php');
include_once ('../models/Estimate.php');
include_once ('../utilities/Response.php');

$task = new Task();
$prospect = new Prospect();
$assignment = new Assignment();
$estimate = new Estimate();

$tasks = $task->fetchAll();
$prospects = $prospect->fetchAll();
$assignments = $assignment->fetchAll();
$estimates = $estimate->fetchAll();

$combined_array = array_merge($tasks,$prospects,$assignments,$estimates);
//ADD A TIME DIFFERENCE FROM NOW
foreach ($combined_array as $item){
    $item->time_difference = strtotime($item->closing_date) - time();
}

$upcoming = array_filter($combined_array, function ($obj){
    return $obj->time_difference > 0;
});

//SORT THE ARRAY
usort($upcoming,function ($obj1, $obj2){
    return $obj1->time_difference > $obj2->time_difference;
});

//spare the first 3 items
$upcoming = array_slice($upcoming, 0, 3);

//SORT ON DATE MODIFIED
usort($combined_array, function ($obj1, $obj2) {
    return $obj1->modified_at < $obj2->modified_at;
});

//spare the first four items
$recent= array_slice($combined_array, 0, 5);

$res = new Response();
$res->upcoming = $upcoming;
$res->recent= $recent;
