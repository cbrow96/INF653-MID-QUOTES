<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quotes.php';
    include_once '../../models/Authors.php';
    include_once '../../models/Categories.php';

    $database = new Database();
    $db = $database->connect();


    $quote = new Quote($db);
    $author = new Author($db);
    $category = new Category($db);

    $data = json_decode(file_get_contents("php://input"));

    if(!isset($data->id) || !isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)){
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    $quote->id = $data->id;
    $quote->quote = $data->quote;
    $quote->category_id = $data->category_id;
    $quote->author_id = $data->author_id;

    $author->id = $data->author_id;
    $category->id = $data->category_id;
    
    $category->read_single();
    if(!$category->category){
        echo json_encode(array('message' => 'category_id Not Found'));
        exit ();
    }

    $author->read_single();
    if(!$author->author){
        echo json_encode(array('message' => 'author_id Not Found'));
        exit();
    }

    $quote->read_single();
    if(!$quote->quote){
        echo json_encode(array('message' => 'No Quotes Found'));
        exit();
    }

    if($quote->update()){
        $quote_arr = array(
            'id'=> $quote->id,
            'quote'=> $quote->quote,
            'author_id'=> $quote->author_id,
            'category_id'=> $quote->category_id
        );

        print_r(json_encode($quote_arr));
    } else {
        echo json_encode(
            array(
                'message' => 'No Quotes Found'
        ));
    }
?>