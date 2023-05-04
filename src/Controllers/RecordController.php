<?php

namespace Vanier\Api\Controllers;

use Vanier\Api\Models\RecordModel;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Log\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;


class RecordController extends BaseController
{
    private $record_model = null;

    public function __construct(){
        $this->record_model = new RecordModel();
    }

    public function handleCreateRecords(Request $request, Response $response){

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

    public function getRecordId(Request $request, Response $response, array $uri_args)
    {
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

    public function getAllRecords(Request $request, Response $response) {
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
