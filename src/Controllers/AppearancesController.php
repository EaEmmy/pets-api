<?php

namespace Vanier\Api\Controllers;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Psr\Log\InvalidArgumentException;
use Vanier\Api\Models\AppearancesModel;


/**
 * Summary of AppearancesController
 */
class AppearancesController
{
    private $appearanceModel = null;

    /**
     * Summary of __construct
     */
    public function __construct(){
        $this->appearanceModel = new AppearancesModel();
    }
    // create new appearance

    
    /**
     * Summary of handleCreateAppearances
     * @param Request $request
     * @param Response $response
     * @throws HttpNotFoundException
     * @throws InvalidArgumentException
     * @return Response
     */
    public function handleCreateAppearances(Request $request, Response $response){

        //step1: to retrieve from request body
        $appearance_data = $request->getParsedBody();

        //VALIDATE 1-check if body is not empty
        if(empty($appearance_data)){
            throw new HttpNotFoundException($request, "Error body cannot be empty");
        }
    
        //VALIDATE 2-if parsed body is an array
        if(!is_array($appearance_data)){
            throw new InvalidArgumentException("Invalid data format: expected an array");
        }
    
        foreach ($appearance_data as $key => $appearance) {
            if($this->isValidAppearance($appearance)){
                $this->appearanceModel->createAppearance($appearance); 
            }   
        }
        return $response->withStatus(StatusCodeInterface::STATUS_CREATED);//->withHeader("Content-Type", "application/json");
    }

    // // validate film and set rules
    /**
     * Summary of isValidAppearance
     * @param mixed $appearance
     * @throws InvalidArgumentException
     * @return bool
     */
    private function isValidAppearance($appearance)
    {
        
        // rules to validate 
        $rules = array(
            'fur' => array(
                array('lengthBetween', 1, 50),
                array('regex', '/^[a-zA-Z\s]+$/'
                )
            ),
            'color' => array(
                array('lengthBetween', 1, 50),
                array('regex', '/^[a-zA-Z\s]+$/'
                )
            )
        );
    
        // stores new film data
        $validator = new \Vanier\Api\Helpers\Validator($appearance);

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
    // get appearance by id 
    /**
     * Summary of getAppearanceId
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @throws HttpNotFoundException
     * @return Response
     */
    public function getAppearanceId(Request $request, Response $response, array $uri_args)
    {
        $appearance_id = $uri_args["appearance_id"];
    
        if(empty($appearance_id)){
            throw new HttpNotFoundException($request, "Invalid appearance_id");
        }

        $appearance_model = new AppearancesModel();
        $data = $appearance_model->getAppearanceId($appearance_id);

     
        $json_data = json_encode($data);
        $response->getBody()->write($json_data);
        return $response->withStatus(200)->withHeader("Content-Type" , "application/json");

    }
    /**
     * Summary of getAllAppearances
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getAllAppearances(Request $request, Response $response) {
        $filters = $request->getQueryParams();
    
        $appearance_model = new AppearancesModel();
        if (isset($filters["page"], $filters["page_size"])) {
            $appearance_model->setPaginationOptions($filters["page"], $filters["page_size"]);
        }
    
        $data = $appearance_model->getAll($filters);
        $json_data = json_encode($data);
    
        $response->getBody()->write($json_data);
        return $response->withStatus(200)->withHeader("Content-Type","application/json");
    }
}
