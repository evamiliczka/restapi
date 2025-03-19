<?php
namespace Read;

use PDO;

use Models\Category;

// // include database and object files
//include_once '../database.php';

include_once __DIR__.'/../objects/category.php';
include_once 'read_all_products_from_category.php';

// instantiate database and product object
//$database = new Database();
//$db = $database->getConnection();
  
// initialize object
//$category = new Product($db);
function handleGet(){
    $category = new Category();  
    $stmt = $category->read();
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if ($num > 0) {
        try{
            $categories_arr = [];
            while ($rowCategory = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($rowCategory);
                $category_item=array(
                    "id" => $id,
                    "name" => $name,
                    "description" => html_entity_decode($description),
                    "created"=> $created
                );

                //now extract all products in a given category
                $products_arr = handleGetAllProductsFromCategory($category_item["id"]);
                //and add their count and the array to the corresponfing category
                $category_item["product_count"] = count($products_arr);
                $category_item["products_in_category"] = $products_arr;
                array_push($categories_arr, $category_item);
            }
            //list also all products in each category
            //$result = $stmt->fetchall(PDO::FETCH_ASSOC);
            
            http_response_code(200);          
            echo json_encode($categories_arr);
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