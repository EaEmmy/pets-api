<?php

namespace Vanier\Api\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Vanier\Api\Models\CategoriesModel;


/**
 * Summary of CategoriesController
 */
class CategoriesController
{
    private $categoryModel = null;

    /**
     * Summary of __construct
     */
    public function __construct(){
        $this->categoryModel = new CategoriesModel();
    }

    // get category by id 
    /**
     * Summary of getCategoryId
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @throws HttpNotFoundException
     * @return Response
     */
    public function getCategoryId(Request $request, Response $response, array $uri_args)
    {
        $category_id = $uri_args["category_id"];
    
        if(empty($category_id)){
            throw new HttpNotFoundException($request, "Invalid category_id");
        }

        $category_model = new CategoriesModel();
        $data = $category_model->getCategoryId($category_id);

     
        $json_data = json_encode($data);
        $response->getBody()->write($json_data);
        return $response->withStatus(200)->withHeader("Content-Type" , "application/json");

    }
    /**
     * Summary of getAllCategories
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getAllCategories(Request $request, Response $response) {
        $filters = $request->getQueryParams();
    
        $category_model = new CategoriesModel();
        if (isset($filters["page"], $filters["page_size"])) {
            $category_model->setPaginationOptions($filters["page"], $filters["page_size"]);
        }
    
        $data = $category_model->getAll($filters);
        $json_data = json_encode($data);
    
        $response->getBody()->write($json_data);
        return $response->withStatus(200)->withHeader("Content-Type","application/json");
    }
}
