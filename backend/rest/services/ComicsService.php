<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../dao/ComicsDao.php';

class ComicsService extends BaseService {

   public function __construct() {
       $dao = new ComicsDao();
       parent::__construct($dao);
   }

   public function getByTitle($title) {
       
    return $this->dao->getByTitle($title);

   }

   public function getByAuthor($author) {
       return $this->dao->getByAuthor($author);
   }

 

    



}



?>
