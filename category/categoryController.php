<?php
namespace Controller;

require_once 'read.php';
require_once 'post.php';
require_once 'read_one.php';
require_once 'update.php';

// include database and object files
include_once __DIR__.'/../objects/product.php';
//include_once __DIR__ .'/../database.php';


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
                    \Read\handleReadOne($this->categoryId);
                }
                else
                {
                    \Read\handleGet();
                }
            break;
            case 'POST':
                \Post\handlePost($this->data);
            break;    
            case 'PUT':
                \Update\handleUpdate($this->data);
            break;    
            default:
                echo json_encode(['message' => 'Invalid request method']);
            break;
        }
    }
    
}




