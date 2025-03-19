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
  
    // constructor with $db as database connection
    // public function __construct($db){
    //     $this->conn = $db;
    // }
    public function __construct(){
        $database = new Database();
        $this->conn = $database->getConnection();
    }


    // read products
    public function read(){
        // select all query
        $query = "SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
              FROM  " . $this->table_name . " p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
         //PDOStatement Object:
         return $stmt;
      //  return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

  // create product
    function create(){
        // query to insert record
        $query = "INSERT INTO {$this->table_name}
                SET name=:name, price=:price, description=:description, category_id=:category_id, created=:created";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->created=htmlspecialchars(strip_tags($this->created));
    
        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":created", $this->created);
    
        // execute query
        return $stmt->execute();
    } 

    //this function should be static...!!!
    function readAllProductsFromCategory($categoryId){
        try{
            $query = "SELECT id, name, price, description, created FROM products WHERE category_id=:categoryId";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":categoryId", $categoryId);
            $stmt->execute();
           
            return $stmt;
        }
        catch (\PDOException $e){
            echo "Error retrieving products from a category: ".$e->getMessage();
            die();
        }

    }

    function readOne(){
        $query = "SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
              FROM  {$this->table_name} p LEFT JOIN categories c ON p.category_id = c.id 
              WHERE p.id=:p_id LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam("p_id", $this->id);

        if ($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row != NULL) 
           {
                $this->name = $row['name'];
                $this->price = $row['price'];
                $this->description = $row['description'];
                $this->category_id = $row['category_id'];
                $this->category_name = $row['category_name'];
            }
        }
}

    function deleteOne(){
        $query ="DELETE  FROM {$this->table_name} WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id",$this->id);

        try {
            return $stmt->execute();
        }
        catch (\PDOException $e){
            echo "Error deleting: ".$e->getMessage();
            die();
            
        }
         
    }

    function update(){
        $query = "UPDATE {$this->table_name} SET name=:name,
        category_id=:category_id, price=:price, description=:description";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        
        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":category_id", $this->category_id);

        try{
            return $stmt->execute();
        }
        catch  (\PDOException $e){
            echo "Error updating: ".$e->getMessage();
            die();
        }
           
    }
  
}
?>