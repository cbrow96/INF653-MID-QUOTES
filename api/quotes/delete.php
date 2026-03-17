<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Mehtods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quotes.php';

    $database = new Database();
    $db = $database->connect();


    $quote = new Quote($db);

    $data = json_decode(file_get_contents("php://input"));

    $quote->id = $data->id;

    if($quote->delete()){
        echo json_encode(
            array('message' => 'Quote Deleted')
        );
    }else{
        echo json_encode(
            array('message' => 'Quote Not Deleted')
        );
    }
?>