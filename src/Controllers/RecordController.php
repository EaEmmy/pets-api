<?php

namespace Vanier\Api\Controllers;

use Vanier\Api\Models\RecordModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RecordController extends BaseController
{
    private $record_model = null;

    public function __construct(){
        $this->record_model = new RecordModel();
    }

    public function getRecordByDateType(Request $request, Response $response, array $uri_args)
    {
        $date_type = $uri_args["date_type"];
        $record_model = new RecordModel();
        $data = $record_model->getRecordByDate($date_type);
        
        $json_data = json_encode($data);
        $response->getBody()->write($json_data);
        return $response->withStatus(200)->withHeader("Content-Type","application/json");
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
