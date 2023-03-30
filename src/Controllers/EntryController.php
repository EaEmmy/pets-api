<?php

namespace Vanier\Api\Controllers;

use Vanier\Api\Models\EntryModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;


class EntryController extends BaseController
{
    private $entry_model = null;

    public function __construct(){
        $this->entry_model = new EntryModel();
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
