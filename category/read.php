<?php
namespace Read;

use PDO;

use Models\Category;

// // include database and object files
//include_once '../database.php';

include_once __DIR__.'/../objects/category.php';
  
// instantiate database and product object
//$database = new Database();
//$db = $database->getConnection();
  
// initialize object
//$product = new Product($db);
function handleGet(){
    $product = new Category();  

    // read products will be here
    $stmt = $product->read();
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if ($num > 0) {
        try{
            $result = $stmt->fetchall(PDO::FETCH_ASSOC);
            http_response_code(200);
           
            echo json_encode($result);
        }
        catch (\PDOException $e){
            echo json_encode(array("message" => $e->getMessage()));
            exit();
        }
    }
    else{
        // set response code - 404 Not found
        http_response_code(404);
        // tell the user no products found
        echo json_encode(
            array("message" => "No categories found.")
        );
    }
}
?>