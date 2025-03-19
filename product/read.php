<?php
namespace Read;

use PDO;

use Models\Product;

// // include database and object files
//include_once '../database.php';

include_once __DIR__.'/../objects/product.php';
  
// instantiate database and product object
//$database = new Database();
//$db = $database->getConnection();
  
// initialize object
//$product = new Product($db);
function handleGet(){
    $product = new Product();  

    // read products will be here
    $stmt = $product->read();
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if ($num > 0) {
        // products array
        $products_arr=array();
        // $products_arr[]=array();
        // retrieve our table contents
        // fetch() is faster than fetchAll()
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($row);
            $product_item=array(
                "id" => $id,
                "name" => $name,
                "description" => html_entity_decode($description),
                "price" => $price,
                "category_id" => $category_id,
                "category_name" => $category_name
            );
            array_push($products_arr, $product_item);
        }
        // set response code - 200 OK
        http_response_code(200);
    
        // show products data in json format
        echo json_encode($products_arr);
    }
    else{
        // set response code - 404 Not found
        http_response_code(404);
        // tell the user no products found
        echo json_encode(
            array("message" => "No products found.")
        );
    }
}
?>