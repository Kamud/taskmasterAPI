<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');


include_once '../config/config.php';
include_once '../config/Database.php';
include_once '../models/User.php';
include_once '../utilities/Response.php';

$user = new User();


//CHECK IF ID IS AVAILABLE
$id = isset($_GET['id']) ? $_GET['id'] : false;

//EXECUTE MULTIPLE OF SINGLE IF AN ID IS SET OR NOT
if($id){
    $user->id = $id;
    $result = $user->fetchOne();

    if(!$result){
        //DISPLAY RESULT
        $res = new Response(1);
        $res->error = "The requested id ($user->id) was not found";
    }

    else{
        $res = new Response();
        $res->data = $result;
    }
}

else{
    $res = new Response();
    $res->data = $user->fetchAll();
    $res->count= count($res->data);
}

