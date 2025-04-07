<?php

require_once 'BaseService.php';
require_once 'ReviewsDao.php';

class ReviewsService extends BaseService {

   public function __construct() {
       $dao = new ReviewsDao();
       parent::__consturct($dao);
   }

   public function getReviewsSortedByRating($sortOrder) {
       return $this->dao->getReviewsSortedByRating($sortOrder);
   }
}

?>
