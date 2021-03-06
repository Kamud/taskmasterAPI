<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');


include_once './config/config.php';
include_once './config/Database.php';
include_once './models/Prospect.php';


$prospect1 = new Database();

$res = $prospect1->test();

echo json_encode($res);



