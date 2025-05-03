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

    public function getByComicIdAndUserId($comicId, $userId) {
        $sql = "SELECT * FROM wishlist WHERE comic_id = :comic_id AND user_id = :user_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':comic_id' => $comicId,
            ':user_id' => $userId
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteByComicIdAndUserId($comic_id, $user_id) {
        $stmt = $this->connection->prepare("DELETE FROM wishlist WHERE comic_id = :comic_id AND user_id = :user_id");
        $stmt->bindParam(':comic_id', $comic_id);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

}

?>