<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Access-Control-Allow-Methods,Content-Type,Authorization, X-Requested-With');


include_once '../config/config.php';
include_once '../config/Database.php';
include_once '../models/Assignment.php';
include_once '../utilities/Response.php';

$assignment1 = new Assignment();

$input = json_decode(file_get_contents('php://input'));
$data = array();

//filter tags from input
foreach ($input as $item => $value ){
    $data[$item] = strip_tags($value);
}

//CREATE A NEW HEX ID WITH A total OF 10
$data['_id'] = $assignment1->id = bin2hex(random_bytes(5));

//CREATE A SLUG
$string = $data['description']. " " .$data['client_ref'];
$data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));



if($assignment1->createOne($data)){
    //FETCH THE NEWLY CREATED DOCUMENT
    $new_document = $assignment1->fetchOne();
    //CREATE THE RESPONSE ARRAY TO BE DISPLAYED
    $res = new Response();
    $res->message = "Document successfully Created";
    $res->data = $new_document;
    $res->code = 201;
}

else{
    $res = new Response(1);
    $res->message = "Failed to create document";
    $res->error = $assignment1->error;
}








