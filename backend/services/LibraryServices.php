<?php

require_once 'BaseService.php';
require_once 'LibraryDao.php';

class LibraryService extends BaseService {

   public function __construct() {
       $dao = new LibraryDao();
       parent::__construct($dao);
   }

   public function getByStatus($userId, $status) {
       return $this->dao->getByStatus($userId, $status);
   }
}

?>
