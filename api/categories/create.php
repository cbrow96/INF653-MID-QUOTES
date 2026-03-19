<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Categories.php';

    $database = new Database();
    $db = $database->connect();

    //create Category instance
    $category = new Category($db);

    $data = json_decode(file_get_contents("php://input"));  //read and asign data given

    $category->category = isset($data->category) ? $data->category : NULL;  //if author given, assign. NULL otherwise

    //check if category is given
    if($category->category != NULL){
        //attempt create
        if($category->create()){
            $category_arr = array(
                'id'=> $category->id,
                'category'=> $category->category
            );

            print_r(json_encode($category_arr));
        }
    }else{
        //message if no params given.
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }
?>