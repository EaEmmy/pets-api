<?php

namespace Vanier\Api\Controllers;

use Vanier\Api\Models\EntryModel;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Psr\Log\InvalidArgumentException;
use Vanier\Api\Models\WSLoggingModel;

/**
 * Summary of EntryController
 */
class EntryController extends BaseController
{
    /**
     * Summary of entry_model
     * @var
     */
    private $entry_model = null;

    /**
     * Summary of __construct
     */
    public function __construct(){
        $this->entry_model = new EntryModel();
    }

    /**
     * Summary of handleCreateEntries
     * @param Request $request
     * @param Response $response
     * @throws HttpNotFoundException
     * @throws InvalidArgumentException
     * @return Response
     */
    public function handleCreateEntries(Request $request, Response $response){

        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

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
    //         if($this->isValidDeleteEntry($entry)){
    //             $this->entry_model->deleteEntry($entry); 
    //         }   
    //     }
    //     return $response->withStatus(StatusCodeInterface::STATUS_OK);//->withHeader("Content-Type", "application/json");
    // }

    // validate entry and set rules
    /**
     * Summary of isValidEntry
     * @param mixed $entry
     * @throws InvalidArgumentException
     * @return bool
     */
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

    // validate entry_id before deletion
    // private function isValidDeleteEntry($entry)
    // {       
    //     foreach ($entry as $entry_id) {
    //         // Check if the film exists in the database
    //         $valid_entry = $this->entry_model->getEntryId($entry_id);
    //         if(!$valid_entry) {
    //             throw new InvalidArgumentException("Invalid entry_id");
    //         }
    //     }
    //     return true;
    // }

    /**
     * Summary of getEntryId
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @throws HttpNotFoundException
     * @return Response
     */
    public function getEntryId(Request $request, Response $response, array $uri_args)
    {
        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

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

    /**
     * Summary of getAllEntries
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getAllEntries(Request $request, Response $response) {
        
        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

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
