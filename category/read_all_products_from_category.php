<?php
namespace Read;

use PDO;

use Models\Product;


function handleGetAllProductsFromCategory($categoryId){
    try{
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
    return $products_arr;
    }
    catch (\PDOException $e){
        echo json_encode(array("message" => $e->getMessage()));
        exit();
    }
}
?>