<?php
namespace Api;
//use Controller\categoryController;

require_once __DIR__ . '/../database.php';
//require_once 'categoryController.php';

class ApiInitializer
{
    const ALLOWED_HEADERS = [
        "Access-Control-Allow-Origin: *",
        "Content-Type: application/json; charset=UTF-8",
        "Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE",
        "Access-Control-Max-Age: 3600",
        "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"
    ];

    public static function setHeaders()
    {
        foreach (self::ALLOWED_HEADERS as $header) {
            header($header);
        }
    }

    public static function parseUrlPath(): array
    {
        $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return explode('/', $urlPath);
    }

    public static function getRequestMethod(): string
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    public static function getRequestData()
    {
        return json_decode(file_get_contents('php://input'));
    }
}

class ApiUtils
{
    public static function validateId($inputId): ?int
    {
        if (empty($inputId)) {
            return null;
        }

        if (!self::isIdValid($inputId)) {
            self::sendBadRequest("Bad request: expected .../id where id is an integer.");
        }

        return (int)$inputId;
    }

    private static function isIdValid($id): bool
    {
        return ctype_digit($id);
    }

    public static function sendBadRequest(string $message)
    {
        http_response_code(400);
        echo json_encode(['message' => $message]);
        exit();
    }
}

ApiInitializer::setHeaders();
