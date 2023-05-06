<?php

namespace Vanier\Api\Controllers;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\InvalidArgumentException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpNotFoundException;
use Vanier\Api\Models\PetsModel;
use Vanier\Api\Models\WSLoggingModel;

/**
 * Summary of PetsController
 */
class PetsController
{
    private $pet_model = null;

    /**
     * Summary of __construct
     */
    public function __construct(){
        $this->pet_model = new PetsModel();
    }

    //POST to create new pet
     /**
      * Summary of handleCreatePets
      * @param Request $request
      * @param Response $response
      * @throws HttpNotFoundException
      * @throws InvalidArgumentException
      * @return Response
      */
     public function handleCreatePets(Request $request, Response $response){

        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

        //step1: to retrieve from request body
        $pet_data = $request->getParsedBody();

        //VALIDATE 1-check if body is not empty
        if(empty($pet_data)){
            throw new HttpNotFoundException($request, "Error body cannot be empty");
        }
    
        //VALIDATE 2-if parsed body is an array
        if(!is_array($pet_data)){
            throw new InvalidArgumentException("Invalid data format: expected an array");
        }
    
        foreach ($pet_data as $key => $pet) {
            if($this->isValidPet($pet))
            {
                $this->pet_model->createPet($pet); 
            }   
        }
        return $response->withStatus(StatusCodeInterface::STATUS_CREATED);//->withHeader("Content-Type", "application/json");
    }

    // // validate film and set rules
    /**
     * Summary of isValidPet
     * @param mixed $pet
     * @throws InvalidArgumentException
     * @return bool
     */
    private function isValidPet($pet)
    {
        
        // rules to validate 
        $rules = array(
            'name' => array(
                array('lengthBetween', 1, 20),
                array('regex', '/^[a-zA-Z\s]+$/')
            ),
            'age' => array(
                'integer'
            ),
            'gender' => array(
                array('in',array('male','female','neutered_male','spayed_female','unaltered'))
            ),
            'status' => array(
                array('in',array('lost','found','adoptable'))
            ),
            'current_location' => array(
                array('lengthBetween', 1, 80),
                array('regex', '/^[a-zA-Z\s]+$/')
            ),
            'breed_id' => array(
                'required',
                'integer',
                ['min', 1],
                ['max', 19]
            ),
            'record_id' => array(
                'required',
                'integer',
                ['min', 1]
               // ['max', 12]
            ),
            'appearance_id' => array(
                'required',
                'integer',
                ['min', 1]
                //['max', 14]
            )
        );
    
        // stores new film data
        $validator = new \Vanier\Api\Helpers\Validator($pet);

        // pass new film through rules array to check
        $validator->mapFieldsRules($rules);
        // validate the new actor, else catch error 
        if ($validator->validate()) {
            return true;
        } else {
            $errors = $validator->errors();
            $error_messages = array();
            foreach($errors as $field => $field_errors){
                foreach($field_errors as $error){
                    $error_messages[] = "$field: $error";
                }
            }
            $error_message = implode("; ", $error_messages);
            throw new InvalidArgumentException("Invalid: $error_message");
        }
    }

    /**
     * Summary of handleUpdatePets
     * @param Request $request
     * @param Response $response
     * @throws HttpNotFoundException
     * @throws InvalidArgumentException
     * @return Response
     */
    public function handleUpdatePets(Request $request, Response $response){

        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

        $pet_data = $request->getParsedBody();

            //VALIDATE 1-check if body is not empty
            if(empty($pet_data)){
                throw new HttpNotFoundException($request, "Error body cannot be empty");
            }
        
            //VALIDATE 2-if parsed body is an array
            if(!is_array($pet_data)){
                throw new InvalidArgumentException("Invalid data format: expected an array");
            }
               
        foreach ($pet_data as $key => $pet) {
            // validate rules for pet
            if($this->isValidPetUpdate($pet)){
                $animal_id = $pet['animal_id'];
                unset($pet['animal_id']);
                $this->pet_model->updatePet($pet, $animal_id); 
            }      
        }
    
        return $response->withStatus(StatusCodeInterface::STATUS_OK);
    }

    /**
     * Summary of isValidPetUpdate
     * @param mixed $pet
     * @throws InvalidArgumentException
     * @return bool
     */
    private function isValidPetUpdate($pet)
    {
        
        // rules to validate 
        $rules = array(
            'animal_id' => array(
                'required',
                'integer',
                array('lengthBetween', 1, 11)
            ),
            'name' => array(
                array('lengthBetween', 1, 20),
                array('regex', '/^[a-zA-Z\s]+$/')
            ),
            'age' => array(
                'integer'
            ),
            'gender' => array(
                array('in',array('male','female','neutered_male','spayed_female','unaltered'))
            ),
            'status' => array(
                array('in',array('lost','found','adoptable'))
            ),
            'current_location' => array(
                array('lengthBetween', 1, 80),
                array('regex', '/^[a-zA-Z\s]+$/')
            ),
            'breed_id' => array(
                'required',
                'integer',
                ['min', 1],
                ['max', 19]
            ),
            'record_id' => array(
                'required',
                'integer',
                ['min', 1]
               // ['max', 12]
            ),
            'appearance_id' => array(
                'required',
                'integer',
                ['min', 1]
                //['max', 14]
            )
        );
    
        // stores new film data
        $validator = new \Vanier\Api\Helpers\Validator($pet);

        // pass new film through rules array to check
        $validator->mapFieldsRules($rules);
        // validate the new actor, else catch error 
        if ($validator->validate()) {
            return true;
        } else {
            $errors = $validator->errors();
            $error_messages = array();
            foreach($errors as $field => $field_errors){
                foreach($field_errors as $error){
                    $error_messages[] = "$field: $error";
                }
            }
            $error_message = implode("; ", $error_messages);
            throw new InvalidArgumentException("Invalid: $error_message");
        }
    }
    
    // DELETE Pets 
    /**
     * Summary of handleDeletePets
     * @param Request $request
     * @param Response $response
     * @throws HttpNotFoundException
     * @throws InvalidArgumentException
     * @return Response
     */
    public function handleDeletePets(Request $request, Response $response){

        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

        //Step 1) retrieve data from request
        $pet_data = $request->getParsedBody();
        
        //VALIDATE 1-check if body is not empty
        if(empty($pet_data)){
            throw new HttpNotFoundException($request, "Error body cannot be empty");
        }
    
        //VALIDATE 2-if parsed body is an array
        if(!is_array($pet_data)){
            throw new InvalidArgumentException("Invalid data format: expected an array");
        }
    
        //Step 2) Delete film
        foreach($pet_data as $key => $animal_id){
            // Check if the film is valid and exists in the database
            if($this->isValidDeletePets([$animal_id])){
                //Step 3) Pass the film element/item to the model
                $this->pet_model->deletePet($animal_id);
            }           
        }
    
        return $response->withStatus(StatusCodeInterface::STATUS_CREATED);
    }
    
    // validate film_id before deletion
    /**
     * Summary of isValidDeletePets
     * @param mixed $pets
     * @throws InvalidArgumentException
     * @return bool
     */
    private function isValidDeletePets($pets)
    {       
        foreach ($pets as $animal_id) {
            // Check if the film exists in the database
            $valid_pet = $this->pet_model->getPetId($animal_id);
            if(!$valid_pet) {
                throw new InvalidArgumentException("Invalid animal_id");
            }
        }
        return true;
    }

    // get pet by id 
    /**
     * Summary of getPetId
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @throws HttpNotFoundException
     * @return Response
     */
    public function getPetId(Request $request, Response $response, array $uri_args)
    {
        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

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
   

    /**
     * Summary of getAllPets
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getAllPets(Request $request, Response $response) {

        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

        $filters = $request->getQueryParams();
    
        $pet_model = new PetsModel();
        if (isset($filters["page"], $filters["page_size"])) {
            $pet_model->setPaginationOptions($filters["page"], $filters["page_size"]);
        }
    
        // composite resources 
        $cat_facts_controller = new CatFactController();
        $joke_controller = new JokeController();
        $facts = $cat_facts_controller ->getCatFacts();
        $jokes = $joke_controller ->getJokes();
        $data = [
            'pets' => $this->pet_model->getAll($filters),
            'cat_facts' => $facts,
            'jokes' => $jokes
        ]; 
        $json_data = json_encode($data);
    
        $response->getBody()->write($json_data);
        return $response->withStatus(200)->withHeader("Content-Type","application/json");
    }


    /**
     * Summary of getPetsByCategory
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @return Response
     */
    public function getPetsByCategory(Request $request, Response $response, array $uri_args)
    {
        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

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

    /**
     * Summary of getPetsByEntry
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @return Response
     */
    public function getPetsByEntry(Request $request, Response $response, array $uri_args)
    {
        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);
        
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
