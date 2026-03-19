<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quotes.php';

    $database = new Database();
    $db = $database->connect();

    //create Quote instance
    $quote = new Quote($db);

    //check for data given and set variable, NULL otherwise
    $quote->author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
    $quote->category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

    //call read method, asign revieved data.
    $result = $quote->read();

    //determine amount of data recieved
    $row_count = $result->rowCount();

    if($row_count > 0){
        $quote_arr = array();

        //loop while data exists, place data in row as assoc. array.
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $quote_item = array(
                'id' => $id,
                'quote' => $quote,
                'category' => $category,
                'author' => $author
            );
            //push retrieved data
            array_push($quote_arr, $quote_item);
        }
        //reurn retrieved data
        echo json_encode($quote_arr);

    } else{
        //message if no data retrieved.
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }
?>