<?php
namespace Delete;

use Models\Product;

include_once __DIR__.'/../objects/product.php';

function handleDeleteOne($productId){
    if ($productId){
        $product = new Product();
        $product->id = $productId;
        if ($product->deleteOne())
        {
            http_response_code(204);
         
        }
    }
    else //no id is given
    {
        http_response_code(400);
        echo json_encode(array("Message"=>"BAd request - ID of product is not given"));
    }
}