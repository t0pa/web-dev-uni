<?php

require_once 'BaseService.php';
require_once __DIR__ .'/../dao/WishlistDao.php';

class WishlistService extends BaseService {

    private ComicsService $comicsService; 
   public function __construct() {
       $dao = new WishlistDao();
       parent::__construct($dao);
       $this->comicsService = new ComicsService(); // Assuming you have a ComicsService class
   }

   public function getWishlistByUserId($user_id) {
       return $this->dao->getWishlistByUserId($user_id);    
   }


   public function add_comic_to_wishlist_by_id($id, $user_id) {
    // Check if the comic exists using ComicsService
    $comic = $this->comicsService->getById($id);

    if ($comic) {
        // Prepare data to be inserted into the library
        $wishlist_data = [
            'user_id' => $user_id,      // Default user_id set to 1
            'comic_id' => $id,          // The comic ID to add to the library
             // Default status is 'reading'
        ];

        // Insert the comic into the library (using LibraryDao)
        $this->dao->insert($wishlist_data);  // Assuming insert method exists in your LibraryDao

        return ['status' => 'success', 'message' => 'Comic added to library'];
    } else {
        return ['status' => 'error', 'message' => 'Comic not found'];
    }
}



public function remove_comic_from_wishlist($id, $user_id ) {

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
