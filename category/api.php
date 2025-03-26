<?php

use Controller\categoryController;
use Api\ApiInitializer;
use Api\ApiUtils;
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../api/api.php';
require_once __DIR__.'/../category/categoryController.php';

ApiInitializer::setHeaders();

$parsedUrlSegments = ApiInitializer::parseUrlPath();
$requestMethod = ApiInitializer::getRequestMethod();
$requestData = ApiInitializer::getRequestData();
var_dump($parsedUrlSegments[4]);
$categoryId = isset($parsedUrlSegments[4]) ? ApiUtils::validateId($parsedUrlSegments[4]) : null;

try {
    $controller = new categoryController($requestMethod, $categoryId, $requestData);
    $controller->processRequest();
} catch (PDOException $e) {
    http_response_code($e->getCode());
    echo json_encode(["message" => $e->getMessage()]);
    exit();
}