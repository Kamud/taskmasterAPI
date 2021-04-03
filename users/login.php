<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');


include_once '../config/config.php';
include_once '../config/Database.php';
include_once '../models/User.php';
include_once '../utilities/Response.php';

$user = new User();

//CHECK IF EMAIL IS AVAILABLE
$email = isset($_GET['email']) ? $_GET['email'] : false;

if(!$email){
    //DISPLAY RESULT
    $res = new Response(1);
    $res->error = "No email was set";
}

else{
    //SET THE USER EMAIL
    $user->email = $email;
    $result = $user->requestUser();
    if(!$result){
        //DISPLAY RESULT
        $res = new Response(1);
        $res->error = "The requested email was not found on the server";
    }
    else{
        //DISPLAY RESULT
        $res = new Response();
        $res->message = "User exists, proceed authentication";
        $res->proceed = $result;
    }
}