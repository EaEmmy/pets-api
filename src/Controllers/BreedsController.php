<?php

namespace Vanier\Api\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Vanier\Api\Models\BreedsModel;

/**
 * Summary of BreedsController
 */
class BreedsController
{
    private $breedModel = null;

     /**
      * Summary of __construct
      */
     public function __construct(){
        $this->breedModel = new BreedsModel();
    }

    /**
     * Summary of getBreedId
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @throws HttpNotFoundException
     * @return Response
     */
    public function getBreedId(Request $request, Response $response, array $uri_args)
    {
        $breed_id = $uri_args["breed_id"];
    
        if(empty($breed_id)){
            throw new HttpNotFoundException($request, "Invalid breed_id");
        }

        $breedModel = new BreedsModel();
        $data = $breedModel->getBreedId($breed_id);

     
        $json_data = json_encode($data);
        $response->getBody()->write($json_data);
        return $response->withStatus(200)->withHeader("Content-Type" , "application/json");
    }
    
    /**
     * Summary of getAllBreeds
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getAllBreeds(Request $request, Response $response) {
        $filters = $request->getQueryParams();
    
        $breedModel = new BreedsModel();
        if (isset($filters["page"], $filters["page_size"])) {
            $breedModel->setPaginationOptions($filters["page"], $filters["page_size"]);
        }
    
        $data = $breedModel->getAll($filters);
        $json_data = json_encode($data);
    
        $response->getBody()->write($json_data);
        return $response->withStatus(200)->withHeader("Content-Type","application/json");
    }

}
