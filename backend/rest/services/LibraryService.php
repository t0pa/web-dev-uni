<?php

require_once 'BaseService.php';
//require_once '../dao/LibraryDao.php';
require_once __DIR__ . '/../dao/LibraryDao.php';

class LibraryService extends BaseService {
    private $comicsService;

   public function __construct() {
       $dao = new LibraryDao();
       parent::__construct($dao);
       $this->comicsService = new ComicsService();   }

   public function getByStatus($userId, $status) {
       return $this->dao->getByStatus($userId, $status);
   }

   public function add_comic_to_library_by_id($id, $user_id = 1) {
    // Check if the comic exists using ComicsService
    $comic = $this->comicsService->getById($id);

    if ($comic) {
        // Prepare data to be inserted into the library
        $library_data = [
            'user_id' => $user_id,      // Default user_id set to 1
            'comic_id' => $id,          // The comic ID to add to the library
            'current_chapter' => 0,     // Default to chapter 0
            'status' => 'reading'       // Default status is 'reading'
        ];

        // Insert the comic into the library (using LibraryDao)
        $this->dao->insert($library_data);  // Assuming insert method exists in your LibraryDao

        return ['status' => 'success', 'message' => 'Comic added to library'];
    } else {
        return ['status' => 'error', 'message' => 'Comic not found'];
    }
}


public function remove_comic_from_library($id, $user_id = 1) {

    // Check if the comic exists in the user's library (based on both comic_id and user_id)
    $comic = $this->dao->getByComicIdAndUserId($id, $user_id);  // Ensure your DAO supports checking by both comic_id and user_id

    if ($comic) {
        // Proceed to remove the comic from the library
        return $this->dao->deleteByComicIdAndUserId($id, $user_id);  // Ensure delete method handles both comic_id and user_id
    } else {
        return false;  // Comic not found in the user's library
    }
}



}

?>
