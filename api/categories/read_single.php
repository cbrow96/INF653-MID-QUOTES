<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Categories.php';

    $database = new Database();
    $db = $database->connect();

    //create Category instance
    $category = new Category($db);

    $category->id = isset($_GET['id']) ? $_GET['id'] : die();   //assign id if one given, die otherwise

    $category->read_single();
    
    //check if category value changed from read_single, assign data to array if true
    //      and send as json data
    if($category->category != NULL){
        $category_arr = array(
            'id' => $category->id,
            'category' => $category->category
        );

        print_r(json_encode($category_arr));
    }  else{
        //message if no author value retrieved.
        echo json_encode(
            array('message' => 'category_id Not Found')
        );
    }
?>