<?php
namespace Models;
use Config\Database;

use PDO;

class Category{
    // database connection and table name
    private $conn;
    private $table_name = "categories";
  
    // object properties
    public $id;
    public $name;
    public $description;
    public $created;

  
    // constructor with $db as database connection
    // public function __construct($db){
    //     $this->conn = $db;
    // }
    public function __construct(){
        // if ($id) $this->id = htmlspecialchars(strip_tags($id));
        // if ($name) $this->name = htmlspecialchars(strip_tags($name));
        // if ($description) $this->description = htmlspecialchars(strip_tags($description));
        // if ($created) $this->created = htmlspecialchars(strip_tags($created));

        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function read(){
        try{
            // select all query
            $query = "SELECT id, name, description, created FROM {$this->table_name}";
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            // execute query
            $stmt->execute();
            $num = $stmt->rowCount();
            // check if more than 0 record found
            if ($num > 0) {
                    $categories_arr = [];
                    while ($rowCategory = $stmt->fetch(PDO::FETCH_ASSOC)){
                        extract($rowCategory);
                        $category_item=array(
                            "id" => $id,
                            "name" => $name,
                            "description" => html_entity_decode($description),
                            "created"=> $created
                        );
                        //now extract all products in a given category
                    //    $products_arr = handleGetAllProductsFromCategory($category_item["id"]);
                    $products_arr=[];
                        //and add their count and the array to the corresponfing category
                        $category_item["product_count"] = count($products_arr);
                        $category_item["products_in_category"] = $products_arr;
                        array_push($categories_arr, $category_item);
                    }
                    //list also all products in each category                 
                    http_response_code(200);          
                    return json_encode($categories_arr);
            }
            else{
                http_response_code(404); //404 Not found
                return json_encode(array("message" => "No categories found."));
            }
        }
        catch(\PDOException $e) {
            // Handle error
            http_response_code($e->getCode()); 
            return json_encode(array("message" => "Query failed:  {$e->getMessage()}"));
            die(); // Or log the error instead of displaying it
        }
    } //read

  // create category
    function create($data){
        if (!empty($data->name) && !empty($data->description))
        {
            // sanitize
            $this->name=htmlspecialchars(strip_tags($data->name));
            $this->description=htmlspecialchars(strip_tags($data->description));
            // set data
            $this->name = $data->name;
            $this->description = $data->description;
            $this->created = date('Y-m-d H:i:s');
        
            try{
                $query = "INSERT INTO {$this->table_name} SET name=:name,  description=:description, created=:created";
                $stmt = $this->conn->prepare($query);
                // bind values
                $stmt->bindParam(":name", $this->name);
                $stmt->bindParam(":description", $this->description);
                $stmt->bindParam(":created", $this->created);
                // execute query
                return $stmt->execute();
            }
            catch(\PDOException $e) {
                    http_response_code($e->getCode()); 
                    return json_encode(array("message" => "Insert failed: {$e->getMessage()}"));
                    die(); // Or log the error instead of displaying it
                }
        } //if
        else{
            http_response_code(400); //bad request
            return json_encode(array("message" => "Unable to create category. Data is incomplete"));
        }
    } //create

    function readOne($categoryId){
        try{
            $query = "SELECT * FROM {$this->table_name} WHERE id=:id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $this->id = htmlspecialchars(strip_tags($categoryId));
            $stmt->bindParam("id", $this->id);
            
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row != NULL) 
            {
                $this->name = $row['name'];
                $this->description = $row['description'];
                $this->created = $row['created'];
                $category_item = array (
                    "id" => $this->id,
                    "name" => $this->name,
                    "description" => $this->description,
                    "created" => $this->created,
                );
        //   $products_arr =  handleGetAllProductsFromCategory($categoryId);
                $products_arr = [];
                $category_item["product_count"] = count($products_arr);
                $category_item["products_in_category"] = $products_arr;
                http_response_code(200);
                echo json_encode($category_item);
            }
            else{// set response code - 404 Not found
                http_response_code(404);
                echo json_encode(array("message" => "Category {$categoryId} not found."));
            }
        }
        catch(\PDOException $e) {
            // Handle error
            http_response_code($e->getCode()); 
            return json_encode(array("message" => "Query failed:  {$e->getMessage()}"));
            die(); // Or log the error instead of displaying it
        }
    } //readOne
    
         function update($data){
            try{
                if (!empty($data->id) && !empty($data->name)  && !empty($data->description))
                {
                    //prepare object
                    $this->id=htmlspecialchars(strip_tags($data->id));
                    $this->name=htmlspecialchars(strip_tags($data->name));
                    $this->description=htmlspecialchars(strip_tags($data->description));
                    //prepare query
                    $query = "UPDATE {$this->table_name} SET name=:name, description=:description WHERE id=:category_id";     
                    $stmt = $this->conn->prepare($query);
                    // bind 
                    $stmt->bindParam(":category_id", $this->id);
                    $stmt->bindParam(":name", $this->name);
                    $stmt->bindParam(":description", $this->description);
                    
                    return $stmt->execute();
                }
                else
                {
                    http_response_code(400);
                    return json_encode(array("message" => "Unable to update category. Data is incomplete"));
                }
            }
            catch (\PDOException $e) {
                // Handle error
                http_response_code($e->getCode()); 
                return json_encode(array("message" => "Update of category failed:  {$e->getMessage()}"));
                die(); // Or log the error instead of displaying it
            }
        } //update
    }

//     function deleteOne(){
//         $query ="DELETE  FROM {$this->table_name} WHERE id=:id";
//         $stmt = $this->conn->prepare($query);

//         $stmt->bindParam(":id",$this->id);

//         try {
//             return $stmt->execute();
//         }
//         catch (\PDOException $e){
//             echo "Error deleting: ".$e->getMessage();
//             die();
            
//         }
         
//     }


  

