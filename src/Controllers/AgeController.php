<?php

namespace Vanier\Api\Controllers;

use DateTime;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Fig\Http\Message\StatusCodeInterface as HttpCodes;
use Vanier\Api\Helpers\Calculator;

/**
 * Summary of AgeController
 */
class AgeController extends BaseController
{

    /**
     * Summary of handleAge
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function handleAge(Request $request, Response $response)
    {
        $inputs = $request->getParsedBody();
        $dateOfBirth = $inputs['Date of Birth'];
        $date = $inputs['Age at the Date of'];

        $birthday = new DateTime($dateOfBirth);
        $newDate = new DateTime($date);

        $calculator = new Calculator();
        $age = $calculator->getAge($birthday, $newDate);

        $result = array(
            'age' => $age['years'],
            'months' => $age['months'],
            'days' => $age['days']
        );        

        $json_data = json_encode($result);
        $response->getBody()->write($json_data);
        return $response->withStatus(200)->withHeader("Content-Type","application/json");
    }
}
