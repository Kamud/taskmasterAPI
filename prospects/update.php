<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Access-Control-Allow-Methods,Content-Type,Authorization, X-Requested-With');


include_once '../config/config.php';
include_once '../config/Database.php';
include_once '../models/Prospect.php';


//CHECK IF ID IS SET
$id = isset($_GET['id']) ? $_GET['id'] : false;
if(!$id){
    header("HTTP/1.1 400");
    echo json_encode(
        array(
            "message" => "No id was specified"
        )
    );
    die(400);
}
else{
    //INITIATE A NEW MODEL, GET USER INPUT AND INITIALIZE AN EMPTY DATA ARRAY
    $prospect1 = new Prospect();
    $input = json_decode(file_get_contents('php://input'));
    $data = array();

    //filter tags from input
    foreach ($input as $item => $value ){
        $data[$item] = strip_tags($value);
    }

    //EXECUTE UPDATE
    $prospect1->id = $id;
    if($prospect1->upateOne($data)){
        echo json_encode(
            array(
                "message" => "Document successfully Updated"
            )
        );
    }
    else{
        header("HTTP/1.1 400");
        echo json_encode(
            array(
                "message" => "Failed to update document",
                "error"=> $prospect1->error
            )
        );

    }
}
