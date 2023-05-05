<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Vanier\Api\Controllers\AboutController;
use Vanier\Api\Controllers\PetsController;
use Vanier\Api\Controllers\CategoriesController;
use Vanier\Api\Controllers\AppearancesController;
use Vanier\Api\Controllers\BreedsController;
use Vanier\Api\controllers\CatFactController;
use Vanier\Api\Controllers\EntryController;
use Vanier\Api\Controllers\RecordController;
use Vanier\Api\Controllers\DistanceController;

// Import the app instance into this file's scope.
global $app;

// NOTE: Add your app routes here.
// The callbacks must be implemented in a controller class.
// The Vanier\Api must be used as namespace prefix. 

// ROUTE: /
//root
$app->get('/', [AboutController::class, 'handleAboutApi']);

// -------------- GET ALL -------------
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

//breeds
$app->get('/breeds', [BreedsController::class,'getAllBreeds']);

// -------------GET BY ID ----------------

//breeds by id
$app->get('/breeds/{breed_id}', [BreedsController::class,'getBreedId']);

//pets by id
$app->get('/pets/{animal_id}', [PetsController::class,'getPetId']);

//categories by id
$app->get('/categories/{category_id}', [CategoriesController::class,'getCategoryId']);

//appearances by id
$app->get('/appearances/{appearance_id}', [AppearancesController::class,'getAppearanceId']);

//entries by id
$app->get('/entries/{entry_id}', [EntryController::class,'getEntryId']);

//records by id
$app->get('/records/{record_id}', [RecordController::class,'getRecordId']);


// ------------- Relationships ------------------

// Get pets by category id
$app->get('/categories/{category_id}/pets', [PetsController::class,'getPetsByCategory']);
// Get pets by entry id
$app->get('/entries/{entry_id}/pets', [PetsController::class,'getPetsByEntry']);

// ------------- POST ------------------
// Create new pet
$app->post('/pets', [PetsController::class,'handleCreatePets']); 
// Create new appearance
$app->post('/appearances', [AppearancesController::class,'handleCreateAppearances']);
// Create new entries
$app->post('/entries', [EntryController::class,'handleCreateEntries']);
// Create new records  
$app->post('/records', [RecordController::class,'handleCreateRecords']);

// ------------- UPDATE ------------------
$app->put('/pets', [PetsController::class,'handleUpdatePets']); 

// ------------- DELETE ------------------
// Delete pets
$app->delete('/pets', [PetsController::class,'handleDeletePets']); 
// Delete entries
//$app->delete('/entries', [EntryController::class,'handleDeleteEntries']);
// Delete records  
//$app->delete('/records', [RecordController::class,'handleDeleteRecords']);


//Distance 
$app->post('/distance', [DistanceController::class,'handleDistance']); 

// ROUTE: /hello
$app->get('/hello', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Reporting! Hello there!");    
    return $response;
});