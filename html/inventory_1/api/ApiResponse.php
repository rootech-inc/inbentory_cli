<?php
namespace API;
class ApiResponse
{
    function success($message)
    {
        header('content-type:applications/json');
        echo json_encode(array('status_code'=>200,'status'=>'success','message'=>$message));
    }

    function error($message)
    {
        header('content-type:applications/json');
        echo json_encode(array('status_code'=>500,'status'=>'error','message'=>$message));
    }

}