<?php

namespace Vanier\Api\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Vanier\Api\Models\AppearancesModel;


class AppearancesController
{
    private $appearanceModel = null;

    public function __construct(){
        $this->appearanceModel = new AppearancesModel();
    }

    // get appearance by id 
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
