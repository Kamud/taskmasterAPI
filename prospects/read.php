<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');


include_once '../config/config.php';
include_once '../config/Database.php';
include_once '../models/Prospect.php';
include_once '../utilities/Response.php';


//INITITATE PROTOTYPES
$prospect1 = new Prospect();


//CHECK IF ID IS AVAILABLE
$id = isset($_GET['id']) ? $_GET['id'] : false;

//EXECUTE MULTIPLE OF SINGLE IF AN ID IS SET OR NOT
if($id){
    $prospect1->id = $id;
    $result = $prospect1->fetchOne();

    if(!$result){
        //DISPLAY RESULT
        $res = new Response(1);
        $res->error = "The requested id ($prospect1->id) was not found";
    }

    else{
        $res = new Response();
        $res->data = $result;
    }
}

else{
    $res = new Response();
    $res->data = $prospect1->fetchAll();
    $res->count= count($res->data);
}

