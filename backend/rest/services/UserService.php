<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';

class UserService extends BaseService {

   public function __construct() {
       $dao = new UserDao();
       parent::__construct($dao);
   }

   public function getByEmail($email) {
       return $this->dao->getByEmail($email);
   }
}

?>
