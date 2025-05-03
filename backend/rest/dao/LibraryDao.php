<?php

require_once 'BaseDao.php';

class LibraryDao extends BaseDao {

    public function __construct() {
        parent::__construct("library");
    }

    public function getByStatus($userId, $status) {
        $sql = "SELECT * FROM library WHERE user_id = :user_id AND status = :status";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':status' => $status
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function getByComicIdAndUserId($comicId, $userId) {
        $sql = "SELECT * FROM library WHERE comic_id = :comic_id AND user_id = :user_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':comic_id' => $comicId,
            ':user_id' => $userId
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteByComicIdAndUserId($comic_id, $user_id) {
        $stmt = $this->connection->prepare("DELETE FROM library WHERE comic_id = :comic_id AND user_id = :user_id");
        $stmt->bindParam(':comic_id', $comic_id);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

}

?>
