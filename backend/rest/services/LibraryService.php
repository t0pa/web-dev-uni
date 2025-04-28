<?php

require_once 'BaseService.php';
//require_once '../dao/LibraryDao.php';
require_once __DIR__ . '/../dao/LibraryDao.php';

class LibraryService extends BaseService {

   public function __construct() {
       $dao = new LibraryDao();
       parent::__construct($dao);
   }

   public function getByStatus($userId, $status) {
       return $this->dao->getByStatus($userId, $status);
   }

   public function add_comic_to_library($data) {
    $comic_id = $data['comic_id'];

    // Check if the comic exists
    $comic = Flight::comicService()->getById($comic_id);

    if (!$comic) {
        throw new Exception("Comic does not exist.");
    }

    // If exists, add to library
    return $this->libraryDao->add($data);
    }

   public function remove_comic_from_library($data) {
    $comic_id = $data['comic_id'];

    // Check if the comic exists
    $comic = Flight::comicService()->getById($comic_id);

    if (!$comic) {
        throw new Exception("Comic does not exist.");
    }

    // If exists, remove from library
    return $this->libraryDao->remove($data);


    }

}

?>
