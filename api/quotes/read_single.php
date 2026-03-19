<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quotes.php';

    $database = new Database();
    $db = $database->connect();

    //create instance of Quote
    $quote = new Quote($db);

    //assign id if one given, die otherwise
    $quote->id = isset($_GET['id']) ? $_GET['id'] : die();

    $quote->read_single();

    //check if quote value changed from read_single, assign data to array if true
    //      and send as json data
    if($quote->quote != NULL){
        $quote_arr = array(
            'id' => $quote->id,
            'quote' => $quote->quote,
            'author' => $quote->author,
            'category' => $quote->category
        );

        print_r(json_encode($quote_arr));
    } else{
        //message if no author value retrieved.
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }
?>