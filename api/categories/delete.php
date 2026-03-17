<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Categories.php';

    $database = new Database();
    $db = $database->connect();


    $category = new Category($db);

    $data = json_decode(file_get_contents("php://input"));

    $category->id = isset($data->id) ? $data->id : NULL;
    $category->category = isset($data->category) ? $data->category : NULL;

    if(($category->id != NULL) && ($category->category != NULL)){
        if($category->delete()){
                $category_arr = array(
                    'id' => $category->id
                );
            
                print_r(json_encode($category_arr));
        }
    }else{
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }
?>