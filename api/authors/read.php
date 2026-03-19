<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Authors.php';

    $database = new Database();
    $db = $database->connect();

    //create author instance
    $author = new Author($db);

    //call read method, asign revieved data.
    $result = $author->read();

    //determine amount of data recieved
    $row_count = $result->rowCount();
 
    if($row_count > 0){
        $author_arr = array();

        //loop while data exists, place data in row as assoc. array.
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $author_item = array(
                'id' => $id,
                'author' => $author
            );
            //push retrieved data
            array_push($author_arr, $author_item);
        }
        //reurn retrieved data
        echo json_encode($author_arr);

    } else{
        //message if no data retrieved.
        echo json_encode(
            array('message' => 'No Authors Found')
        );
    }
?>