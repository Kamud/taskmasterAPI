<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
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

//CHECK IF ID IS AVAILABLE
$id = isset($_GET['id']) ? $_GET['id'] : false;

//EXECUTE MULTIPLE OF SINGLE IF AN ID IS SET OR NOT
if($id){
    $prospect1->id = $id;
}

else{
    echo json_encode(
        array(
            "message" => "No id was specified"
        )
    );
    die(401);
}

if($prospect1->upateOne($data)){
    echo json_encode(
        array(
            "message" => "Document successfully Updated"
        )
    );
}
else{
    echo json_encode(
        array(
            "message" => "Failed to update document",
            "error"=> $prospect1->error
        )
    );

}

