<?php
use Controller\productController;
require_once __DIR__.'/../database.php';
require_once 'productController.php';



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
$uriLastElement = $uri[count($uri) - 1];
if (isset($uriLastElement) && intval($uriLastElement) != 0) {
    $id = intval($uriLastElement);
}

// var_dump($uri);
// authenticate the request with Okta:
// if (! authenticate()) {
//     header("HTTP/1.1 401 Unauthorized");
//     exit('Unauthorized');
// }


// for now all of our endpoints start with /product
// everything else results in a 404 Not Found
if ($uri[2] == 'product') {
    // pass the request method and user ID to the PersonController:
    $controller = new productController($requestMethod, $id, $data);
    $controller->processRequest();
}
else
{ 
    header("HTTP/1.1 404 Not Found");
    exit();
}




