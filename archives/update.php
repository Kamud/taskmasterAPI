<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Access-Control-Allow-Methods,Content-Type,Authorization, X-Requested-With');


include_once '../config/config.php';
include_once '../config/Database.php';
include_once '../models/Archive.php';
include_once '../utilities/Response.php';

//CHECK IF ID IS SET
$id = isset($_GET['id']) ? $_GET['id'] : false;
if(!$id){
    $res = new Response();
    $res->message = 'No id was specified';
}
else{
    //INITIATE A NEW MODEL, GET USER INPUT AND INITIALIZE AN EMPTY DATA ARRAY
    $archive1 = new Archive();
    $archive1->id = $id;
    $input = json_decode(file_get_contents('php://input'));
    $data = array();

    //filter tags from input
    foreach ($input as $item => $value ){
        $data[$item] = strip_tags($value);
    }


    //UPDATE NECESSARY FIELDS slug and modified at
    //CREATE A SLUG IF EITHER DESCRIPTION OR CLIENT REF HAS BEEN ALTERED
    //GET OLD DATA FOR COMPARISON ON FIELD CHANGES
//    $slug = '';
//    if(array_key_exists("description",$data) || array_key_exists("client_ref",$data)){
//        //IF BOTH WERE MODIFIED
//        if(array_key_exists("description",$data) && array_key_exists("client_ref",$data)){
//
//            $slug = $data['description']. " " .$data['client_ref'];
//            $data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug)));
//        }
//        //IF DESCRIPTION ONLY
//        elseif (array_key_exists("description",$data)){
//
//            $old_data = $archive1->fetchOne();
//            $slug = $data['description']. " " .$old_data->client_ref;
//            $data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug)));
//        }
//        else{
//            $old_data = $archive1->fetchOne();
//            $slug = $old_data->description. " " .$data['client_ref'];
//            $data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug)));
//        }
//    }

    //UPDATE MODIFIED AT DATE
    date_default_timezone_set('Africa/Harare');
    $data['modified_at'] = date('Y-m-d H:i:s');

    //EXECUTE UPDATE
    if($archive1->upateOne($data)){
        //FETCH THE NEWLY CREATED DOCUMENT
        $new_document = $archive1->fetchOne();
        //create response
        $res = new Response();
        $res->message = "Document successfully Updated";
        $res->data = $new_document;
    }
    else{
        $res = new Response(1);
        $res->message = "Failed to update document";

        //create response
        $res->error = $archive1->error;
    }
}


