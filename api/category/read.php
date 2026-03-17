<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Categories.php';

    $database = new Database();
    $db = $database->connect();

    $category = new Category($db);
    
    $result = $category->read();

    $row_count = $result->rowCount();

    if($row_count > 0) {
        $category_arr = array();
        
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $category_item = array(
                'id' => $id,
                'category' => $category
            );
            array_push($category_arr, $category_item);
        }
        echo json_encode($category_arr);

    } else{
        echo json_encode(
            array('message' => 'No Categories Found')
        );
    }
?>