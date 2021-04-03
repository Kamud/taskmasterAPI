<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');


include_once '../config/config.php';
include_once '../config/Database.php';
include_once '../models/Pinned.php';
include_once '../utilities/Response.php';


//INITITATE PROTOTYPES
$pinned1 = new Pinned();


//CHECK IF ID IS AVAILABLE
$id = isset($_GET['id']) ? $_GET['id'] : false;
$resource_id = isset($_GET['resource_id']) ? $_GET['resource_id'] : false;
$resource = isset($_GET['resource']) ? $_GET['resource'] : false;


//EXECUTE MULTIPLE OF SINGLE IF AN ID IS SET OR NOT
if($id){
    $pinned1->id = $id;
    $result = $pinned1->fetchOne();

    if(!$result){
        //DISPLAY RESULT
        $res = new Response(1);
        $res->error = "The requested id ($pinned1->id) was not found";
    }

    else{
        $res = new Response();
        $res->data = $result;
    }
}
elseif ($resource_id){
    $pinned1->id = $resource_id;
    $result = $pinned1->fetchByResourceId();

    if(!$result){
        //DISPLAY RESULT
        $res = new Response(1);
        $res->error = "The requested resource_id ($pinned1->id) was not found";
    }

    else{
        $res = new Response();
        $res->data = $result;
    }

}elseif ($resource){
    $filter = new stdClass();
    $filter->key = 'resource_category';
    $filter->value = $resource;

    $res = new Response();
    $res->data = $pinned1->fetchAll($filter);
    $res->count= count($res->data);

}

else{
    $res = new Response();
    $res->data = $pinned1->fetchAll();
    $res->count= count($res->data);
}

