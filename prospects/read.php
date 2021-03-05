<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');


include_once '../config/config.php';
include_once '../config/Database.php';
include_once '../models/Prospect.php';

$prospect1 = new Prospect();

//INITIATE A RESPONSE ARRAY
$res = array(
    'status' => 'success',
    'count' => 0
);

//CHECK IF ID IS AVAILABLE
$id = isset($_GET['id']) ? $_GET['id'] : false;

//EXECUTE MULTIPLE OF SINGLE IF AN ID IS SET OR NOT
if($id){
    $prospect1->id = $id;
    unset($res['count']);
    $res['data'] = $prospect1->fetchOne();
}
else{
    $res['data'] = $prospect1->fetchAll();
    $res['count'] = count($res['data']);
}


echo json_encode($res);
