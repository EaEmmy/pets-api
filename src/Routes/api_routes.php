<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Vanier\Api\Controllers\AboutController;
use Vanier\Api\Controllers\PetsController;
use Vanier\Api\Controllers\CategoriesController;
use Vanier\Api\Controllers\AppearancesController;
use Vanier\Api\Controllers\BreedsController;
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
$app->get('/entries', [EntryController::class,'getAllEntries']);

//record
$app->get('/records', [RecordController::class,'getAllRecords']);

// // Get pets by record city name
// $app->get('/pets/{city}', [PetsController::class,'getPetsByCityName']);

// // Get pets from category by name
// $app->get('/pets/category/{name}', [PetsController::class,'getPetsByCategoryName']);

// // Get record by date type
// $app->get('/records/{date_type}', [RecordController::class,'getRecordByDateType']);

// // Get pets by breed
// $app->get('/pets/appearances/{fur}', [PetsController::class,'getPetsByFurName']);

//breeds
$app->get('/breeds', [BreedsController::class,'getAllBreeds']);

//breeds by id
$app->get('/breeds/{breed_id}', [BreedsController::class,'getBreedId']);

//pets by id
$app->get('/pets/{animal_id}', [PetsController::class,'getPetId']);

//categories by id
$app->get('/categories/{category_id}', [CategoriesController::class,'getCategoryId']);

//appearance by id
$app->get('/appearance/{appearance_id}', [AppearancesController::class,'getAppearanceId']);

// Get breeds by category id
$app->get('/breeds/category/{name}', [BreedsController::class,'getBreedsByCategoryName']);


/// ---------------------------TODO ---------------------------
//entry by id
$app->get('/entry/{entry_id}', [EntryController::class,'getBreedId']);

//record by id
$app->get('/record/{record_id}', [RecordController::class,'getBreedId']);


// Build 2 ----------------  TODO
// /pets/{pet_id}/breed

// /pets/{category_id}/breeds
// /pets/1/breeds     -> cat' breads
// /pets/2/breeds

// ROUTE: /hello
$app->get('/hello', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Reporting! Hello there!");    
    return $response;
});