<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Categories.php';

    $database = new Database();
    $db = $database->connect();

    //create Category instance
    $category = new Category($db);
    
    //call read method, asign revieved data.
    $result = $category->read();

    //determine amount of data recieved
    $row_count = $result->rowCount();

    if($row_count > 0) {
        $category_arr = array();
        
        //loop while data exists, place data in row as assoc. array.
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $category_item = array(
                'id' => $id,
                'category' => $category
            );

            //push retrieved data
            array_push($category_arr, $category_item);
        }
        //reurn retrieved data
        echo json_encode($category_arr);

    } else{
        //message if no data retrieved.
        echo json_encode(
            array('message' => 'No Categories Found')
        );
    }
?>