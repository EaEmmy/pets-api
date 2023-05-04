<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Fig\Http\Message\StatusCodeInterface as HttpCodes;
use Vanier\Api\Helpers\Calculator;
use Vanier\Api\Models\DistanceModel;

class DistanceController extends BaseController
{

    private $distance_model = null;
    public function __construct()
    {
        $this->distance_model = new DistanceModel();
    }

    public function handleDistance(Request $request, Response $response)
    {
        $inputs = $request->getParsedBody();
        //var_dump($inputs);exit;
        $from_prefix = $inputs['from'];
        //var_dump($from_prefix);exit;
        $to_prefix = $inputs['to'];
        // var_dump($to_prefix);exit;
        $unit_prefix = $inputs['unit'];

        $from = $this->distance_model->getCodeCoordinates($from_prefix);
        $to = $this->distance_model->getCodeCoordinates($to_prefix);
        // var_dump($from);exit;
        $from_latitude = $from['latitude'];
        $from_longitude = $from['longitude'];;
        $to_latitude = $to['latitude'];;
        $to_longitude = $to['longitude'];;;

        $calculator = new Calculator();

        $distance = $calculator->calculate(
            $from_latitude,
            $from_longitude,
            $to_latitude,
            $to_longitude
        )->to($unit_prefix, 3, true);
            echo $distance;
        return $response;
    }
}
