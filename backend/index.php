<?php
// Absolute first thing in the file - error reporting and connection debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/rest/config.php';  // MOVED THIS UP

// 
// Immediate connection test - place right after error reporting
error_log("=== STARTING DEBUG ===");
error_log("Attempting DB connection to: ".Config::DB_HOST().":".Config::DB_PORT());
error_log("With user: ".Config::DB_USER());
// At the VERY top - no whitespace, no BOM, nothing before this
error_log('Register endpoint reached');

// Immediately set output buffering to prevent header issues
ob_start();

// Set CORS headers that will apply to ALL responses including errors
header("Access-Control-Allow-Origin: https://comicfront-app-dul8l.ondigitalocean.app");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Authentication");
header("Access-Control-Allow-Credentials: true");
header("Vary: Origin");  // Important for caching

// Handle OPTIONS requests immediately
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    ob_end_flush();
    exit();
}
//require_once __DIR__ . '/rest/services/LibraryService.php';
require_once 'rest/services/LibraryService.php';
require_once 'rest/services/ComicsService.php';
require_once 'rest/services/UserService.php';
require_once 'rest/services/WishlistService.php';
require_once 'rest/services/ReviewsService.php';
require_once 'rest/services/AuthService.php';
require_once "middleware/AuthMiddleware.php";
require_once  'data/roles.php'; 

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


// Register error handler to ensure CORS headers on errors
Flight::map('error', function(Throwable $error) {
    // Ensure headers are still set
    header("Access-Control-Allow-Origin: https://comicfront-app-dul8l.ondigitalocean.app");
    header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, Authentication");
    header("Access-Control-Allow-Credentials: true");
    
    error_log('Error: ' . $error->getMessage());
    Flight::halt(500, json_encode(['error' => 'Internal Server Error']));
});


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


// Database connection test endpoint - add this just before Flight::start()
Flight::route('/debug/db', function() {
    try {
        $dsn = "mysql:host=".Config::DB_HOST().";port=".Config::DB_PORT().";dbname=".Config::DB_NAME();
        $options = [
            PDO::MYSQL_ATTR_SSL_CA => __DIR__.'/ca-certificate.crt', // Remove if not using SSL
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        ];
        
        error_log("Full DSN: ".$dsn);
        $pdo = new PDO($dsn, Config::DB_USER(), Config::DB_PASSWORD(), $options);
        Flight::json(['success' => true, 'message' => 'DB Connection successful!']);
    } catch (PDOException $e) {
        error_log("DB Connection FAILED: ".$e->getMessage());
        Flight::halt(500, json_encode([
            'error' => 'DB Connection failed',
            'message' => $e->getMessage(),
            'details' => [
                'host' => Config::DB_HOST(),
                'port' => Config::DB_PORT(),
                'user' => Config::DB_USER(),
                'dbname' => Config::DB_NAME()
            ]
        ]));
    }
});

// Add this right after your existing routes
Flight::route('GET /debug/routes', function() {
    $routes = Flight::router()->getRoutes();
    Flight::json(array_map(function($route) {
        return [
            'pattern' => $route->pattern,
            'methods' => $route->methods
        ];
    }, $routes));
});

//require_once  "rest/routes/LibraryRoutes.php";
Flight::start();  //start FlightPHP

