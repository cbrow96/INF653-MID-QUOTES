<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Mehtods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Categories.php';

    $database = new Database();
    $db = $database->connect();


    $category = new Category($db);

    $data = json_decode(file_get_contents("php://input"));

    $category->id = isset($data->id) ? $data->id : NULL;
    $category->id = isset($data->category) ? $data->category : NULL;

    if(($category->id != NULL) && ($category->id != NULL)){
        if($category->update()){
            echo json_encode(
                array(
                    'id' => $category->id,
                    'author' => $category->category
                ));
        }
    }else{
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }
?>