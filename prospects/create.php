<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Access-Control-Allow-Methods,Content-Type,Authorization, X-Requested-With');


include_once '../config/config.php';
include_once '../config/Database.php';
include_once '../models/Prospect.php';

$prospect1 = new Prospect();

$input = json_decode(file_get_contents('php://input'));
$data = array();

//filter tags from input
foreach ($input as $item => $value ){
    $data[$item] = strip_tags($value);
}


if($prospect1->createOne($data)){
    header("HTTP/1.1 201 Created");
    echo json_encode(
        array(
            "message" => "Document successfully Created",
        )
    );
}
else{
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(
        array(
            "message" => "Failed to create document",
            "error"=> $prospect1->error
        )
    );

}

