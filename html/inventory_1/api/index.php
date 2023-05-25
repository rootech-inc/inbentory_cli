<?php

$response = array('code'=>001,'status'=>'initializing','message'=>'initializing');


header('Content-Type:Application/Json');
echo(json_encode($response));
