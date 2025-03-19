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
    public $listOfProducts;
  
    // constructor with $db as database connection
    // public function __construct($db){
    //     $this->conn = $db;
    // }
    public function __construct(){
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
            //PDOStatement Object:
            return $stmt;
        }
        catch(\PDOException $e) {
            // Handle error
            die("Query failed: " . $e->getMessage()); // Or log the error instead of displaying it
        }
    }

  // create category
    function create(){
       try{
            $query = "INSERT INTO {$this->table_name}
                    SET name=:name,  description=:description, created=:created";
        
            // prepare query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->description=htmlspecialchars(strip_tags($this->description));
            $this->created=htmlspecialchars(strip_tags($this->created));
        
            // bind values
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":description", $this->description);
            $stmt->bindParam(":created", $this->created);
        
            // execute query
            return $stmt->execute();
       }
       catch(\PDOException $e) {
            // Handle error
            die("Insert failed: " . $e->getMessage()); // Or log the error instead of displaying it
        }
    } 

    function readOne(){
        $query = "SELECT * FROM {$this->table_name} WHERE id=:id LIMIT 1";
              
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam("id", $this->id);

        
        if ($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row != NULL) 
           {
                $this->name = $row['name'];
                $this->description = $row['description'];
                $this->created = $row['created'];
            }
        }
    }   
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

//     function update(){
//         $query = "UPDATE {$this->table_name} SET name=:name,
//         category_id=:category_id, price=:price, description=:description";

//         $stmt = $this->conn->prepare($query);

//         // sanitize
//         $this->name=htmlspecialchars(strip_tags($this->name));
//         $this->price=htmlspecialchars(strip_tags($this->price));
//         $this->description=htmlspecialchars(strip_tags($this->description));
//         $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        
//         // bind values
//         $stmt->bindParam(":name", $this->name);
//         $stmt->bindParam(":price", $this->price);
//         $stmt->bindParam(":description", $this->description);
//         $stmt->bindParam(":category_id", $this->category_id);

//         try{
//             return $stmt->execute();
//         }
//         catch  (\PDOException $e){
//             echo "Error updating: ".$e->getMessage();
//             die();
//         }
           
//     }
  

