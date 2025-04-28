<?php

require_once 'BaseDao.php';

class ReviewsDao extends BaseDao {

    public function __construct() {
        parent::__construct("reviews");
    }

    public function getReviewsSortedByRating($sortOrder) {
        // Assume $sortOrder is already validated
        $sql = "SELECT * FROM reviews ORDER BY rating $sortOrder";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
