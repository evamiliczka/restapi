<?php
namespace Controller;
use  Models\Product;
include_once __DIR__.'/../models/product.php';

class productController{

    private $requestMethod;
    private $productId;
    private $categoryId;
    private $data;

    public function __construct ($requestMethod, $productId, $data, $categoryId){
        $this->requestMethod = $requestMethod;
        $this->productId    = $productId;
        $this->data = $data;
        $this->categoryId    = $categoryId;
    }

    public function processRequest(){
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->productId)
                {
                    $this->handleGetOne();
                }
                else{
                    if ($this->categoryId){
                        $this->handleGetByCategory();
                    }
                    else
                    {
                       $this->handleGet();
                    }
                }
                break;
            case 'POST':
                    $this->handlePost();
                break;  
            case 'DELETE':
                $this->handleDeleteOne($this->productId);
                break;
            case 'PUT':
                $this->handleUpdate();
                break; 
            //we expect /product/{id} to retrieve single product with the corresponding id
            default:
                echo json_encode(['message' => 'Invalid request method']);
                break;
        }
    }

    private function handleGet(){
        $product = new Product();  
        echo $product->read();
    }
    private function handleGetOne(){
            $product = new Product();  
            echo $product->readOne($this->productId);
    }


    private function handleGetByCategory(){
        $product = new Product();  
        echo $product->readAllProductsFromCategoryNotStatic($this->categoryId);
}
    private function handlePost(){
        $product = new Product();  
        if ($product->create($this->data)){
            http_response_code(201); //created
            echo  json_encode(array('Message' => "Product created"));
        }
    }

    private function handleUpdate(){
        $product   = new Product();
        if ($product->update($this->data)) {
            http_response_code(200);
            echo  json_encode(array('Message' => "Product updated"));}
    }
    private function handleDeleteOne(){

        $product = new Product();  
        if ($product->deleteOne($this->productId)) {
            http_response_code(200);
            echo  json_encode(array('Message' => "Product deleted"));
        }
        else{
            echo  json_encode(array('Message' => "Something went wrong;"));
        }
    }
}




