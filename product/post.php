<?php
namespace Post;

use Models\Product;

//include_once '../database.php';
include_once __DIR__.'/../objects/product.php';

function handlePost($data){
    $product = new Product();
   

    if (!empty($data->name) && !empty($data->price) && !empty($data->description) && !empty($data->category_id))
    {
        $product->name = $data->name;
        $product->price = $data->price;
        $product->description = $data->description;
        $product->category_id = $data->category_id;
        $product->created = date('Y-m-d H:i:s');

        //try to create product in database
        if ($product->create())
        {
            http_response_code(201); //created
            echo json_encode(array("message" => "Product was created"));
        }
        else
        {
            http_response_code(503); //service unavialable
            echo json_encode(array("message" => "Unable to create product"));
        }

    }
    else //data is incomplete
    {
        http_response_code(400); //bad request
        echo json_encode(array("message" => "Unable to create product. Data is incomplete"));
    }

}

