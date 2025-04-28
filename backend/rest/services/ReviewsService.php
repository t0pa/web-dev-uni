<?php

require_once 'BaseService.php';
require_once '../dao/ReviewsDao.php';

class ReviewsService extends BaseService {

    public function __construct() {
        $dao = new ReviewsDao();
        parent::__construct($dao);
    }

    public function getReviewsSortedByRating($sortOrder) {
        $sortOrder = strtoupper($sortOrder); // Normalize case

        if (!in_array($sortOrder, ['ASC', 'DESC'])) {
            throw new Exception("Invalid sort order. Use 'ASC' or 'DESC'.");
        }

        return $this->dao->getReviewsSortedByRating($sortOrder);
    }
}

?>
