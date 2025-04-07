<?php

require_once 'BaseDao.php';

class ReviewsDao extends BaseDao {

    public function __construct() {
        parent::__construct("reviews");
    }

    public function getReviewsSortedByRating($sortOrder) {
        // Validate sort order to prevent SQL injection
        $sortOrder = strtoupper($sortOrder); // Convert to uppercase
        if (!in_array($sortOrder, ['ASC', 'DESC'])) {
            throw new Exception("Invalid sort order. Use 'ASC' or 'DESC'.");
        }

        // Prepare query with dynamic sorting
        $sql = "SELECT * FROM reviews ORDER BY rating $sortOrder";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
