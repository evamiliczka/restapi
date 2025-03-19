<?php
use Controller\categoryController;
require_once __DIR__.'/../database.php';
require_once 'categoryController.php';



header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

$data = json_decode(file_get_contents('php://input'));

$requestMethod = $_SERVER["REQUEST_METHOD"];

$id = null;
$uriLastElement = $uri[4];
if (isset($uriLastElement))
{
    // if last elment is set, it must be a number = an id
    if (intval($uriLastElement) != 0)
         {
            $id = intval($uriLastElement);
        }
    else{
        http_response_code(400); //bad request
        echo json_encode(['message' => 'Bad request expected /restapi/category/{id} where id is an integer']);
        die();
    }
}


// var_dump($uri);
// authenticate the request with Okta:
// if (! authenticate()) {
//     header("HTTP/1.1 401 Unauthorized");
//     exit('Unauthorized');
// }


// for now all of our endpoints start with /product
// everything else results in a 404 Not Found
try{
    $controller = new categoryController($requestMethod, $id, $data);
    $controller->processRequest();
}
catch (\PDOException $e){
    echo json_encode(array("message" => $e->getMessage()));
    exit();
}




