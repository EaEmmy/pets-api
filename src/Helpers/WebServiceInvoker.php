<?php


namespace Vanier\Api\Helpers;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * Summary of WebServiceInvoker
 */
class WebServiceInvoker
{
    private array $request_options = [];

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        //$this->request_options = $options;
    }

    /**
     * Summary of invokeUri
     * @param string $resource_uri
     * @throws Exception
     * @return string
     */
    public function invokeUri(string $resource_uri)
    {
        //1) Instantiate a client options
        $client = new Client();

        $response = $client->request('GET',
         $resource_uri, $this->request_options);
        //$client->get('/shows');

        //Part 2 -- We need to prossess the response
        //  1) Did we get the 200 OK
        if($response->getStatusCode() != 200){
            throw new Exception('Something went wrong!Try again?');
        }

        //var_dump($response->getHeaderLine('Content-Type'));

        // 2) Verify the requested resource representation

        if(!str_contains($response->getHeaderLine('Content-Type'), 'application/json')){
            throw new Exception('Unprocessed data forset');
        }

        // 3) We can retrieve the data from the response body

        $data = $response->getBody()->getContents();
        //var_dump($data);
        //$shows = json_decode($data);
        return $data;
        //var_dump($shows);
    }
}
