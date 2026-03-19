<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quotes.php';

    $database = new Database();
    $db = $database->connect();


    $quote = new Quote($db);

    $data = json_decode(file_get_contents("php://input"));  //read and assign data

    //check if id given, send message and exit if not
    if(!isset($data->id)){
        echo(json_encode(array('message' => 'Missing Required parameters')));
        exit();
    }

    $quote->id = $data->id;

    //attempt delete
    if($quote->delete()){
            $quote_arr = array(
                'id' => $quote->id
            );
        
            print_r(json_encode($quote_arr));
    }else{
        //message if deletion fails
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }
?>