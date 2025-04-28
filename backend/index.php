<?php


// CORS HEADERS - must be set before anything else
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'vendor/autoload.php';
//require_once __DIR__ . '/rest/services/LibraryService.php';
require_once 'rest/services/LibraryService.php';
require_once 'rest/services/ComicsService.php';

Flight::register('libraryService', 'LibraryService');

Flight::register('comicsService', 'ComicsService');

require_once __DIR__ . '/rest/routes/LibraryRoutes.php';

require_once __DIR__ . '/rest/routes/ComicsRoutes.php';

//require_once  "rest/routes/LibraryRoutes.php";
Flight::start();  //start FlightPHP

