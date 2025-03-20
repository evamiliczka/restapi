<?php
namespace Models;
use Config\Database;

use PDO;


class Product{
    // database connection and table name
    private $conn;
    private $table_name = "products";
  
    // object properties
    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $category_name;
    public $created;
  
    public function __construct(){
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // create product
    function create($data){
        //create product
     
   
        if (!empty($data->name) && !empty($data->price) && !empty($data->description) && !empty($data->category_id))
        {
           
            $this->name = htmlspecialchars(strip_tags($data->name));
            $this->price = htmlspecialchars(strip_tags($data->price));
            $this->description = htmlspecialchars(strip_tags($data->description));
            $this->category_id = htmlspecialchars(strip_tags($data->category_id));
            $this->created = date('Y-m-d H:i:s');
            try{
                // query to insert record
                $query = "INSERT INTO {$this->table_name}
                        SET name=:name, price=:price, description=:description, category_id=:category_id, created=:created";
                $stmt = $this->conn->prepare($query);
                // bind values
                $stmt->bindParam(":name", $this->name);
                $stmt->bindParam(":price", $this->price);
                $stmt->bindParam(":description", $this->description);
                $stmt->bindParam(":category_id", $this->category_id);
                $stmt->bindParam(":created", $this->created);
            
                // execute query
                return $stmt->execute();
            }
            catch(\PDOException $e) {
                    http_response_code($e->getCode()); 
                    echo json_encode(array("message" => "Create failed: {$e->getMessage()}"));
                    return false; // Or log the error instead of displaying it
            }
        } //if
        else{
            http_response_code(400); //bad request
            echo json_encode(array("message" => "Unable to create product. Data is incomplete"));
            return false;
        }
    } 

    function readOne($productId){
        $this->id = htmlspecialchars(strip_tags($productId));
        try{
            $query = "SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                FROM  {$this->table_name} p LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id=:p_id LIMIT 0,1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam("p_id", $this->id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row != NULL) 
            {
                $this->name = $row['name'];
                $this->price = $row['price'];
                $this->description = $row['description'];
                $this->category_id = $row['category_id'];
                $this->category_name = $row['category_name'];
                $product_item = array (
                    "id" => $this->id,
                    "name" => $this->name,
                    "description" => $this->description,
                    "price" => $this->price,
                    "category_id" => $this->category_id,
                    "category_name" => $this->category_name
                );
                http_response_code(200);
                return json_encode($product_item);
            }
            else{// set response code - 404 Not found
                http_response_code(404);
                echo json_encode(array("message" => "Product {$productId} not found."));
                return false;
            }
    }  
    catch(\PDOException $e) {
        // Handle error
        http_response_code($e->getCode()); 
        echo json_encode(array("message" => "Query failed:  {$e->getMessage()}"));
        die(); // Or log the error instead of displaying it
    }
}

    // read all products
    public function read(){
        try{
            $query = "SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                FROM  " . $this->table_name . " p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $num = $stmt->rowCount();
            // check if more than 0 records found
            if ($num > 0) {
                // products array
                $products_arr=[];
                // retrieve our table contents
                // fetch() is faster than fetchAll()
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    // extract row this will make $row['name'] to just $name only
                    extract($row);
                    $product_item=array(
                        "id" => $id,
                        "name" => $name,
                        "description" => html_entity_decode($description),
                        "price" => $price,
                        "category_id" => $category_id,
                        "category_name" => $category_name
                    );
                    array_push($products_arr, $product_item);
                }
                http_response_code(200);
                return json_encode($products_arr);
            }
            else{
                // set response code - 404 Not found
                http_response_code(404);
                // tell the user no products found
                echo json_encode(array("message" => "No products found."));
                return NULL;
                
            }
        }
        catch(\PDOException $e) {
            // Handle error
            http_response_code($e->getCode()); 
            return json_encode(array("message" => "Query failed:  {$e->getMessage()}"));
            die(); // Or log the error instead of displaying it
        }
    } //read

   function readAllProductsFromCategoryNotStatic($categoryId){
        return json_encode(Product::readAllProductsFromCategory($categoryId, $this->conn));
   }

    static function readAllProductsFromCategory($categoryId, $connection){
        $categoryId = htmlspecialchars(strip_tags($categoryId));
        try{
            $query = "SELECT id, name, price, description, created FROM products WHERE category_id=:categoryId";
            $stmt = $connection->prepare($query);

            $stmt->bindParam(":categoryId", $categoryId);
            $stmt->execute();
            
            $num = $stmt->rowCount();
            //echo($num);
            //var_dump($stmt->fetchAll(PDO::FETCH_ASSOC));
            $products_arr=[];
            if ($num > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                {
                //   var_dump('snehuliak:', $row);
                    extract($row);
                    $product_item=array(
                        "id" => $id,
                        "name" => $name,
                        "description" => html_entity_decode($description),
                        "price" => $price,
                    );
                    array_push($products_arr, $product_item);
                }
            }
            else{
                return array();
            }
            return $products_arr;
        }
        catch (\PDOException $e){
            http_response_code($e->getCode()); 
            echo "Error retrieving products from a category: ".$e->getMessage();
            die();
          
        }

    }
 

    function deleteOne($productId){

        if ($productId){
            $this->id=htmlspecialchars(strip_tags($productId));

            try{
                $query ="DELETE  FROM {$this->table_name} WHERE id=:id";
                $stmt = $this->conn->prepare($query);

                $stmt->bindParam(":id",$this->id);
                $result = $stmt->execute();

            return $stmt->execute();
            }
            catch (\PDOException $e){
                http_response_code($e->getCode()); 
                echo json_encode(array("message" => "Delete failed:  {$e->getMessage()}"));
                die(); // Or log the error instead of displaying it
            }
        }
        else //no id is given
        {
            http_response_code(400);
            echo json_encode(array("Message"=>"BAd request - ID of product is not given"));
            return false;
        }
         
    }

    function update($data){ 
        if (!empty($data->id) && !empty($data->name) && !empty($data->price) && !empty($data->description) && !empty($data->category_id))
        {   
            //prepare object
            $this->id = htmlspecialchars(strip_tags($data->id));
            $this->name = htmlspecialchars(strip_tags($data->name));
            $this->price = htmlspecialchars(strip_tags($data->price));
            $this->description = htmlspecialchars(strip_tags($data->description));
            $this->category_id = htmlspecialchars(strip_tags($data->category_id));
            try{
                $query = "UPDATE {$this->table_name} SET name=:name,
                category_id=:category_id, price=:price, description=:description WHERE id=:productId";

                $stmt = $this->conn->prepare($query);
                
                // bind values
                $stmt->bindParam(":productId", $this->id);
                $stmt->bindParam(":name", $this->name);
                $stmt->bindParam(":price", $this->price);
                $stmt->bindParam(":description", $this->description);
                $stmt->bindParam(":category_id", $this->category_id);
                return $stmt->execute();
            }
            catch  (\PDOException $e){
                http_response_code($e->getCode()); 
                return json_encode(array("message" => "Update of product failed:  {$e->getMessage()}"));
                die(); // Or log the error instead of displaying it
            }
        }
        else{
            http_response_code(400);
            echo json_encode(array("message" => "Unable to update product. Data is incomplete"));
            return false;
        }
    } //update
}
?>