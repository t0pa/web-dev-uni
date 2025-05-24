<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../dao/ReviewsDao.php';

class ReviewsService extends BaseService {

    public function __construct() {
        $dao = new ReviewsDao();
        parent::__construct($dao);
    }

    public function getReviewsSortedByRating($sortOrder) {
        $sortOrder = strtoupper($sortOrder); // Normalize case

        if (!in_array($sortOrder, ['ASC', 'DESC'])) {
            throw new Exception("Invalid sort order. Use 'ASC' or 'DESC'.");
        }

        return $this->dao->getReviewsSortedByRating($sortOrder);
    }


    public function create_review($comic_id, $user_id = 1 , $data) {
        // Validate the data before inserting
        if (empty($data['rating']) || empty($data['comment']) || $data['rating'] < 1 || $data['rating'] > 5) {
            throw new Exception("Missing required fields: comic_id, user_id, rating, review. Rating must be between 1 and 5.");
        }

        $review_data = [
            'user_id' => $user_id, // Default user_id set to 1
            'comic_id' => $comic_id,
            'rating' => $data['rating'],
            'comment' => $data['comment'],
        ];
        // Call the parent create method to insert the review
        return parent::create($review_data);
    }

    public function get_reviews_for_comic($comic_id) {
        // Validate the comic_id
        if (empty($comic_id)) {
            throw new Exception("Missing required field: comic_id.");
        }

        // Call the DAO method to get reviews for the specific comic
        return $this->dao->get_reviews_for_comic($comic_id);
    }

    public function update_review($review_id, $data) {
        // Validate the data before updating
        if (empty($data['rating']) || empty($data['comment']) || $data['rating'] < 1 || $data['rating'] > 5) {
            throw new Exception("Missing required fields: rating, review. Rating must be between 1 and 5.");
        }

        // Call the parent update method to update the review
        return parent::update($review_id, $data);
    }

    public function delete_review_by_user_and_comic($comic_id, $user_id) {
    $existing_review = $this->dao->get_user_review_for_comic($comic_id, $user_id);

    if (!$existing_review) {
        throw new Exception("Review not found or you're not the owner.");
    }

    return $this->dao->delete($existing_review['id']);
}




}

?>
