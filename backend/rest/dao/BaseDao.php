<?php
require_once 'config.php';


class BaseDao {
   protected $table;
   protected $connection;

   public function __construct($table) {
       $this->table = $table;
       $this->connection = Database::connect();
   }

   public function getAll() {
       $stmt = $this->connection->prepare("SELECT * FROM " . $this->table);
       $stmt->execute();
       return $stmt->fetchAll();
   }

   public function getById($id) {
       $stmt = $this->connection->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");
       $stmt->bindParam(':id', $id);
       $stmt->execute();
       return $stmt->fetch();
   }

   public function insert($data) {
       $columns = implode(", ", array_keys($data));
       $placeholders = ":" . implode(", :", array_keys($data));
       $sql = "INSERT INTO " . $this->table . " ($columns) VALUES ($placeholders)";
       $stmt = $this->connection->prepare($sql);
       return $stmt->execute($data);
   }

   public function update($id, $data) {
       $fields = "";
       foreach ($data as $key => $value) {
           $fields .= "$key = :$key, ";
       }
       $fields = rtrim($fields, ", ");
       $sql = "UPDATE " . $this->table . " SET $fields WHERE id = :id";
       $stmt = $this->connection->prepare($sql);
       $data['id'] = $id;
       return $stmt->execute($data);
   }

  /*   public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }    */
    public function delete($id) {
        // DEBUG
        echo "ID to delete: $id\n";  // Output the ID you are trying to delete
    
        // Prepare the SQL query
        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
    
        // Debug: Show the final SQL query (excluding binding values)
        echo "SQL: $sql\n";
    
        // Prepare the statement
        $stmt = $this->connection->prepare($sql);
    
        // Bind the parameter
        $stmt->bindParam(':id', $id);
    
        // Debug: Check the statement before executing
        echo "Executing query...\n";
    
        // Execute the query
        $result = $stmt->execute();
    
        // Debug: Check if the query executed successfully
        echo "Delete result: " . ($result ? "Success" : "Failure") . "\n";
    
        return $result;
    }
    

}