<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Access-Control-Allow-Methods,Content-Type,Authorization, X-Requested-With');


include_once '../config/config.php';
include_once '../config/Database.php';
include_once '../models/Prospect.php';

$prospect1 = new Prospect();

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
$res = $prospect1->deleteOne();
printf($res);

//if(){
//    echo json_encode(
//        array(
//            "message" => "Document successfully Deleted"
//        )
//    );
//}
//else{
//    echo json_encode(
//        array(
//            "message" => "Failed to delete document",
//            "error"=> $prospect1->error
//        )
//    );
//
//}

