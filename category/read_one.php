<?php
namespace Read;

use PDO;

use Models\Category;
use Models\Product;
// // include database and object files
//include_once '../database.php';
include_once __DIR__.'/../objects/category.php';

  

function handleReadOne($categoryId){
    $category = new Category();  
    //not very correct, should have its own function to update id
   
    $category->id = $categoryId;


    // one category should be here
    $category->readOne();
    
    if ($category->name !=null){
        $categories_arr = array (
            "id" => $category->id,
            "name" => $category->name,
            "description" => $category->description,
            "created" => $category->created,
        );

        $product = new Product();
        $stmt = $product->readAllProductsFromCategory($categoryId);
        $num = $stmt->rowCount();
        //echo($num);
        //var_dump($stmt->fetchAll(PDO::FETCH_ASSOC));
        $products_arr=[];
        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
             //   var_dump('snehuliak:', $row);
                extract($row);
                $product_item=array(
                    "id" => $id,
                    "name" => $name,
                    "description" => html_entity_decode($description),
                    "price" => $price,
                );
                array_push($products_arr, $product_item);
            }
        }
      $categories_arr["products_in_this_category"] = $products_arr;

        http_response_code(200);
        echo json_encode($categories_arr);
    }
    else{// set response code - 404 Not found
        http_response_code(404);
        echo json_encode(array("message" => "Category {$categoryId} does not exist."));}
    
}
?>