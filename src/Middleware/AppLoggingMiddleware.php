<?php

namespace Vanier\Api\Middleware;


use DateTimeZone;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotFoundException;
use Vanier\Api\Exceptions\HttpNotAcceptableException;

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;


/**
 * Summary of AppLoggingMiddleware
 */
class AppLoggingMiddleware implements MiddlewareInterface
{
    /**
     * Summary of __construct
     * @param array $options
     */
    public function __construct(array $options = []){
    
    }

    /**
     * Summary of process
     * @param Request $request
     * @param RequestHandler $handler
     * @return ResponseInterface
     */
    public function process (Request $request, RequestHandler $handler): ResponseInterface{

        // inspect request first
        $logger = new Logger('access_logs');
        $logger->setTimezone(new DateTimeZone('America/Toronto'));
        $log_handler = new StreamHandler(APP_LOG_DIR.'access.log', Logger::DEBUG);
        $logger->pushHandler($log_handler);

        // log any info
        $logger->info("hello from middleware");
        $params = $request->getQueryParams();
        
        $ip_address = $_SERVER["REMOTE_ADDR"];
        $logger->info("IP: ".$ip_address.' '.$request->getMethod().
                      ' '.$request->getUri()->getPath(), $params);
        
        $data = $request->getParsedBody();
        $logger->info("IP: ".$ip_address.' DATA '. $data);

        $response = $handler->handle($request);
        return $response;
    }
}
