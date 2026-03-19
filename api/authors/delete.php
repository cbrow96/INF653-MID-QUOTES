<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Authors.php';

    $database = new Database();
    $db = $database->connect();


    $author = new Author($db);

    $data = json_decode(file_get_contents("php://input"));  //read and assign data

    //assign value if given, NULL otherwise
    $author->id = isset($data->id) ? $data->id : NULL;

    //check if all data present
    if($author->id != NULL){
        //atempt delete
        if($author->delete()){
                $author_arr = array(
                    'id' => $author->id
                );
            
                print_r(json_encode($author_arr));
        }
    }else{
        //message if all data is not present
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }
?>