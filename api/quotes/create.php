<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Mehtods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quotes.php';

    $database = new Database();
    $db = $database->connect();


    $quote = new Quote($db);

    $data = json_decode(file_get_contents("php://input"));

    $quote->quote = isset($data->quote) ? $data->quote : NULL;
    $quote->category_id = isset($data->category_id) ? $data->category_id : NULL;
    $quote->author_id = isset($data->author_id) ? $data->author_id : NULL;

    if(($quote->quote != NULL) && ($quote->category_id != NULL) && ($$quote->author_id != NULL)){
        if($quote->create()){
            $quote_arr = array(
                'id'=> $quote->id,
                'quote'=> $quote->quote,
                'author_id'=> $quote->author_id,
                'category_id'=> $quote->category_id
            );
        }
    }else{
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }
?>