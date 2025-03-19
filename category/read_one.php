<?php
namespace Read;



use Models\Category;

// // include database and object files
//include_once '../database.php';
include_once __DIR__.'/../objects/category.php';
include_once 'read_all_products_from_category.php';
  

function handleReadOne($categoryId){
    $category = new Category();  
    //not very correct, should have its own function to update id
   
    $category->id = $categoryId;

    // one category should be here
    $category->readOne();
    
    if ($category->name !=null){
        $category_item = array (
            "id" => $category->id,
            "name" => $category->name,
            "description" => $category->description,
            "created" => $category->created,
        );

      $products_arr =  handleGetAllProductsFromCategory($categoryId);
      $category_item["product_count"] = count($products_arr);
      $category_item["products_in_category"] = $products_arr;

        http_response_code(200);
        echo json_encode($category_item);
    }
    else{// set response code - 404 Not found
        http_response_code(404);
        echo json_encode(array("message" => "Category {$categoryId} does not exist."));}
    
}
