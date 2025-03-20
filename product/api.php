<?php
use Controller\productController;
require_once __DIR__.'/../database.php';
require_once 'productController.php';
require_once __DIR__.'/../logger.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


/* With GET expect request in the form: 
    GET /restapi/product/api.php ..get all products
    GET  /restapi/product/api.php/{id} .. get product with id
    GET  /restapi/product/api.php?category={id} ... list all product from category id */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

$data = json_decode(file_get_contents('php://input'));

$requestMethod = $_SERVER["REQUEST_METHOD"];
$categoryId = null;

// resolve     GET  /restapi/product/api.php?category={id} ... list all product from category id */
// bad format, e.g.  /restapi/product/api.php?ffddd={id} is ignored
if (isset($_GET['category']))
{
 if (intval($_GET['category'])!== 0){
    $categoryId = intval($_GET['category']);
    }
else{
    http_response_code(400); //bad request
    echo json_encode(['message' => 'Bad request expected /restapi/product/api.php?category={id} where id is an integer']);
    die();
}
}



$id = null;
if (isset($uri[4]))
{
    // if last elment is set, it must be a number = an id
    if (empty($uri[4]) || intval($uri[4]) !== 0)
         {
            if (intval($uri[4]) !== 0) $id = intval($uri[4]);
        }
    else{
        http_response_code(400); //bad request
        echo json_encode(['message' => 'Bad request expected /restapi/product/api.php/{id} where id is an integer']);
        die();
    }
}




try{
    $controller = new productController($requestMethod, $id, $data, $categoryId);
    $controller->processRequest();
}
catch (\PDOException $e){
    echo json_encode(array("message" => $e->getMessage()));
    exit();
}




