<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Authors.php';

    $database = new Database();
    $db = $database->connect();


    $author = new Author($db);      //create instance of author

    $author->id = isset($_GET['id']) ? $_GET['id'] : die();     //assign id if one given, die otherwise

    $author->read_single();
    
    //check if author value changed from read_single, assign data to array if true
    //      and send as json data
    if($author->author != NULL){
    $author_arr = array(
        'id' => $author->id,
        'author' => $author->author
    );

    print_r(json_encode($author_arr));
    } else{
        //message if no author value retrieved.
        echo json_encode(
            array('message' => 'author_id Not Found')
        );
    }
?>