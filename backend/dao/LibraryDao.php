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
}

?>
