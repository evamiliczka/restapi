<?php
namespace Controller;

use Models\Category;

include_once __DIR__.'/../objects/category.php';

class categoryController{

    private $requestMethod;
    private $categoryId;
    private $data;

    public function __construct ($requestMethod, $categoryId, $data){
        $this->requestMethod = $requestMethod;
        $this->categoryId    = $categoryId;
        $this->data = $data;
    }

    public function processRequest(){

        switch ($this->requestMethod) {
            case 'GET':
                if ($this->categoryId)
                {
                    $this->handleGetOne();
                }
                else
                {
                    $this->handleGet();
                }
            break;
            case 'POST':
                $this->handlePost();
            break;    
            case 'PUT':
               $this->handleUpdate();
            break;    
            default:
                http_response_code(405);
                echo json_encode(array('Message' => "Method {$this->requestMethod} Not Allowed"));
            break;
        }
    }

        private function handleUpdate(){
            $category = new Category();
            if ($category->update($this->data)) {
                http_response_code(200);
                echo  json_encode(array('Message' => "Category updated"));}
        }

        private function handleGet(){
            $category = new Category();  
            echo $category->read();
        }

        private function handleGetOne(){
            $category = new Category();  
            echo $category->readOne($this->categoryId);
        }

        private function handlePost(){
            $category = new Category();  
            if ($category->create($this->data)){
                http_response_code(201); //created
                echo  json_encode(array('Message' => "Category created"));
            }
        }
    }
    





