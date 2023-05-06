<?php

namespace Vanier\Api\Controllers;

use Vanier\Api\Models\RecordModel;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Log\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Vanier\Api\Models\WSLoggingModel;


/**
 * Summary of RecordController
 */
class RecordController extends BaseController
{
    private $record_model = null;

    /**
     * Summary of __construct
     */
    public function __construct(){
        $this->record_model = new RecordModel();
    }

    /**
     * Summary of handleCreateRecords
     * @param Request $request
     * @param Response $response
     * @throws HttpNotFoundException
     * @throws InvalidArgumentException
     * @return Response
     */
    public function handleCreateRecords(Request $request, Response $response){

        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

        //step1: to retrieve from request body
        $record_data = $request->getParsedBody();

        //VALIDATE 1-check if body is not empty
        if(empty($record_data)){
            throw new HttpNotFoundException($request, "Error body cannot be empty");
        }
    
        //VALIDATE 2-if parsed body is an array
        if(!is_array($record_data)){
            throw new InvalidArgumentException("Invalid data format: expected an array");
        }
    
        foreach ($record_data as $key => $record) {
            if($this->isValidRecord($record)){
                $this->record_model->createRecord($record); 
            }   
        }
        return $response->withStatus(StatusCodeInterface::STATUS_CREATED);//->withHeader("Content-Type", "application/json");
    }

    // public function handleDeleteRecords(Request $request, Response $response){

    //     //step1: to retrieve from request body
    //     $record_data = $request->getParsedBody();

    //     //VALIDATE 1-check if body is not empty
    //     if(empty($record_data)){
    //         throw new HttpNotFoundException($request, "Error body cannot be empty");
    //     }
    
    //     //VALIDATE 2-if parsed body is an array
    //     if(!is_array($record_data)){
    //         throw new InvalidArgumentException("Invalid data format: expected an array");
    //     }
    
    //     foreach ($record_data as $key => $record) {
    //         if($this->isValidDeleteRecord($record)){
    //             $this->record_model->deleteRecord($record); 
    //         }   
    //     }
    //     return $response->withStatus(StatusCodeInterface::STATUS_OK);//->withHeader("Content-Type", "application/json");
    // }

    // // validate film and set rules
    /**
     * Summary of isValidRecord
     * @param mixed $record
     * @throws InvalidArgumentException
     * @return bool
     */
    private function isValidRecord($record)
    {
        
        // rules to validate 
        $rules = array(
            'address' => array(
                array('lengthBetween', 1, 100),
                array('regex', '/^[a-zA-Z0-9\s]+$/')
            ),
            'city' => array(
                array('lengthBetween', 1, 40),
                array('regex', '/^[a-zA-Z\s]+$/')
            ),
            'state' => array(
                array('lengthBetween', 1, 10),
                array('regex', '/^[a-zA-Z\s]+$/')
            ),
            'postal_code' => array(
                array('lengthBetween', 1, 10),    
                array('regex', '/^[a-zA-Z0-9\s]+$/')     
            ),
            'jurisdiction' => array(
                array('lengthBetween', 1, 20),
                array('regex', '/^[a-zA-Z\s]+$/')
            ),
            'entry_id' => array(
                'required',
                'integer',
                ['min', 1]
            )
        );
    
        // stores new film data
        $validator = new \Vanier\Api\Helpers\Validator($record);

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

    // validate record_id before deletion
    // private function isValidDeleteRecord($record)
    // {       
    //     foreach ($record as $record_id) {
    //         // Check if the film exists in the database
    //         $valid_record = $this->record_model->getRecordId($record_id);
    //         if(!$valid_record) {
    //             throw new InvalidArgumentException("Invalid record_id");
    //         }
    //     }
    //     return true;
    // }

    /**
     * Summary of getRecordId
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @throws HttpNotFoundException
     * @return Response
     */
    public function getRecordId(Request $request, Response $response, array $uri_args)
    {
        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);
        
        $record_id = $uri_args["record_id"];
    
        if(empty($record_id)){
            throw new HttpNotFoundException($request, "Invalid record_id");
        }

        $recordModel = new RecordModel();
        $data = $recordModel->getRecordId($record_id);

     
        $json_data = json_encode($data);
        $response->getBody()->write($json_data);
        return $response->withStatus(200)->withHeader("Content-Type" , "application/json");
    }

    /**
     * Summary of getAllRecords
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getAllRecords(Request $request, Response $response) {

        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);
        
        $filters = $request->getQueryParams();
    
        $record_model = new RecordModel();
        if (isset($filters["page"], $filters["page_size"])) {
            $record_model->setPaginationOptions($filters["page"], $filters["page_size"]);
        }
    
        $data = $record_model->getAll($filters);
        $json_data = json_encode($data);
    
        $response->getBody()->write($json_data);
        return $response->withStatus(200)->withHeader("Content-Type","application/json");
    }
}
