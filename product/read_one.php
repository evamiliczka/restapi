<?php
namespace Read;

use PDO;

use Models\Product;

// // include database and object files
//include_once '../database.php';
include_once __DIR__.'/../objects/product.php';
  

function handleReadOne($productId){
    $product = new Product();  
    //not very correct, should have its own function to update id
    $product->id = $productId;



    // read products will be here
    $stmt = $product->readOne();
    
    if ($product->name !=null){
        $products_arr = array (
            "id" => $product->id,
            "name" => $product->name,
            "description" => $product->description,
            "price" => $product->price,
            "category_id" => $product->category_id,
            "category_name" => $product->category_name

        );

        http_response_code(200);
        echo json_encode($products_arr);
    }
    else{// set response code - 404 Not found
        http_response_code(404);
        echo json_encode(array("message" => "Product does not exist."));}
    
}
?>