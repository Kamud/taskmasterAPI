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
$password = isset($_GET['password']) ? $_GET['password'] : false;
if(!$email || !$password){
    //DISPLAY RESULT
    $res = new Response(1);
    $res->error = "Please provide both email and password";
}

else{
    //SET THE USER EMAIL
    $user->email = $email;
    $user->password = $password;

    $result = $user->authenticateUser();
    if(!$result){
        //DISPLAY RESULT
        print_r($result);
        $res = new Response(1);
        $res->error = "Invalid credentials";
    }
    else{
        //DISPLAY RESULT
        $res = new Response();
        $res->message = "Successfully authenticated";
        $res->authenticated = $result;
    }
}