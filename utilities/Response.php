<?php


class Response
{
    public $status;
    public $count;
    public $message;
    public $error;
    public $data;

    public function __construct($code =0)
    {
        if($code === 1){
            $this->status = 'fail';
        }
        else{
            $this->status = 'success';
        }
    }

    //DISPLAY RESPONSE WHEN PROGRAM EXITS
    public function __destruct(){

        //REMOVE ALL NULL FIELDS FROM RESULT
        $response = (object) array_filter((array) $this);
        echo json_encode($response);
    }
}