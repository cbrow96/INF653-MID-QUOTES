<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Authors.php';

    $database = new Database();
    $db = $database->connect();


    $author = new Author($db);      //create Author instance

    $data = json_decode(file_get_contents("php://input"));  //read and asign data given

    $author->author = isset($data->author) ? $data->author : NULL;  //if author given, assign. NULL otherwise

    //check if author given
    if($author->author != NULL){
        //attempt create
        if($author->create()){

        $author_arr = array(
            'id'=> $author->id,
            'author'=> $author->author
        );

        print_r(json_encode($author_arr));
        }
    }else{
        //message if no params given.
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }
?>