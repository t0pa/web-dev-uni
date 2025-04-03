<?php

require_once 'LibraryDao.php';

try {
    // Create instance of WishlistDao
    $reviewsDao = new LibraryDao();

    // Set a test user ID (change this to an actual user ID from your database)
    
    // Fetch wishlist for the test user
    $wishlist = $reviewsDao->getByStatus(2, 'reading');

    // Display results
    echo "Reviews sorted by rating (descending):\n";
    print_r($wishlist);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>
