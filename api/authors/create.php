<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Mehtods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Authors.php';

    $database = new Database();
    $db = $database->connect();


    $author = new Author($db);

    $data = json_decode(file_get_contents("php://input"));

    $author->author = isset($data->author) ? $data->author : NULL;

    if($author->author != NULL){
        if($author->create()){

        $author_arr = array(
            'id'=> $author->id,
            'author'=> $author->author
        );

        print_r(json_encode($author_arr));
        }
    }else{
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }
?>