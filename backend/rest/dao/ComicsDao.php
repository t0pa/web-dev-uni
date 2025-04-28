<?php
require_once 'BaseDao.php';



class ComicsDao extends BaseDao {
   public function __construct() {
       parent::__construct("comics");
   }


   public function getByTitle($title) {
       $stmt = $this->connection->prepare("SELECT * FROM comics WHERE title = :title");
       $stmt->bindParam(':title', $title);
       $stmt->execute();
       return $stmt->fetch();
   }

   public function getByAuthor($author){
    $stmt=$this->connection->prepare("SELECT * FROM comics WHERE author = :author");
    $stmt->bindParam(':author',$author);
    $stmt->execute();
    return $stmt->fetch();
   }

 
   
}
?>
