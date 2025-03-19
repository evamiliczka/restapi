<?php
namespace Controller;

require_once 'read.php';
require_once 'read_one.php';
require_once 'post.php';
require_once 'delete.php';
require_once 'update.php';
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once __DIR__.'/../objects/product.php';

include_once __DIR__ .'/../database.php';




class productController{

    private $requestMethod;
    private $productId;
    private $data;

    public function __construct ($requestMethod, $productId, $data){
       
        $this->requestMethod = $requestMethod;
        $this->productId    = $productId;
        $this->data = $data;
    }

    public function processRequest(){

        switch ($this->requestMethod) {
            case 'GET':
                if ($this->productId)
                {
                    \Read\handleReadOne($this->productId);
                }
                else{
                    \Read\handleGet();
                }
                break;
            case 'POST':
                \Post\handlePost($this->data);
                break;
            case 'DELETE':
                \Delete\handleDeleteOne($this->productId);
                break;
            case 'PUT':
                \Update\handleUpdate($this->data);
                break;
            //we expect /product/{id} to retrieve single product with the corresponding id
            default:
                echo json_encode(['message' => 'Invalid request method']);
                break;
        }
    }
    
}




