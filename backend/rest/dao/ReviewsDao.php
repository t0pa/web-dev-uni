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


    public function get_user_review_for_comic($comic_id, $user_id) {
    $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE comic_id = :comic_id AND user_id = :user_id");
    $stmt->execute(['comic_id' => $comic_id, 'user_id' => $user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}

?>
