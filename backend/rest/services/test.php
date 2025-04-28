<?php
require_once 'LibraryService.php';

$menu_item_service = new LibraryService();

$menus = $menu_item_service->getById(1); // Assuming you want to get the library with ID 1
print_r($menus);

?>
