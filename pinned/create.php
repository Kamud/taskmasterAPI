<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Access-Control-Allow-Methods,Content-Type,Authorization, X-Requested-With');


include_once '../config/config.php';
include_once '../config/Database.php';
include_once '../models/Pinned.php';
include_once '../utilities/Response.php';

$pinned1 = new Pinned();

$input = json_decode(file_get_contents('php://input'));
$data = array();

//filter tags from input
foreach ($input as $item => $value ){
    $data[$item] = strip_tags($value);
}

//CREATE A NEW HEX ID WITH A total OF 10
$data['_id'] = $pinned1->id = bin2hex(random_bytes(5));

//CREATE A SLUG
//$string = $data['description']. " " .$data['client_ref'];
//$data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));



if($pinned1->createOne($data)){
    //FETCH THE NEWLY CREATED DOCUMENT
    $new_document = $pinned1->fetchOne();
    //CREATE THE RESPONSE ARRAY TO BE DISPLAYED
    $res = new Response();
    $res->message = "Document successfully Pinned";
    $res->data = $new_document;
    $res->code = 201;
}

else{
    $res = new Response(1);
    $res->message = "Failed to create document";
    $res->error = $pinned1->error;
}








