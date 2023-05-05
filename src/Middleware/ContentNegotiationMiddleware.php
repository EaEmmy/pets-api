<?php

namespace Vanier\Api\Middleware;


use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotFoundException;
use Vanier\Api\Exceptions\HttpNotAcceptableException;

/**
 * Summary of ContentNegotiationMiddleware
 */
class ContentNegotiationMiddleware implements MiddlewareInterface
{

    /**
     * Summary of supported_types
     * @var array
     */
    private $supported_types = [APP_MEDIA_TYPE_JSON];
    /**
     * Summary of __construct
     * @param array $options
     */
    public function __construct(array $options = []){
            $this->supported_types = array_merge($options, $this->supported_types);
        }

    /**
     * Summary of process
     * @param Request $request
     * @param RequestHandler $handler
     * @return ResponseInterface
     */
    public function process (Request $request, RequestHandler $handler): ResponseInterface{

        $accept = $request->getHeaderLine("Accept");
        $str_supported_types = implode("|", $this->supported_types);
        //Step 2) compare the requested resource representation format with what the service can produce
        if(!str_contains(APP_MEDIA_TYPE_JSON, $accept)){
            //unsupported media
            //throw new HttpNotAcceptableException($request); 

            //Instantiate response object
            $response = new \Slim\Psr7\Response();

            //Associative array is a collection
            $error_data = [
                "code" => StatusCodeInterface::STATUS_NOT_ACCEPTABLE,
                "message" => "Not Acceptable", 
                "description" => "The server cannot produce a response matching the list of acceptable values define in the request."
            ];

            $response->getBody()->write(json_encode($error_data)); 

            return $response
            ->withStatus(StatusCodeInterface::STATUS_NOT_ACCEPTABLE)
            ->withAddedHeader("Content-type", APP_MEDIA_TYPE_JSON); 
        }

        $response = $handler->handle($request);
        return $response;
    }
}
