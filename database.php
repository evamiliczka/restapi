<?php
namespace Config;
use PDO;

require_once realpath(__DIR__ . "/vendor/autoload.php");
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


class Database{
    private $conn = null;
        public function __construct(){
            try{
                $this->conn = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
                $this->conn->exec("set names utf8");
            }
            catch(\PDOException $exception){
                echo "Connection error: " . $exception->getMessage();
            }
        }

        public function getConnection(){
            return $this->conn; 
        }
}
?>