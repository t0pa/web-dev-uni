<?php



require 'vendor/autoload.php';
//require_once __DIR__ . '/rest/services/LibraryService.php';
require_once 'rest/services/LibraryService.php';
require_once 'rest/services/ComicsService.php';
require_once 'rest/services/UserService.php';
require_once 'rest/services/WishlistService.php';
require_once 'rest/services/ReviewsService.php';
require_once 'rest/services/AuthService.php';
require_once "middleware/AuthMiddleware.php";
require_once  'data/Roles.php'; 

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

Flight::register('libraryService', 'LibraryService');

Flight::register('reviewsService', 'ReviewsService');

Flight::register('wishlistService', 'WishlistService');

Flight::register('userService', 'UserService');

Flight::register('comicsService', 'ComicsService');

Flight::register('auth_service', "AuthService");

Flight::register('auth_middleware', "AuthMiddleware");


Flight::route('/*', function() {
   if(
       strpos(Flight::request()->url, '/auth/login') === 0 ||
       strpos(Flight::request()->url, '/auth/register') === 0
   ) {
       return TRUE;
   } else {
       try {
           $token = Flight::request()->getHeader("Authentication");
           if(Flight::auth_middleware()->verifyToken($token))
               return TRUE;
       } catch (\Exception $e) {
           Flight::halt(401, $e->getMessage());
       }
   }
});


require_once __DIR__ .'/rest/routes/AuthRoutes.php';

require_once __DIR__ . '/rest/routes/UserRoutes.php';

require_once __DIR__ . '/rest/routes/ReviewsRoutes.php';

require_once __DIR__ . '/rest/routes/WishlistRoutes.php';

require_once __DIR__ . '/rest/routes/LibraryRoutes.php';

require_once __DIR__ . '/rest/routes/ComicsRoutes.php';



$allowedOrigins = [
    "http://localhost",
    "https://comicfront-app-dul8l.ondigitalocean.app"
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
    header("Access-Control-Allow-Credentials: true");  // only if needed
}

header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authentication");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204);
    exit();
}


//require_once  "rest/routes/LibraryRoutes.php";
Flight::start();  //start FlightPHP

