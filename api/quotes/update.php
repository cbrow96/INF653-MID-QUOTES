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

    $data = json_decode(file_get_contents("php://input"));      //read and assign data

    //check if all data present
    if(!isset($data->id) || !isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)){
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }

    //asign values
    $quote->id = $data->id;
    $quote->quote = $data->quote;
    $quote->category_id = $data->category_id;
    $quote->author_id = $data->author_id;

    $author->id = $data->author_id;
    $category->id = $data->category_id;
    
    //check if given category exists, print error and exit if not.
    $category->read_single();
    if(!$category->category){
        echo json_encode(array('message' => 'category_id Not Found'));
        exit ();
    }

    //check if given author exists, print error and exit if not.
    $author->read_single();
    if(!$author->author){
        echo json_encode(array('message' => 'author_id Not Found'));
        exit();
    }

    //attempt update
    if($quote->update()){
        $quote_arr = array(
            'id'=> $quote->id,
            'quote'=> $quote->quote,
            'author_id'=> $quote->author_id,
            'category_id'=> $quote->category_id
        );

        print_r(json_encode($quote_arr));
    } else {
        //message if quote does not exist
        echo json_encode(
            array(
                'message' => 'No Quotes Found'
        ));
    }
?>