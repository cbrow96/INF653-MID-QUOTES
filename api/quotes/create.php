<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quotes.php';
    include_once '../../models/Authors.php';
    include_once '../../models/Categories.php';

    $database = new Database();
    $db = $database->connect();

    //create instances
    $quote = new Quote($db);
    $category = new Category($db);
    $author = new Author($db);

    $data = json_decode(file_get_contents("php://input"));      //read and asign data given

    //check that all required data given, send message and exit if not
    if(!isset($data->quote) || !isset($data->category_id) || !isset($data->author_id)){
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
        exit();
    }

    //asign values
    $quote->quote = $data->quote;
    $quote->category_id = $data->category_id;
    $quote->author_id = $data->author_id;

    $author->id = $data->author_id;
    $category->id = $data->category_id;

    //check if category exists, send message and exit if not
    $category->read_single();
    if(!$category->category){
        echo json_encode(array('message' => 'category_id Not Found'));
        exit ();
    }

    //check if author exists, send message and exit if not
    $author->read_single();
    if(!$author->author){
        echo json_encode(array('message' => 'author_id Not Found'));
        exit();
    }

    //attempt create
    if($quote->create()){
        $quote_arr = array(
            'id'=> $quote->id,
            'quote'=> $quote->quote,
            'author_id'=> $quote->author_id,
            'category_id'=> $quote->category_id
        );

        print_r(json_encode($quote_arr));
    }
?>