<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Helpers\Validator;

/**
 * Summary of AboutController
 */
class AboutController extends BaseController
{
    /**
     * Summary of handleAboutApi
     * @param Request $request
     * @param Response $response
     * @param array $uri_args
     * @return Response
     */
    public function handleAboutApi(Request $request, Response $response, array $uri_args)
    {
        $data = array(
            'project name' => 'pets-api',
            'about' => 'An api about lost, found and adoptable pets.',
            'authors' => 'Emmy Ea, Jiamin Yuan, Konstantinos Nikopoulos',
            'version' => 'version here: note to teacher -> remind us versioning to the api requirement number 9 in docs ',
            'resources' =>  array(
                '/pets' => 'List of pets. Cats, Dogs, Goats, Birds, Rabbits and Hamsters',
                '/breeds' => 'List of breeds. Type of breed.',
                '/entries' => 'List of entries. Date of pets arrival',
                '/appearances' => 'List of appearance. Physical description of pets.',
                '/records' => 'List of records. Address of shelters.',
                '/categories' => 'List of categories. Type of pets.'),
            'uris' =>  array(
                '/pets/{animal_id}' => 'Get pet by animal_id.',
                '/breeds/{breed_id}' => 'Get breed by breed_id.',
                '/entries/{entry_id}' => 'Get entry by entry_id.',
                '/appearances/{appearance_id}' => 'Get appearance by appearance_id.',
                '/records/{record_id}' => 'Get record by record_id',
                '/categories/{category_id}' => 'Get category by category_id'),
            'relationships' =>  array(
                '/categories/{category_id}/pets' => 'Get pet by category_id.',
                '/entries/{entry_id}/pets' => 'Get pet by entry_id.'),
            'post' =>  array(
                '/pets' => 'Create new pet ',
                '/entries' => 'Create new entry',
                '/appearances' => 'Create new appearance',
                '/records' => 'Create new record.'),
            'put' =>  array(
                '/pets' => 'Updating the pets'
            ),
        );

        return $this->prepareOkResponse($response, $data);
    }
}
