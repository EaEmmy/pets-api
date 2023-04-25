<?php

namespace Vanier\Api\Controllers;

use Vanier\Api\Models\EntryModel;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Psr\Log\InvalidArgumentException;

class EntryController extends BaseController
{
    private $entry_model = null;

    public function __construct(){
        $this->entry_model = new EntryModel();
    }

    public function handleCreateEntries(Request $request, Response $response){

        //step1: to retrieve from request body
        $entry_data = $request->getParsedBody();

        //VALIDATE 1-check if body is not empty
        if(empty($entry_data)){
            throw new HttpNotFoundException($request, "Error body cannot be empty");
        }
    
        //VALIDATE 2-if parsed body is an array
        if(!is_array($entry_data)){
            throw new InvalidArgumentException("Invalid data format: expected an array");
        }
    
        foreach ($entry_data as $key => $entry) {
            if($this->isValidEntry($entry)){
                $this->entry_model->createEntry($entry); 
            }   
        }
        return $response->withStatus(StatusCodeInterface::STATUS_CREATED);//->withHeader("Content-Type", "application/json");
    }

    // public function handleDeleteEntries(Request $request, Response $response){

    //     //step1: to retrieve from request body
    //     $entry_data = $request->getParsedBody();

    //     //VALIDATE 1-check if body is not empty
    //     if(empty($entry_data)){
    //         throw new HttpNotFoundException($request, "Error body cannot be empty");
    //     }
    
    //     //VALIDATE 2-if parsed body is an array
    //     if(!is_array($entry_data)){
    //         throw new InvalidArgumentException("Invalid data format: expected an array");
    //     }
    
    //     foreach ($entry_data as $key => $entry) {
    //         if($this->isValidEntry($entry)){
    //             $this->entry_model->createEntry($entry); 
    //         }   
    //     }
    //     return $response->withStatus(StatusCodeInterface::STATUS_CREATED);//->withHeader("Content-Type", "application/json");
    // }

    // // validate entry and set rules
    private function isValidEntry($entry)
    {
        
        // rules to validate 
        $rules = array(
            'date' => array(
                'required',
                'date' 
            ),
            'date_type' => array(
                'required',
                array('in',array('receivedOn','dateLost','dateFound'))),
        );
    
        // stores new film data
        $validator = new \Vanier\Api\Helpers\Validator($entry);

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

    public function getEntryId(Request $request, Response $response, array $uri_args)
    {
        $entry_id = $uri_args["entry_id"];
    
        if(empty($entry_id)){
            throw new HttpNotFoundException($request, "Invalid entry_id");
        }

        $entryModel = new EntryModel();
        $data = $entryModel->getEntryId($entry_id);

     
        $json_data = json_encode($data);
        $response->getBody()->write($json_data);
        return $response->withStatus(200)->withHeader("Content-Type" , "application/json");
    }

    public function getAllEntries(Request $request, Response $response) {
        $filters = $request->getQueryParams();
    
        $entry_model = new EntryModel();
        if (isset($filters["page"], $filters["page_size"])) {
            $entry_model->setPaginationOptions($filters["page"], $filters["page_size"]);
        }
    
        $data = $entry_model->getAll($filters);
        $json_data = json_encode($data);
    
        $response->getBody()->write($json_data);
        return $response->withStatus(200)->withHeader("Content-Type","application/json");
    }
}
