<?php
namespace Update;

use Models\Product;

include_once __DIR__.'/../objects/product.php';

function handleUpdate($data){
    $product = new Product();

    if (!empty($data->id) && 
    !empty($data->name) && !empty($data->price) && !empty($data->description) && !empty($data->category_id))
    {
        $product->id = $data->id;
        $product->name = $data->name;
        $product->price = $data->price;
        $product->description = $data->description;
        $product->category_id = $data->category_id;

        if ($product->update()){
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