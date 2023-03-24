<?php

namespace Vanier\Api\Controllers;

use Vanier\Api\Models\EntryModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EntryController extends BaseController
{
    private $entry_model = null;

    public function __construct(){
        $this->entry_model = new EntryModel();
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
