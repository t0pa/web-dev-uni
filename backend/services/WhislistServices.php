<?php

require_once 'BaseService.php';
require_once 'WishlistDao.php';

class WishlistService extends BaseService {

   public function __construct() {
       $dao = new WishlistDao();
       parent::__construct($dao);
   }

   public function getWishlistByUserId($user_id) {
       return $this->dao->getWishlistByUserId($user_id);
   }
}

?>
