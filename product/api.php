<?php


use Controller\productController;
use Api\ApiInitializer;
use Api\ApiUtils;
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../api/api.php';
require_once __DIR__.'/../product/productController.php';

ApiInitializer::setHeaders();


$parsedUrlSegments = ApiInitializer::parseUrlPath();
$requestMethod = ApiInitializer::getRequestMethod();
$requestData = ApiInitializer::getRequestData();
$productId = isset($parsedUrlSegments[4]) ? ApiUtils::validateId($parsedUrlSegments[4]) : null;


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



try {
    $controller = new productController($requestMethod, $productId, $requestData, $categoryId);
    $controller->processRequest();
} catch (PDOException $e) {
    http_response_code($e->getCode());
    echo json_encode(["message" => $e->getMessage()]);
    exit();
}



