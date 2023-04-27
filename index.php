<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Controllers\PetsController;
use Vanier\Api\Controllers\CategoriesController;
use Vanier\Api\Controllers\AppearancesController;
use Vanier\Api\Middleware\ContentNegotiationMiddleware;

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

define('APP_BASE_DIR', __DIR__);
// What's up???
require __DIR__ . '/vendor/autoload.php';
 // Include the file that contains the application's global configuration settings,
 // database credentials, etc.
require_once __DIR__ . '/src/Config/app_config.php';

//--Step 1) Instantiate a Slim app.
$app = AppFactory::create();
//-- Add the routing and body parsing middleware.
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

//-- Add error handling middleware.
// NOTE: the error middleware MUST be added last.
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->getDefaultErrorHandler()->forceContentType(APP_MEDIA_TYPE_JSON);

// TODO: change the name of the subdirectory here.
// You also need to change it in .htaccess
$app->setBasePath("/pets-api");

// Here we include the file that contains the application routes. 
// NOTE: your routes must be managed in the api_routes.php file.
require_once __DIR__ . '/src/Routes/api_routes.php';

// Logging 
$app->get('/login', function (Request $request, Response $response, $args) {
    // 1) A new log channel for general message
    $logger = new Logger('access_logs');
    $logger->setTimezone(new DateTimeZone('America/Toronto'));
    $log_handler = new StreamHandler(APP_LOG_DIR.'access.log', Logger::DEBUG);
    $logger->pushHandler($log_handler);
    // 2) A new channel for db 
    $db_logger = new Logger("database_logs");
    // returns time 
    $db_logger->setTimezone(new DateTimeZone('America/Toronto'));
    $db_logger->pushHandler($log_handler);
    // here is your logging message body
    $db_logger->info("Hello, this our pets-api project.");
    $db_logger->info("Line 2 test");
    // general log message
    $params = $request->getQueryParams();
    $logger->info("Access: ".$request->getMethod().
    ' '.$request->getUri()->getPath(), $params);
    $response->getBody()->write("Reporting! Logging in process!");
    return $response; 
});

// Run the app.
$app->run();
