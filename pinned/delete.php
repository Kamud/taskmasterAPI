<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Access-Control-Allow-Methods,Content-Type,Authorization, X-Requested-With');


include_once '../config/config.php';
include_once '../config/Database.php';
include_once '../models/Pinned.php';
include_once '../utilities/Response.php';



//CHECK IF ID IS SET
$id = isset($_GET['id']) ? $_GET['id'] : false;
$resource_id = isset($_GET['resource_id']) ? $_GET['resource_id'] : false;
if($id){
    //INITIATE A NEW MODEL
    $pinned1 = new Pinned();

    //EXECUTE deletion
    $pinned1->id = $id;
    if($pinned1->deleteOne()){
        //create response
        $res = new Response();
        $res->message = "Document with Id ($id) successfully Deleted";

    }
    else{
        $res = new Response(1);
        $res->message = "Failed to Delete document";
        //CHECK IF THE ERROR WAS A 404
        $res->error = $pinned1->error;
    }
   }
elseif ($resource_id){
    //INITIATE A NEW MODEL
    $pinned1 = new Pinned();

    //EXECUTE deletion
    $pinned1->id = $resource_id;
    if($pinned1->deleteByResourceId($resource_id)){
        //create response
        $res = new Response();
        $res->message = "Document with Id ($resource_id) successfully unpinned";

    }
    else{
        $res = new Response(1);
        $res->message = "Failed to Unpin document";
        //CHECK IF THE ERROR WAS A 404
        $res->error = $pinned1->error;
    }

}
else{
    //create response
    $res = new Response(1);
    $res->message = "No id was specified";


}
