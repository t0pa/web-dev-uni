<?php

require_once __DIR__ . '/ComicsDao.php';

// Create an instance of UserDao
$comicsDao = new ComicsDao();

$comicsDao -> insert([
    'title' => 'The Walking Dead',
    'author' => 'Charlie Adlard',
    'genre' => 'Horror',
    'total_chapters' => 193,
    'cover_image' => 'https://upload.wikimedia.org/wikipedia/en/c/cf/WalkingDead1.jpg'
]);
echo "Comic inserted successfully\n";



$comics=$comicsDao->getAll();
echo "All Comics:\n";
print_r($comics);

?>
