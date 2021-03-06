<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
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
    //INITIATE A NEW MODEL
    $prospect1 = new Prospect();

    //EXECUTE deletion
    $prospect1->id = $id;
    if($prospect1->deleteOne()){
        echo json_encode(
            array(
                "message" => "Document with Id ($id) successfully Deleted"
            )
        );
    }
    else{
        header("HTTP/1.1 400");
        echo json_encode(
            array(
                "message" => "Failed to Delete document",
                "error"=> $prospect1->error
            )
        );

    }
}
