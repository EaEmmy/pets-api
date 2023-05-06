<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Fig\Http\Message\StatusCodeInterface as HttpCodes;
use Vanier\Api\Helpers\Calculator;
use Vanier\Api\Models\DistanceModel;

/**
 * Summary of DistanceController
 */
class DistanceController extends BaseController
{

    /**
     * Summary of distance_model
     * @var
     */
    private $distance_model = null;
    /**
     * Summary of __construct
     */

    public function __construct()
    {
        $this->distance_model = new DistanceModel();
    }

    /**
     * Summary of handleDistance
     * @param Request $request
     * @param Response $response
     * @return Response
     */

     public function handleDistance(Request $request, Response $response)
    {
        $inputs = $request->getParsedBody();
        $from_prefix = $inputs['from'];
        $to_prefix = $inputs['to'];
        $unit_prefix = $inputs['unit'];

        $from = $this->distance_model->getCodeCoordinates($from_prefix);
        $to = $this->distance_model->getCodeCoordinates($to_prefix);
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
