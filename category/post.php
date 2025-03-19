<?php
namespace Post;

use Models\Category;

//include_once '../database.php';
include_once __DIR__.'/../objects/category.php';

function handlePost($data){
    $category = new Category();
    if (!empty($data->name) && !empty($data->description))
    {
        $category->name = $data->name;
        $category->description = $data->description;
        $category->created = date('Y-m-d H:i:s');

        //try to create category$category in database
        if ($category->create())
        {
            http_response_code(201); //created
            echo json_encode(array("message" => "category  was created"));
        }
        else
        {
            http_response_code(503); //service unavialable
            echo json_encode(array("message" => "Unable to create category"));
        }

    }
    else //data is incomplete
    {
        http_response_code(400); //bad request
        echo json_encode(array("message" => "Unable to create category. Data is incomplete"));
    }

}

