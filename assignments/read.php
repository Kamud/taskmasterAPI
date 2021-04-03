<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');


include_once '../config/config.php';
include_once '../config/Database.php';
include_once '../models/Assignment.php';
include_once '../utilities/Response.php';


//INITITATE PROTOTYPES
$assignment1 = new Assignment();


//CHECK IF ID IS AVAILABLE
$id = isset($_GET['id']) ? $_GET['id'] : false;

//EXECUTE MULTIPLE OF SINGLE IF AN ID IS SET OR NOT
if($id){
    $assignment1->id = $id;
    $result = $assignment1->fetchOne();

    if(!$result){
        //DISPLAY RESULT
        $res = new Response(1);
        $res->error = "The requested id ($assignment1->id) was not found";
    }

    else{
        $res = new Response();
        $res->data = $result;
    }
}

else{
    $res = new Response();
    $res->data = $assignment1->fetchAll();
    $res->count= count($res->data);
}

