<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Vanier\Api\Controllers\AboutController;
use Vanier\Api\Controllers\PetsController;
use Vanier\Api\Controllers\CategoriesController;
use Vanier\Api\Controllers\AppearancesController;
use Vanier\Api\Controllers\EntryController;
use Vanier\Api\Controllers\RecordController;

// Import the app instance into this file's scope.
global $app;

// NOTE: Add your app routes here.
// The callbacks must be implemented in a controller class.
// The Vanier\Api must be used as namespace prefix. 

// ROUTE: /
//root
$app->get('/', [AboutController::class, 'handleAboutApi']);

//pets
$app->get('/pets', [PetsController::class,'getAllPets']); 

//categories
$app->get('/categories', [CategoriesController::class,'getAllCategories']);

//appearances
$app->get('/appearances', [AppearancesController::class,'getAllAppearances']);

//entry
$app->get('/entry', [EntryController::class,'getAllEntries']);

//record
$app->get('/record', [RecordController::class,'getAllRecords']);

// Get pets by record city name
$app->get('/pets/{city}', [PetsController::class,'getPetsByCityName']);

// Get pets from category by name
$app->get('/pets/category/{name}', [PetsController::class,'getPetsByCategoryName']);

// Get pets from category by name
$app->get('/record/{date_type}', [RecordController::class,'getRecordByDateType']);

// ROUTE: /hello
$app->get('/hello', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Reporting! Hello there!");    
    return $response;
});