<?php

namespace Vanier\Api\Controllers;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\InvalidArgumentException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpNotFoundException;
use Vanier\Api\Models\PetsModel;



class PetsController
{
    private $pet_model = null;

    public function __construct(){
        $this->pet_model = new PetsModel();
    }

     // POST to create new pet
    //  public function handleCreatePets(Request $request, Response $response){

    //     //step1: to retrieve from request body
    //     $pet_data = $request->getParsedBody();

    //     //VALIDATE 1-check if body is not empty
    //     if(empty($pet_data)){
    //         throw new HttpNotFoundException($request, "Error body cannot be empty");
    //     }
    
    //     //VALIDATE 2-if parsed body is an array
    //     if(!is_array($pet_data)){
    //         throw new InvalidArgumentException("Invalid data format: expected an array");
    //     }
    
    //     foreach ($pet_data as $key => $pet) {
    //         if($this->isValidPet($pet)){
    //             $this->pet_model->createPet($pet); 
    //         }   
    //     }
    //     return $response->withStatus(StatusCodeInterface::STATUS_CREATED);//->withHeader("Content-Type", "application/json");
    // }

    // // validate film and set rules
    // private function isValidPet($pet)
    // {
        
    //     // rules to validate 
    //     $rules = array(
    //         'title' => array(
    //             'required',
    //             array('lengthBetween', 1, 128),
    //             array('regex', '/^[a-zA-Z\s]+$/'
    //             )
    //         ),
    //         'description' => array(
    //             'required',
    //             array('lengthBetween', 1, 128),
    //             array('regex', '/^[a-zA-Z\s]+$/'
    //             )
    //         ),
    //         'release_year' => array(
    //             'required',
    //             'date' 
    //         ),
    //         'language_id' => array(
    //             'required',
    //             'integer'
    //         ),
    //         'original_language_id' => array(
    //             'integer'
    //         ),
    //         'rental_duration' => array(
    //             'required',
    //             'integer'
    //         ),
    //         'rental_rate' => array(
    //             'required',
    //             'decimal'
    //         ),
    //         'length' => array(
    //             'required',
    //             'integer'
    //         ),
    //         'replacement_cost' => array(
    //             'required',
    //             'decimal'
    //         ),
    //         'rating' => array(
    //             'required',
    //             array('in',array('G','PG','PG-13','R','NC-17'))
    //         ),
    //         'special_features' => array(
    //             array('in',array('Trailers','Commentaries','Deleted Scenes','Behind the Scenes'))
    //         ),
    //         'last_update' => array(
    //             'required',
    //             'date' 
    //         )
    //     );
    
    //     // stores new film data
    //     $validator = new \Vanier\Api\Helpers\Validator($pet);

    //     // pass new film through rules array to check
    //     $validator->mapFieldsRules($rules);
    //     // validate the new actor, else catch error 
    //     if ($validator->validate()) {
    //         return true;
    //     } else {
    //         $errors = $validator->errors();
    //         $error_messages = array();
    //         foreach($errors as $field => $field_errors){
    //             foreach($field_errors as $error){
    //                 $error_messages[] = "$field: $error";
    //             }
    //         }
    //         $error_message = implode("; ", $error_messages);
    //         throw new InvalidArgumentException("Invalid: $error_message");
    //     }
    // }

    // public function handleUpdatePets(Request $request, Response $response){

    //     $pet_data = $request->getParsedBody();

    //         //VALIDATE 1-check if body is not empty
    //         if(empty($pet_data)){
    //             throw new HttpNotFoundException($request, "Error body cannot be empty");
    //         }
        
    //         //VALIDATE 2-if parsed body is an array
    //         if(!is_array($pet_data)){
    //             throw new InvalidArgumentException("Invalid data format: expected an array");
    //         }
               
    //     foreach ($pet_data as $key => $pet) {
    //         // validate rules for pet
    //         if($this->isValidPet($pet)){
    //             $pet_id = $pet['pet_id'];
    //             $this->pet_model->updatePet($pet_id, $pet); 
    //         }      
    //     }
    
    //     return $response->withStatus(StatusCodeInterface::STATUS_OK);
    // }
    
    // // DELETE films 
    // public function handleDeletePets(Request $request, Response $response){
    //     //Step 1) retrieve data from request
    //     $pet_data = $request->getParsedBody();
        
    //     //VALIDATE 1-check if body is not empty
    //     if(empty($pet_data)){
    //         throw new HttpNotFoundException($request, "Error body cannot be empty");
    //     }
    
    //     //VALIDATE 2-if parsed body is an array
    //     if(!is_array($pet_data)){
    //         throw new InvalidArgumentException("Invalid data format: expected an array");
    //     }
    
    //     //Step 2) Delete film
    //     foreach($pet_data as $key => $pet_id){
    //         // Check if the film is valid and exists in the database
    //         if($this->isValidDeletePets([$pet_id])){
    //             //Step 3) Pass the film element/item to the model
    //             $this->pet_model->deleteFilm($pet_id);
    //         }           
    //     }
    
    //     return $response->withStatus(StatusCodeInterface::STATUS_CREATED);
    // }
    
    // // validate film_id before deletion
    // private function isValidDeletePets($pets)
    // {       
    //     foreach ($pets as $pet_id) {
    //         // Check if the film exists in the database
    //         $valid_pet = $this->pet_model->getPetId($pet_id);
    //         if(!$valid_pet) {
    //             throw new InvalidArgumentException("Invalid pet_id");
    //         }
    //     }
    //     return true;
    // }

    // get pet by id 
    public function getPetId(Request $request, Response $response, array $uri_args)
    {
        $pet_id = $uri_args["pet_id"];
    
        if(empty($pet_id)){
            throw new HttpNotFoundException($request, "Invalid pet_id");
        }

        $pet_model = new PetsModel();
        $data = $pet_model->getPetId($pet_id);

     
        $json_data = json_encode($data);
        $response->getBody()->write($json_data);
        return $response->withStatus(200)->withHeader("Content-Type" , "application/json");

    }
   

    public function getAllPets(Request $request, Response $response) {
        $filters = $request->getQueryParams();
    
        $pet_model = new PetsModel();
        if (isset($filters["page"], $filters["page_size"])) {
            $pet_model->setPaginationOptions($filters["page"], $filters["page_size"]);
        }
    
        $data = $pet_model->getAll($filters);
        $json_data = json_encode($data);
    
        $response->getBody()->write($json_data);
        return $response->withStatus(200)->withHeader("Content-Type","application/json");
    }


    public function getPetsByCategory(Request $request, Response $response, array $uri_args)
    {
        $filters = $request->getQueryParams();

        $category_id = $uri_args["category_id"];
        
        $pet_model = new PetsModel();
        
        if (isset($filters["page"], $filters["page_size"])) {
            $pet_model->setPaginationOptions($filters["page"], $filters["page_size"]);
        }

        $data = $pet_model->getPetsByCategoryId($category_id);

        $json_data = json_encode($data);
        $response->getBody()->write($json_data);
        return $response->withStatus(200)->withHeader("Content-Type","application/json");
    }

    public function getPetsByEntry(Request $request, Response $response, array $uri_args)
    {
        $filters = $request->getQueryParams();

        $entry_id = $uri_args["entry_id"];
        
        $pet_model = new PetsModel();
        
        if (isset($filters["page"], $filters["page_size"])) {
            $pet_model->setPaginationOptions($filters["page"], $filters["page_size"]);
        }

        $data = $pet_model->getPetsByEntryId($entry_id);

        $json_data = json_encode($data);
        $response->getBody()->write($json_data);
        return $response->withStatus(200)->withHeader("Content-Type","application/json");
    }
   
}
