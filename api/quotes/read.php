<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quotes.php';

    $database = new Database();
    $db = $database->connect();


    $quote = new Quote($db);

    $quote->author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
    $quote->category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;


    $result = $quote->read();

    $row_count = $result->rowCount();

    if($row_count > 0){
        $quote_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $quote_item = array(
                'id' => $id,
                'quote' => $quote,
                'category' => $category,
                'author' => $author
            );
            array_push($quote_arr, $quote_item);
        }
        echo json_encode($quote_arr);

    } else{
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }
?>