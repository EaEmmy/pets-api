<?php

namespace Vanier\Api\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Vanier\Api\Models\CategoriesModel;


class CategoriesController
{
    private $categoryModel = null;

    public function __construct(){
        $this->categoryModel = new CategoriesModel();
    }

    // get category by id 
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
