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
    $stmt = $this->connection->prepare("SELECT * FROM reviews WHERE comic_id = :comic_id AND user_id = :user_id");
    $stmt->execute(['comic_id' => $comic_id, 'user_id' => $user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    public function get_reviews_for_comic($comic_id) {
        $stmt = $this->connection->prepare("SELECT reviews.*, users.username FROM reviews JOIN users ON 
        reviews.user_id=users.id WHERE comic_id = :comic_id");
        $stmt->bindParam(':comic_id', $comic_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteById($id) {
        $sql = "DELETE FROM reviews WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

}

?>
