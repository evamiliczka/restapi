<?php
namespace Update;

use Models\Category;

include_once __DIR__.'/../objects/category.php';

function handleUpdate($data){
    $category = new Category();

    if (!empty($data->id) && !empty($data->name)  && !empty($data->description))
    {
        $category->id = $data->id;
        $category->name = $data->name;     
        $category->description = $data->description;


        if ($category->update()){
            http_response_code(200);
            echo json_encode(array("message" => "Successfully updated"));
        }
        else{
            http_response_code(503); //service unavialable
            echo json_encode(array("message" => "Unable to update product"));
        }
    }
    else{
        http_response_code(400);
        echo json_encode(array("message" => "Unable to update product. Data is incomplete"));
    }
}