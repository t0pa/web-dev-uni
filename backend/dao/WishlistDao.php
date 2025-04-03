<?php

require_once 'BaseDao.php';

class WishlistDao extends BaseDao {
    public function __construct() {
        parent::__construct("wishlist");
    }

    // Get all wishlisted comics for a specific user
    public function getWishlistByUserId($user_id) {
        $stmt = $this->connection->prepare("
            SELECT comics.* 
            FROM wishlist
            JOIN comics ON wishlist.comic_id = comics.id
            WHERE wishlist.user_id = :user_id
        ");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

?>